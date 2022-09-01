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
     * The method name to pass into the query object when making a GetUser
     * query to the SmarterU API.
     */
    protected const SMARTERU_API_GET_USER_QUERY_METHOD = 'getUser';

    /**
     * The method name to pass into the query object when making a
     * GetUserGroups query to the SmarterU API.
     */
    protected const SMARTERU_API_GET_USER_GROUPS_QUERY_METHOD = 'getUserGroups';

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
     * @param string|null $apiKey  the account API key, used for authentication
     *      purposes when making requests to the SmarterU API. Defaults to null.
     *      You must set the account API key via the constructor or
     *      `setAccountApi` before invoking methods which interact with the
     *      SmarterU API
     * @param string|null $apiUserKey  the user API key, used for authentication
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
    }

    /**
     * Make a CreateUser query to the SmarterU API.
     *
     * @param User $user the user to create
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
    public function createUser(User $user): array {
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);

        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if ($errors->count() !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following errors: ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ": ";
                $errorsAsString .= $message;
                $errorsAsString .= ", ";
            }
            throw new SmarterUException($errorsAsString);
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        $userAsArray = [
            'Email' => $email,
            'EmployeeID' => $employeeId
        ];

        $result = [
            'Response' => $userAsArray,
            'Errors' => $errorMessages
        ];

        return $result;
    }

    /**
     * Make a GetUser query to the SmarterU API.
     *
     * @param GetUserQuery $query The query representing the User to return
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
    public function getUser(GetUserQuery $query): array {
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);
        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if (count($errors) !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following errors: ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ': ';
                $errorsAsString .= $message;
                $errorsAsString .= ', ';
            }
            throw new SmarterUException($errorsAsString);
        }

        $user = $bodyAsXml->Info->User;
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

        $userAsRead = [
            'ID' => $user->ID,
            'Email' => $user->Email,
            'EmployeeID' => $user->EmployeeID,
            'CreatedDate' => $user->CreatedDate,
            'ModifiedDate' => $user->ModifiedDate,
            'GivenName' => $user->GivenName,
            'Surname' => $user->Surname,
            'Language' => $user->Language,
            'AllowFeedback' => $user->AllowFeedback,
            'Status' => $user->Status,
            'AuthenticationType' => $user->AuthenticationType,
            'Timezone' => $user->Timezone,
            'AlternateEmail' => $user->AlternateEmail,
            'HomeGroup' => $user->HomeGroup,
            'Organization' => $user->Organization,
            'Title' => $user->Title,
            'Division' => $user->Division,
            // TODO implement supervisors. For iteration 1, we can assume it's blank
            'Supervisors' => [],
            'PhonePrimary' => $user->PhonePrimary,
            'PhoneAlternate' => $user->PhoneAlternate,
            'PhoneMobile' => $user->PhoneMobile,
            'SendMailTo' => $user->SendMailTo,
            'SendEmailTo' => $user->SendEmailTo,
            'Fax' => $user->Fax,
            'Address1' => $user->Address1,
            'Address2' => $user->Address2,
            'City' => $user->City,
            'PostalCode' => $user->PostalCode,
            'Province' => $user->Province,
            'Country' => $user->Country,
            'LearnerNotifications' => $user->SendWeeklyTaskReminder,
            'SupervisorNotifications' => $user->SendWeeklyProgressSummary,
            'Teams' => $teams,
            // TODO implement roles. For iteration 1, we can assume it's blank.
            'Roles' => [],
            // TODO implement custom fields. For iteration 1, we can assume it's blank.
            'CustomFields' => [],
            // TODO implement venues. For iteration 1, we can assume it's blank.
            'Venues' => [],
            // TODO implement wages. For iteration 1, we can assume it's blank.
            'Wages' => [],
            'ReceiveNotifications' => $user->ReceiveNotifications
        ];

        $results = [
            'Response' => $userAsRead,
            'Errors' => $errorMessages
        ];
        return $results;
    }

    /**
     * Make a ListUsers query to the SmarterU API.
     *
     * @param ListUsersQuery $query The query representing the Users to return
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);

        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if (count($errors) !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following errors: ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ': ';
                $errorsAsString .= $message;
                $errorsAsString .= ', ';
            }
            throw new SmarterUException($errorsAsString);
        }

        $users = [];
        foreach ($bodyAsXml->Info->Users->children() as $user) {
            $currentUser = [];
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
            $currentUser['ID'] = (string) $user->ID;
            $currentUser['Email'] = (string) $user->Email;
            $currentUser['EmployeeID'] = (string) $user->EmployeeID;
            $currentUser['GivenName'] = (string) $user->GivenName;
            $currentUser['Surname'] = (string) $user->Surname;
            $currentUser['Name'] = (string) $user->GivenName . ' ' . (string) $user->Surname;
            $currentUser['Status'] = (string) $user->Status;
            $currentUser['Title'] = (string) $user->Title;
            $currentUser['Division'] = (string) $user->Division;
            $currentUser['HomeGroup'] = (string) $user->HomeGroup;
            $currentUser['CreatedDate'] = (string) $user->CreatedDate;
            $currentUser['ModifiedDate'] = (string) $user->ModifiedDate;
            $currentUser['Teams'] = $teams;
            $users[] = $currentUser;
        }

        $result = [
            'Response' => $users,
            'Errors' => $errorMessages
        ];
        return $result;
    }

    /**
     * Make an UpdateUser query to the SmarterU API.
     *
     * @param User $user The User to update
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
    public function updateUser(User $user): array {
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);

        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if ($errors->count() !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following errors: ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ": ";
                $errorsAsString .= $message;
                $errorsAsString .= ", ";
            }
            throw new SmarterUException($errorsAsString);
        }

        $email = (string) $bodyAsXml->Info->Email;
        $employeeId = (string) $bodyAsXml->Info->EmployeeID;

        $userAsArray = [
            'Email' => $email,
            'EmployeeID' => $employeeId
        ];

        $result = [
            'Response' => $userAsArray,
            'Errors' => $errorMessages
        ];

        return $result;
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

        $xml = $query->toXml($this->getAccountApi(), $this->getUserApi());

        $response = $this
            ->getHttpClient()
            ->request('POST', self::POST_URL, ['form_params' => [
                'Package' => $xml]
        ]);

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);
        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if (count($errors) !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following errors: ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ': ';
                $errorsAsString .= $message;
                $errorsAsString .= ', ';
            }
            throw new SmarterUException($errorsAsString);
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
            'Errors' => $errorMessages
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);

        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if ($errors->count() !== 0) {
            $errorMessages = $this->readErrors($errors);
        }
        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following error(s): ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ": ";
                $errorsAsString .= $message;
                $errorsAsString .= ", ";
            }
            throw new SmarterUException($errorsAsString);
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
            'Errors' => $errorMessages
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);
        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if (count($errors) !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following error(s): ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ': ';
                $errorsAsString .= $message;
                $errorsAsString .= ', ';
            }
            throw new SmarterUException($errorsAsString);
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
            'Errors' => $errorMessages
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);
        $result = (string) $bodyAsXml->Result;

        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if (count($errors) !== 0) {
            $errorMessages = $this->readErrors($errors);
        }

        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following errors: ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ': ';
                $errorsAsString .= $message;
                $errorsAsString .= ', ';
            }
            throw new SmarterUException($errorsAsString);
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
            'Errors' => $errorMessages
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

        $body = (string) $response->getBody();
        $bodyAsXml = simplexml_load_string($body);
    
        $result = (string) $bodyAsXml->Result;
    
        $errorMessages = [];
        $errors = $bodyAsXml->Errors;
        if ($errors->count() !== 0) {
            $errorMessages = $this->readErrors($errors);
        }
    
        if ($result === 'Failed') {
            $errorsAsString = 'SmarterU rejected the request due to the following error(s): ';
            foreach ($errorMessages as $id => $message) {
                $errorsAsString .= $id;
                $errorsAsString .= ": ";
                $errorsAsString .= $message;
                $errorsAsString .= ", ";
            }
            throw new SmarterUException($errorsAsString);
        }
    
        $groupAsArray = [
            'Group' => (string) $bodyAsXml->Info->Group,
            'GroupID' => (string) $bodyAsXml->Info->GroupID
        ];
    
        $result = [
            'Response' => $groupAsArray,
            'Errors' => $errorMessages
        ];
    
        return $result;
    }

    /**
     * Translate the error message(s) returned by the SmarterU API to an array
     * of 'ErrorID' => 'ErrorMessage'. For any non-fatal errors, this array
     * will be part of the array returned by the request methods. For any fatal
     * errors, this array will be converted into a comma-separated string and
     * used to throw an exception.
     *
     * @param SimpleXMLElement $errors the <errors> portion of the response
     * @return array an array representation of these errors
     */
    private function readErrors(SimpleXMLElement $errors): array {
        $errorsAsArray = [];
        foreach ($errors->children() as $error) {
            $errorsAsArray[(string) $error->ErrorID] = (string) $error->ErrorMessage;
        }
        return $errorsAsArray;
    }
}
