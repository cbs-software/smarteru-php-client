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
use CBS\SmarterU\DataTypes\User;
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
     * The method name to pass into the XML body when making a CreateUser query
     * to the SmarterU API.
     */
    protected const SMARTERU_API_CREATE_USER_QUERY_METHOD = 'createUser';

    /**
     * The method name to pass into the query object when making a GetUser
     * query to the SmarterU API.
     */
    protected const SMARTERU_API_GET_USER_QUERY_METHOD = 'getUser';

    /**
     * The method name to pass into the query object when making a ListUsers
     * query to the SmarterU API.
     */
    protected const SMARTERU_API_LIST_USERS_QUERY_METHOD = 'listUsers';

    /**
     * The method name to pass into the XML body when making an UpdateUser
     * query to the SmarterU API.
     */
    protected const SMARTERU_API_UPDATE_USER_QUERY_METHOD = 'updateUser';

    /**
     * The method name to pass into the query object when making a
     * GetUserGroups query to the SmarterU API.
     */
    protected const SMARTERU_API_GET_USER_GROUPS_QUERY_METHOD = 'getUserGroups';

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
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        return (clone $user)
            ->setEmail($email)
            ->setEmployeeId($employeeId);
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
    public function getUser(GetUserQuery $query): ?User {
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
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

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
     * Make an UpdateUser query to the SmarterU API.
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

        $response = $this
            ->getHttpClient()
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        return (clone $user)
            ->setEmail($email)
            ->setEmployeeId($employeeId);
    }

    /**
     * Make a GetUserGroups query to the SmarterU API.
     *
     * @param GetUserQuery $query The query representing the User whose Groups
     *      are to be read.
     * @return array An array of [$result, $errors] where $result is an array
     *      of any information returned by the SmarterU API and $errors is an
     *      array of any error messages returned by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function getUserGroups(GetUserQuery $query): array {
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
            $currentGroup = [];
            $permissions = [];
            foreach ($group->Permissions->children() as $permission) {
                $permissions[] = (string) $permission;
            }
            $currentGroup['Name'] = $group->Name;
            $currentGroup['Identifier'] = $group->Identifier;
            $currentGroup['IsHomeGroup'] = $group->IsHomeGroup;
            $currentGroup['Permissions'] = $permissions;
            $groups[] = $currentGroup;
        }

        $result = [
            'Response' => $groups,
            'Errors' => []
        ];

        return $result;
    }

    /**
     * Make a CreateGroup query to the SmarterU API.
     *
     * @param Group $group The Group to create
     * @return array An array of [$result, $errors] where $result is an array
     *      of any information returned by the SmarterU API and $errors is an
     *      array of any error messages returned by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function createGroup(Group $group): array {
        $xml = $group->toXml(
            $this->getAccountApi(),
            $this->getUserApi(),
            'createGroup'
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

        $groupName = (string) $bodyAsXml->Info->Group;
        $groupId = (string) $bodyAsXml->Info->GroupID;

        $groupAsArray = [
            'Group' => $groupName
        ];

        if (!empty($groupId)) {
            $groupAsArray['GroupID'] = $groupId;
        }

        $result = [
            'Response' => $groupAsArray,
            'Errors' => []
        ];

        return $result;
    }

    /**
     * Make a GetGroup query to the SmarterU API.
     *
     * @param GetGroupQuery $query The query representing the Group to return
     * @return array An array of [$result, $errors] where $result is an array
     *      of any information returned by the SmarterU API and $errors is an
     *      array of any error messages returned by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset in both this instance of the Client and in the
     *      query passed in as a parameter.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function getGroup(GetGroupQuery $query): array {
        $xml = $query->toXml($this->getAccountApi(), $this->getUserApi());

        $response = $this
            ->getHttpClient()
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $bodyAsXml = simplexml_load_string((string) $response->getBody());

        if ((string) $bodyAsXml->Result === 'Failed') {
            throw new SmarterUException($this->readErrors($bodyAsXml->Errors));
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
            $tags[] = [
                'TagID' => (string) $tag->TagID,
                'TagName' => (string) $tag->TagName,
                'TagValues' => (string) $tag->TagValues
            ];
        }

        $groupAsRead = [
            'Name' => (string) $group->Name,
            'GroupID' => (string) $group->GroupID,
            'CreatedDate' => (string) $group->CreatedDate,
            'ModifiedDate' => (string) $group->ModifiedDate,
            'Description' => (string) $group->Description,
            'HomeGroupMessage' => (string) $group->HomeGroupMessage,
            'NotificationEmails' => $notificationEmails,
            'UserCount' => (string) $group->UserCount,
            'LearningModuleCount' => (string) $group->LearningModuleCount,
            'Tags2' => $tags,
            'Status' => (string) $group->Status
        ];

        $results = [
            'Response' => $groupAsRead,
            'Errors' => []
        ];
        return $results;
    }

    /**
     * Make a ListGroups query to the SmarterU API.
     *
     * @param ListGroupsQuery $query The query representing the Groups to return
     * @return array An array of [$result, $errors] where $result is an array
     *      of any information returned by the SmarterU API and $errors is an
     *      array of any error messages returned by the SmarterU API.
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
        $xml = $query->toXml($this->getAccountApi(), $this->getUserApi());

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
        foreach ($bodyAsXml->Info->Groups->children() as $group) {
            $currentGroup = [];
            $currentGroup['Name'] = (string) $group->Name;
            $currentGroup['GroupID'] = (string) $group->GroupID;
            $groups[] = $currentGroup;
        }

        $result = [
            'Response' => $groups,
            'Errors' => []
        ];
        return $result;
    }

    /**
     * Make an UpdateGroup query to the SmarterU API.
     *
     * @param Group $group The Group to update
     * @return array An array of [$result, $errors] where $result is an array
     *      of any information returned by the SmarterU API and $errors is an
     *      array of any error messages returned by the SmarterU API.
     * @throws MissingValueException If the Account API Key and/or the User
     *      API Key are unset.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */
    public function updateGroup(Group $group): array {
        $xml = $group->toXml(
            $this->getAccountApi(),
            $this->getUserApi(),
            'updateGroup'
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
    
        $groupAsArray = [
            'Group' => (string) $bodyAsXml->Info->Group,
            'GroupID' => (string) $bodyAsXml->Info->GroupID
        ];
    
        $result = [
            'Response' => $groupAsArray,
            'Errors' => []
        ];
    
        return $result;
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
