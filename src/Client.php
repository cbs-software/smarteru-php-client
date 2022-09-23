<?php

/**
 * Contains CBS\SmarterU\Client
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/15
 */

declare(strict_types=1);

namespace CBS\SmarterU;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\Permission;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\BaseQuery;
use CBS\SmarterU\Queries\GetGroupQuery;
use CBS\SmarterU\Queries\GetUserQuery;
use CBS\SmarterU\Queries\ListGroupsQuery;
use CBS\SmarterU\Queries\ListUsersQuery;
use DateTime;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use SimpleXMLElement;

/**
 * The Client class makes API calls and translates the response to the
 * appropriate object.
 */
class Client {
    #region constants

    /**
     * The URL to POST to.
     */
    public const POST_URL = 'https://api.smarteru.com/apiv2/';

    /**
     * The method name to pass into the query object when making a GetUser
     * query to the SmarterU API.
     */
    protected const SMARTERU_API_GET_USER_QUERY_METHOD = 'getUser';

    /**
     * The method name to pass into the query object when making a
     * GetUserGroups query to the SmarterU API.
     */
    protected const SMARTERU_API_GET_USER_GROUPS_QUERY_METHOD = 'getUserGroups';

    /**
     * All valid Permissions that can be granted to a User, as defined here:
     * https://support.smarteru.com/docs/api-updateuser
     */
    protected const VALID_PERMISSIONS = [
        'MANAGE_GROUP',
        'CREATE_COURSE',
        'MANAGE_GROUP_COURSES',
        'MANAGE_USERS',
        'MANAGE_GROUP_USERS',
        'VIEW_LEARNER_RESULTS',
        'PROCTOR',
        'MARKER',
        'INSTRUCTOR'
    ];

    /**
     * The beginning of the message to use to throw an exception when the
     * SmarterU API returns a fatal error.
     */
    protected const SMARTERU_EXCEPTION_PREAMBLE = 'SmarterU rejected the request due to the following error(s): ';

    #endregion constants

    #region properties

    /**
     * The account API key, used for authentication purposes when making
     * requests to the SmarterU API.
     */
    protected string $accountApi;

    /**
     * The user API key, used for authentication purposes when making
     * requests to the SmarterU API.
     */
    protected string $userApi;

    /**
     * The HTTP Client to use to make the requests. Initialized to a functional
     * HTTP Client by default, change only for testing purposes.
     */
    protected HttpClient $httpClient;

    /**
     * The XML Generator responsible for translating the input from the user to
     * XML in order to send the request to the SmarterU API.
     */
    protected XMLGenerator $xmlGenerator;

    #endregion properties

    /**
     * Initialize a client for making queries to the SmarterU API. By default a
     * GuzzleHttp\Client will be automatically created as needed to communicate
     * with the SmarterU API.
     *
     * @param string $apiKey  the account API key, used for authentication
     *      purposes when making requests to the SmarterU API. Defaults to null.
     *      You must set the account API key via the constructor or
     *      `setAccountApi` before invoking methods which interact with the
     *      SmarterU API
     * @param string $apiUserKey  the user API key, used for authentication
     *      purposes when making requests to the SmarterU API. Defaults to null.
     *      You must set the user API key via the constructor or
     *      `setUserApi` before invoking methods which interact with the
     *      SmarterU API
     */
    public function __construct(
        string $apiKey,
        string $apiUserKey
    ) {
        $this->setAccountApi($apiKey);
        $this->setUserApi($apiUserKey);
    }

    /**
     * Get the account API key.
     *
     * @return string the account API key
     */
    public function getAccountApi(): ?string {
        return $this->accountApi;
    }

    /**
     * Set the account API key.
     *
     * @param string $accountApi the account API key
     * @return self
     */
    public function setAccountApi(string $accountApi): self {
        $this->accountApi = $accountApi;
        return $this;
    }

    /**
     * Get the user API key.
     *
     * @return string the user API key
     */
    public function getUserApi(): ?string {
        return $this->userApi;
    }

    /**
     * Set the user API key.
     *
     * @param string $userApi the user API key
     * @return self
     */
    public function setUserApi(string $userApi): self {
        $this->userApi = $userApi;
        return $this;
    }

    /**
     * Get the HTTP Client. If the client has not already been provided, a
     * new default HttpClient instance will be created and returned.
     *
     * @return HttpClient the HTTP Client
     */
    public function getHttpClient(): HttpClient {
        if (!isset($this->httpClient)) {
            $this->setHttpClient(new HttpClient([
                'base_uri' => self::POST_URL
            ]));
        }
        return $this->httpClient;
    }

    /**
     * Set the HTTP Client.
     *
     * @param HttpClient $httpClient The HTTP Client
     * @return self
     */
    public function setHttpClient(HttpClient $httpClient): self {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Get the XML Generator. If one has not already been provided, a new
     * default XMLGenerator instance will be created and returned.
     */
    public function getXMLGenerator(): XMLGenerator {
        if (!isset($this->xmlGenerator)) {
            $this->setXMLGenerator(new XMLGenerator());
        }
        return $this->xmlGenerator;
    }

    /**
     * Set the XML Generator.
     *
     * @param XMLGenerator $xmlGenerator The XML generator.
     * @return self
     */
    public function setXMLGenerator(XMLGenerator $xmlGenerator): self {
        $this->xmlGenerator = $xmlGenerator;
        return $this;
    }

    /**
     * * Make a CreateUser query to the SmarterU API.
     *
     * @param User $user the user to create
     * @return User The user as created by the API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function createUser(User $user): User {
        $xml = $this->getXMLGenerator()->createUser(
            $this->getAccountApi(),
            $this->getUserApi(),
            $user
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        return (new User())
            ->setEmail($email)
            ->setEmployeeId($employeeId);
    }

    /**
     * Read the User whose ID matches the one provided.
     *
     * @param string $employeeId The ID of the User to return.
     * @return ?User The User whose ID matches the provided ID,
     *      or null if no such User exists within your SmarterU Account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readUserById(string $id): ?User {
        $query = (new GetUserQuery())
            ->setId($id);
        
        return $this->getUser($query);
    }

    /**
     * Read the User whose email address matches the one provided.
     *
     * @param string $employeeId The email address of the User to return.
     * @return ?User The User whose email address matches the one provided,
     *      or null if no such User exists within your SmarterU Account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readUserByEmail(string $email): ?User {
        $query = (new GetUserQuery())
            ->setEmail($email);
        
        return $this->getUser($query);
    }

    /**
     * Read the User whose employee ID matches the one provided.
     *
     * @param string $employeeId The employee ID of the User to return.
     * @return ?User The User whose employee ID matches the provided ID,
     *      or null if no such User exists within your SmarterU Account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readUserByEmployeeId(string $employeeId): ?User {
        $query = (new GetUserQuery())
            ->setEmployeeId($employeeId);
        
        return $this->getUser($query);
    }

    /**
     * Make a ListUsers query to the SmarterU API.
     *
     * @param ListUsersQuery $query The query representing the Users to return
     * @return array An array of all Users matching the parameters specified by
     *      the query passed into the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function listUsers(ListUsersQuery $query): array {
        $xml = $this->getXMLGenerator()->listUsers(
            $this->getAccountApi(),
            $this->getUserApi(),
            $query
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $users = [];
        foreach ($bodyAsXml->Info->Users->children() as $user) {
            $teams = [];

            /**
             * Not casting this to an array causes the teams to be placed in a
             * SimpleXMLElement of <[arrayIndex]> teamName </[arrayIndex]>,
             * which renders the team names inaccessible because [#] is invalid
             * syntax for a node name.
             */
            foreach ((array) $user->Teams->Team as $team) {
                $teams[] = $team;
            }
            $currentUser = (new User())
                ->setId((string) $user->ID)
                ->setEmail((string) $user->Email)
                ->setEmployeeId((string) $user->EmployeeID)
                ->setGivenName((string) $user->GivenName)
                ->setSurname((string) $user->Surname)
                ->setStatus((string) $user->Status)
                ->setTitle((string) $user->Title)
                ->setDivision((string) $user->Division)
                ->setHomeGroup((string) $user->HomeGroup)
                ->setCreatedDate(new DateTime((string) $user->CreatedDate))
                ->setModifiedDate((new DateTime((string) $user->ModifiedDate)))
                ->setTeams($teams);

            $users[] = $currentUser;
        }

        return $users;
    }

    /**
     * Make an UpdateUser query to the SmarterU API. In the event that the
     * User's email address and/or employee ID are being updated, the fields
     * used to keep track of the old values in the User object will be erased
     * while making the request. This prevents outdated information from
     * mistakenly being passed into the SmarterU API when making an additional
     * updateUser query after updating a User's email address and/or employee ID.
     *
     * @param User $user The User to update
     * @return array The User as updated by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function updateUser(User $user): User {
        $xml = $this->getXMLGenerator()->updateUser(
            $this->getAccountApi(),
            $this->getUserApi(),
            $user
        );

        // If the User's email address and/or employee ID are being updated,
        // reset the old values to null after generating the XML. This prevents
        // any future updateUser requests on the same User object from
        // mistakenly attempting to identify the User using old information
        // that was changed by the updateUser request that made changes to the
        // User's email address and/or employee ID.
        if (!empty($user->getOldEmail())) {
            $user->setOldEmail(null);
        }
        if (!empty($user->getOldEmployeeId())) {
            $user->setOldEmployeeId(null);
        }

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        return (new User())
            ->setEmail($email)
            ->setEmployeeId($employeeId);
    }

    /**
     * Read the Groups that have the specified User as a member. The User will
     * be identified by their ID.
     *
     * @param string $id The ID of the User to search for.
     * @return array All Groups that User is a member of.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readGroupsForUserById(string $id): array {
        $query = (new GetUserQuery())
            ->setId($id);

        return $this->getUserGroups($query);
    }

    /**
     * Read the Groups that have the specified User as a member. The User will
     * be identified by their email address.
     *
     * @param string $email The email address of the User to search for.
     * @return array All Groups that User is a member of.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readGroupsForUserByEmail(string $email): array {
        $query = (new GetUserQuery())
            ->setEmail($email);

        return $this->getUserGroups($query);
    }

    /**
     * Read the Groups that have the specified User as a member. The User will
     * be identified by their employee ID.
     *
     * @param string $employeeId The employee ID of the User to search for.
     * @return array All Groups that User is a member of.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readGroupsForUserByEmployeeId(string $employeeId): array {
        $query = (new GetUserQuery())
            ->setEmployeeId($employeeId);

        return $this->getUserGroups($query);
    }

    /**
     * Make a CreateGroup query to the SmarterU API.
     *
     * @param Group $group The Group to create
     * @return Group The group as created by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function createGroup(Group $group): Group {
        $xml = $this->getXMLGenerator()->createGroup(
            $this->getAccountApi(),
            $this->getUserApi(),
            $group
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $groupName = (string) $bodyAsXml->Info->Group;
        $groupId = (string) $bodyAsXml->Info->GroupID;

        return (new Group())
            ->setName($groupName)
            ->setGroupId($groupId);
    }

    /**
     * Read the Group whose ID matches the one provided.
     *
     * @param string $id The ID of the Group to return
     * @return ?Group The Group as read by the API, or null if no Group
     *      matching the provided ID exists within your SmarterU account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readGroupById(string $id): ?Group {
        $query = (new GetGroupQuery())
            ->setGroupId($id);

        return $this->getGroup($query);
    }

    /**
     * Read the Group whose name matches the one provided.
     *
     * @param string $name The name of the Group to return
     * @return ?Group The Group as read by the API, or null if no Group
     *      matching the provided name exists within your SmarterU account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function readGroupByName(string $name): ?Group {
        $query = (new GetGroupQuery())
            ->setName($name);

        return $this->getGroup($query);
    }

    /**
     * Make a ListGroups query to the SmarterU API.
     *
     * @param ListGroupsQuery $query The query representing the Groups to return
     * @return array An array of any Groups returned by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function listGroups(ListGroupsQuery $query): array {
        $xml = $this->getXMLGenerator()->listGroups(
            $this->getAccountApi(),
            $this->getUserApi(),
            $query
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $groups = [];
        foreach ($bodyAsXml->Info->Groups->children() as $group) {
            $currentGroup = (new Group())
                ->setName((string) $group->Name)
                ->setGroupId((string) $group->GroupID);
            $groups[] = $currentGroup;
        }

        return $groups;
    }

    /**
     * Make an UpdateGroup query to the SmarterU API. In the event that the
     * Group's name and/or ID are being updated, the fields used to keep track
     * of the old values in the Group object will be erased while making the
     * request. This prevents outdated information from mistakenly being passed
     * into the SmarterU API when making an additional updateGroup query after
     * updating a Group's name and/or ID.
     *
     * @param Group $group The Group to update
     * @return Group The Group as updated by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function updateGroup(Group $group): Group {
        $xml = $this->getXMLGenerator()->updateGroup(
            $this->getAccountApi(),
            $this->getUserApi(),
            $group
        );

        // If the Group's name and/or ID are being updated, reset the old
        // values to null after generating the XML. This prevents any future
        // updateGroup requests on the same Group object from mistakenly
        // attempting to identify the Group using old information that was
        // changed by a previous updateGroup request.
        if (!empty($group->getOldName())) {
            $group->setOldName(null);
        }
        if (!empty($group->getOldGroupId())) {
            $group->setOldGroupId(null);
        }

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $groupName = (string) $bodyAsXml->Info->Group;
        $groupId = (string) $bodyAsXml->Info->GroupID;
    
        return (new Group())
            ->setName($groupName)
            ->setGroupId($groupId);
    }

    /**
     * Make an addUsersToGroup query to the SmarterU API. This query will add
     * any specified User(s) to the Group, but will not grant them any
     * permissions within the Group. If you would like the Users to have any
     * additional permissions, you must call Client::grantPermissions() after
     * assigning the User to the Group.
     *
     * @param User[] $users An array containing one or more Users to add to the
     *      Group.
     * @param Group $group The Group to which the User(s) will be added.
     * @return Group The Group as updated by the SmarterU API.
     * @throws InvalidArgumentException If the "$users" array contains a value
     *      that is not a User.
     * @throws MissingValueException If the "$users" array contains a User that
     *      does not have an email address or an employee ID.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function addUsersToGroup(array $users, Group $group): Group {
        $xml = $this->getXMLGenerator()->changeGroupMembers(
            $this->getAccountApi(),
            $this->getUserApi(),
            $users,
            $group,
            'Add'
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $groupName = (string) $bodyAsXml->Info->Group;
        $groupId = (string) $bodyAsXml->Info->GroupID;

        return (new Group())
            ->setName($groupName)
            ->setGroupId($groupId);
    }

    /**
     * Make a removeUsersFromGroup query to the SmarterU API.
     *
     * @param User[] $users An array containing one or more Users to remove
     *      from the Group.
     * @param Group $group The Group from which the User(s) will be removed.
     * @return Group The Group as updated by the SmarterU API.
     * @throws InvalidArgumentException If the "$users" array contains a value
     *      that is not a User.
     * @throws MissingValueException If the "$users" array contains a User that
     *      does not have an email address or an employee ID.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function removeUsersFromGroup(array $users, Group $group): Group {
        $xml = $this->getXMLGenerator()->changeGroupMembers(
            $this->getAccountApi(),
            $this->getUserApi(),
            $users,
            $group,
            'Remove'
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $groupName = (string) $bodyAsXml->Info->Group;
        $groupId = (string) $bodyAsXml->Info->GroupID;

        return (new Group())
            ->setName($groupName)
            ->setGroupId($groupId);
    }

    /**
     * Make a GrantPermissions request to the SmarterU API.
     *
     * @param User $user The User to grant permissions to.
     * @param Group $group The Group in which the User will be granted
     *      the specified permissions.
     * @param string[] $permissions The permissions to be granted to the
     *      specified User within the specified Group.
     * @return array The User as updated by the SmarterU API.
     * @throws InvalidArgumentException If any value in the "$permissions"
     *      array is not a string or is not one of the permissions accepted
     *      by the SmarterU API.
     * @throws MissingValueException If the user whose permissions are being
     *      modified doesn't have an email address or an employee ID, or if the
     *      Group in which the permissions are being modified does not have a
     *      name or an ID.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function grantPermissions(
        User $user,
        Group $group,
        array $permissions
    ): User {
        foreach ($permissions as $permission) {
            if (!is_string($permission)) {
                throw new InvalidArgumentException(
                    '"$permissions" must be an array of strings.'
                );
            }
            if (!in_array($permission, self::VALID_PERMISSIONS)) {
                throw new InvalidArgumentException(
                    '"' . $permission . '" is not one of the valid permissions.'
                );
            }
        }
        $xml = $this->getXMLGenerator()->changePermissions(
            $this->getAccountApi(),
            $this->getUserApi(),
            $user,
            $group,
            $permissions,
            'Grant'
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        return (new User())
            ->setEmail($email)
            ->setEmployeeId($employeeId);
    }

    /**
     * Make a RevokePermissions request to the SmarterU API.
     *
     * @param User $user The User to revoke permissions from.
     * @param Group $group The Group in which the User will have the specified
     *      permissions revoked.
     * @param string[] $permissions The permissions to be revoked from the
     *      specified User within the specified Group.
     * @return array The User as updated by the SmarterU API.
     * @throws InvalidArgumentException If any value in the "$permissions"
     *      array is not a string or is not one of the permissions accepted
     *      by the SmarterU API.
     * @throws MissingValueException If the user whose permissions are being
     *      modified doesn't have an email address or an employee ID, or if the
     *      Group in which the permissions are being modified does not have a
     *      name or an ID.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function revokePermissions(
        User $user,
        Group $group,
        array $permissions
    ): User {
        foreach ($permissions as $permission) {
            if (!is_string($permission)) {
                throw new InvalidArgumentException(
                    '"$permissions" must be an array of strings.'
                );
            }
            if (!in_array($permission, self::VALID_PERMISSIONS)) {
                throw new InvalidArgumentException(
                    '"' . $permission . '" is not one of the valid permissions.'
                );
            }
        }
        $xml = $this->getXMLGenerator()->changePermissions(
            $this->getAccountApi(),
            $this->getUserApi(),
            $user,
            $group,
            $permissions,
            'Deny'
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        return (new User())
            ->setEmail($email)
            ->setEmployeeId($employeeId);
    }

    /**
     * Make a GetLearnerReport query to the SmarterU API.
     *
     * @param GetLearnerReportQuery $query The query by which to filter the
     *      results of the Learner Report.
     * @return LearnerReport[] A list of all LearnerReports matching the query.
     * @throws MissingValueException If the Group(s) or User(s) to include in
     *      the report are not identified, or if Groups are filtered by tags
     *      that are not identified.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function getLearnerReport(GetLearnerReportQuery $query): array {
        $xml = $this->getXMLGenerator()->getLearnerReport(
            $this->getAccountApi(),
            $this->getUserApi(),
            $query
        );

        $response = $this
            ->getHttpClient()
            ->request(
                'POST',
                self::POST_URL, 
                ['form_params' => ['Package' => $xml]]
        );

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $learnerReports = [];
        foreach ($bodyAsXml->Info->LearnerReport->children() as $report) {
            $currentReport = (new LearnerReport())
                ->setId((string) $report->ID)
                ->setCourseName((string) $report->CourseName)
                ->setLastName((string) $report->LastName)
                ->setFirstName((string) $report->FirstName)
                ->setLearningModuleId((string) $report->LearningModuleID)
                ->setUserId((string) $report->UserID)
                ->setCreatedDate(new DateTime($report->CreatedDate))
                ->setModifiedDate(new DateTime($report->ModifiedDate));
            if (isset($report->AlternateEmail)) {
                $currentReport->setAlternateEmail(
                    (string) $report->AlternateEmail
                );
            }
            if (isset($report->CompletedDate)) {
                $currentReport->setCompletedDate(
                    new DateTime((string) $report->CompletedDate)
                );
            }
            if (isset($report->CourseDuration)) {
                $currentReport->setCourseDuration(
                    (string) $report->CourseDuration
                );
            }
            if (isset($report->CourseSessionID)) {
                $currentReport->setCourseSessionId(
                    (string) $report->CourseSessionID
                );
            }
            if (isset($report->Division)) {
                $currentReport->setDivision((string) $report->Division);
            }
            if (isset($report->DueDate)) {
                $currentReport->setDueDate(
                    new DateTime((string) $report->DueDate)
                );
            }
            if (isset($report->EmployeeID)) {
                $currentReport->setEmployeeId(
                    (string) $report->EmployeeID
                );
            }
            if (isset($report->EnrolledDate)) {
                $currentReport->setEnrolledDate(
                    new DateTime((string) $report->EnrolledDate)
                );
            }
            if (isset($report->Grade)) {
                $currentReport->setGrade((string) $report->Grade);
            }
            if (isset($report->GradePercentage)) {
                $currentReport->setGradePercentage(
                    (float) $report->GradePercentage
                );
            }
            if (isset($report->GroupID)) {
                $currentReport->setGroupId((string) $report->GroupID);
            }
            if (isset($report->GroupName)) {
                $currentReport->setGroupName((string) $report->GroupName);
            }
            if (isset($report->LastAccessedDate)) {
                $currentReport->setLastAccessedDate(
                    new DateTime((string) $report->LastAccessedDate)
                );
            }
            if (isset($report->Points)) {
                $currentReport->setPoints((string) $report->Points);
            }
            if (isset($report->Progress)) {
                $currentReport->setProgress((string) $report->Progress);
            }
            if (isset($report->RoleID)) {
                $currentReport->setRoleId((string) $report->RoleID);
            }
            if (isset($report->StartedDate)) {
                $currentReport->setStartedDate(
                    new DateTime((string) $report->StartedDate)
                );
            }
            if (isset($report->SubscriptionName)) {
                $currentReport->setSubscriptionName(
                    (string) $report->SubscriptionName
                );
            }
            if (isset($report->Title)) {
                $currentReport->setTitle((string) $report->Title);
            }
            if (isset($report->UserEmail)) {
                $currentReport->setUserEmail((string) $report->UserEmail);
            }
            if (isset($report->VariantEndDate)) {
                $currentReport->setVariantEndDate(
                    new DateTime((string) $report->VariantEndDate)
                );
            }
            if (isset($report->VariantName)) {
                $currentReport->setVariantName((string) $report->VariantName);
            }
            if (isset($report->VariantStartDate)) {
                $currentReport->setVariantStartDate(
                    new DateTime((string) $report->VariantStartDate)
                );
            }
            $learnerReports[] = $currentReport;
        }

        return $learnerReports;        
    }


    /**
     * Make a GetUser query to the SmarterU API.
     *
     * @param GetUserQuery $query The query representing the User to return
     * @return ?User The User matching the identifier specified in the query,
     *      or null if no such User exists within your SmarterU account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    protected function getUser(GetUserQuery $query): ?User {
        $query->setMethod(self::SMARTERU_API_GET_USER_QUERY_METHOD);

        $xml = $this->getXMLGenerator()->getUser(
            $this->getAccountApi(),
            $this->getUserApi(),
            $query
        );

        $response = $this
            ->getHttpClient()
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            $errors = $this->readErrors($bodyAsXml->Errors);
            // The SmarterU API treats "User not found" as a fatal error.
            // If the API returns this error, this if statement will catch it
            // before it becomes an exception and return null.
            if (str_contains($errors, 'GU:03: The user requested does not exist.')) {
                return null;
            }
            throw new SmarterUException($errors);
        }

        $user = $bodyAsXml->Info->User;
        if (count($user->children()) === 0) {
            return null;
        }
        $teams = [];

        /**
         * Not casting this to an array causes the teams to be placed in a
         * SimpleXMLElement of <[arrayIndex]> teamName </[arrayIndex]>, which
         * renders the team names inaccessible because [#] is invalid syntax
         * for a node name.
         */
        foreach ((array) $user->Teams->Team as $team) {
            $teams[] = $team;
        }

        return (new User())
            ->setId((string) $user->ID)
            ->setEmail((string) $user->Email)
            ->setEmployeeId((string) $user->EmployeeID)
            ->setCreatedDate(new DateTime((string) $user->CreatedDate))
            ->setModifiedDate(new DateTime((string) $user->ModifiedDate))
            ->setGivenName((string) $user->GivenName)
            ->setSurname((string) $user->Surname)
            ->setLanguage((string) $user->Language)
            ->setAllowFeedback(filter_var(
                (string) $user->AllowFeedback,
                FILTER_VALIDATE_BOOLEAN
            ))
            ->setStatus((string) $user->Status)
            ->setAuthenticationType((string) $user->AuthenticationType)
            ->setTimezone((string) $user->Timezone)
            ->setAlternateEmail((string) $user->AlternateEmail)
            ->setHomeGroup((string) $user->HomeGroup)
            ->setOrganization((string) $user->Organization)
            ->setTitle((string) $user->Title)
            ->setDivision((string) $user->Division)
            // TODO implement supervisors. For iteration 1, we can assume it's blank
            ->setPhonePrimary((string) $user->PhonePrimary)
            ->setPhoneAlternate((string) $user->PhoneAlternate)
            ->setPhoneMobile((string) $user->PhoneMobile)
            ->setSendMailTo((string) $user->SendMailTo)
            ->setSendEmailTo((string) $user->SendEmailTo)
            ->setFax((string) $user->Fax)
            ->setAddress1((string) $user->Address1)
            ->setAddress2((string) $user->Address2)
            ->setCity((string) $user->City)
            ->setPostalCode((string) $user->PostalCode)
            ->setProvince((string) $user->Province)
            ->setCountry((string) $user->Country)
            ->setLearnerNotifications(filter_var(
                (string) $user->SendWeeklyTaskReminder,
                FILTER_VALIDATE_BOOLEAN
            ))
            ->setSupervisorNotifications(filter_var(
                (string) $user->SendWeeklyProgressSummary,
                FILTER_VALIDATE_BOOLEAN
            ))
            ->setTeams($teams)
            // TODO implement roles. For iteration 1, we can assume it's blank.
            // TODO implement custom fields. For iteration 1, we can assume it's blank.
            // TODO implement venues. For iteration 1, we can assume it's blank.
            // TODO implement wages. For iteration 1, we can assume it's blank.
            ->setReceiveNotifications(filter_var(
                (string) $user->ReceiveNotifications,
                FILTER_VALIDATE_BOOLEAN
            ));
    }

    /**
     * Make a GetUserGroups query to the SmarterU API.
     *
     * @param GetUserQuery $query The query representing the User whose Groups
     *      are to be read.
     * @return array An array of GroupPermissions instances representing the
     *      specified User's membership in the Group(s) he or she is a member
     *      of.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    protected function getUserGroups(GetUserQuery $query): array {
        $query->setMethod(self::SMARTERU_API_GET_USER_GROUPS_QUERY_METHOD);

        $xml = $this->getXMLGenerator()->getUser(
            $this->getAccountApi(),
            $this->getUserApi(),
            $query
        );

        $response = $this
            ->getHttpClient()
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $groups = [];
        foreach ($bodyAsXml->Info->UserGroups->children() as $group) {
            $permissions = [];
            foreach ($group->Permissions->children() as $code) {
                $permissions[] = (string) $code;
            }
            $currentGroup = (new Group())
                ->setName((string) $group->Name)
                ->setGroupId((string) $group->Identifier)
                ->setPermissions($permissions);
            $groups[] = $currentGroup;
        }

        return $groups;
    }

    /**
     * Make a GetGroup query to the SmarterU API.
     *
     * @param GetGroupQuery $query The query representing the Group to return
     * @return ?Group The Group as read by the API, or null if no Group
     *      matching the query exists within your SmarterU account.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    protected function getGroup(GetGroupQuery $query): ?Group {
        $xml = $this->getXMLGenerator()->getGroup(
            $this->getAccountApi(),
            $this->getUserApi(),
            $query
        );

        $response = $this
            ->getHttpClient()
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            $errors = $this->readErrors($bodyAsXml->Errors);
            /**
             * The SmarterU API treats "Group not found" as a fatal error.
             * If the API returns this error, this if statement will catch it
             * before it becomes an exception and return null.
             */
            if (str_contains($errors, 'GG:03: The requested group does not exist.')) {
                return null;
            }
            throw new SmarterUException($errors);
        }

        $group = $bodyAsXml->Info->Group;

        $notificationEmails = [];
        $tags = [];

        /**
         * Not casting this to an array causes the emails to be placed in a
         * SimpleXMLElement of <[arrayIndex]> email </[arrayIndex]>, which
         * renders the emails inaccessible because [#] is invalid syntax
         * for a node name.
         */
        foreach ((array) $group->NotificationEmails->NotificationEmail as $email) {
            $notificationEmails[] = $email;
        }

        foreach ($group->Tags2->children() as $tag) {
            $currentTag = (new Tag())
                ->setTagId((string) $tag->TagID)
                ->setTagName((string) $tag->TagName)
                ->setTagValues((string) $tag->TagValues);
            $tags[] = $currentTag;
        }

        return (new Group())
            ->setName((string) $group->Name)
            ->setGroupId((string) $group->GroupID)
            ->setCreatedDate(new DateTime((string) $group->CreatedDate))
            ->setModifiedDate(new DateTime((string) $group->ModifiedDate))
            ->setDescription((string) $group->Description)
            ->setHomeGroupMessage((string) $group->HomeGroupMessage)
            ->setNotificationEmails($notificationEmails)
            ->setUserCount((int) $group->UserCount)
            ->setLearningModuleCount((int) $group->LearningModuleCount)
            ->setTags($tags)
            ->setStatus((string) $group->Status);
    }

    /**
     * Translate the error message(s) returned by the SmarterU API to a string
     * representing the message to use for an exception.
     *
     * @param SimpleXMLElement $errors the <errors> portion of the response
     * @return string a string representation of these errors
     */
    private function readErrors(SimpleXMLElement $errors): string {
        $errorsAsString = self::SMARTERU_EXCEPTION_PREAMBLE;
        foreach ($errors->children() as $error) {
            $errorsAsString .= (string) $error->ErrorID;
            $errorsAsString .= ': ';
            $errorsAsString .= (string) $error->ErrorMessage;
            $errorsAsString .= ', ';
        }
        return substr($errorsAsString, 0, -2);
    }
}
