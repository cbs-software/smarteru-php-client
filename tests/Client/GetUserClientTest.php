<?php

/**
 * Contains Tests\CBS\SmarterU\GetUserClientTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/25
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\GroupPermissions;
use CBS\SmarterU\DataTypes\Permission;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\GetUserQuery;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Queries\Tags\MatchTag;
use CBS\SmarterU\Client;
use DateTime;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Client::getUser().
 */

class GetUserClientTest extends TestCase {
    /**
     * A User to use for testing purposes.
     */
    protected User $user1;

    /**
     * Set up the test Users.
     */
    public function setUp(): void {
        $permission1 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_USERS');
        $permission2 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_GROUP');
        $groupPermissions = (new GroupPermissions())
            ->setGroupName('Group1')
            ->setPermissions([$permission1, $permission2]);
        $groupPermission2 = (new GroupPermissions())
            ->setGroupName('Group2')
            ->setPermissions([$permission1, $permission2]);
        
        $this->user1 = (new User())
            ->setId('1')
            ->setEmail('phpunit@test.com')
            ->setEmployeeId('1')
            ->setGivenName('PHP')
            ->setSurname('Unit')
            ->setPassword('password')
            ->setTimezone('EST')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit@test1.com')
            ->setAuthenticationType('External')
            ->setSupervisors(['supervisor1', 'supervisor2'])
            ->setOrganization('organization')
            ->setTeams(['team1', 'team2'])
            ->setLanguage('English')
            ->setStatus('Active')
            ->setTitle('Title')
            ->setDivision('division')
            ->setAllowFeedback(true)
            ->setPhonePrimary('555-555-5555')
            ->setPhoneAlternate('555-555-1234')
            ->setPhoneMobile('555-555-4321')
            ->setFax('555-555-5432')
            ->setWebsite('https://localhost')
            ->setAddress1('123 Main St')
            ->setAddress2('Apt. 1')
            ->setCity('Anytown')
            ->setProvince('Pennsylvania')
            ->setCountry('United States')
            ->setPostalCode('12345')
            ->setSendMailTo('Personal')
            ->setReceiveNotifications(true)
            ->setHomeGroup('My Home Group')
            ->setCreatedDate(new DateTime('2022-07-29'))
            ->setModifiedDate(new DateTime('2022-07-30'))
            ->setGroups([$groupPermissions, $groupPermission2]);
    }

    /**
     * Test that getUser() passes the correct input into the SmarterU API when
     * all required information is present and the query uses the ID as the
     * user identifier.
     */
    public function testGetUserProducesCorrectInputForUserID() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId($this->user1->getId());

        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        /**
         * The response needs a body because getUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by getUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $user = $info->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild('Language', $this->user1->getLanguage());
        $user->addChild(
            'AllowFeedback',
            (string) $this->user1->getAllowFeedback()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild(
            'AuthenticationType',
            $this->user1->getAuthenticationType()
        );
        $user->addChild('Timezone', $this->user1->getTimezone());
        $user->addChild('AlternateEmail', $this->user1->getAlternateEmail());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('Organization', $this->user1->getOrganization());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('Supervisors');
        $user->addChild('PhonePrimary', $this->user1->getPhonePrimary());
        $user->addChild('PhoneAlternate', $this->user1->getPhoneAlternate());
        $user->addChild('PhoneMobile', $this->user1->getPhoneMobile());
        $user->addChild('SendMailTo', $this->user1->getSendMailTo());
        $user->addChild('SendEmailTo', $this->user1->getSendEmailTo());
        $user->addChild('Fax', $this->user1->getFax());
        $user->addChild('Address1', $this->user1->getAddress1());
        $user->addChild('Address2', $this->user1->getAddress2());
        $user->addChild('City', $this->user1->getCity());
        $user->addChild('PostalCode', $this->user1->getPostalCode());
        $user->addChild('Province', $this->user1->getProvince());
        $user->addChild('Country', $this->user1->getCountry());
        $user->addChild(
            'SendWeeklyTaskReminder',
            (string) $this->user1->getLearnerNotifications()
        );
        $user->addChild(
            'SendWeeklyProgressSummary',
            (string) $this->user1->getSupervisorNotifications()
        );
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $user->addChild('Roles');
        $user->addChild('CustomFields');
        $user->addChild('Venues');
        $user->addChild('Wages');
        $user->addChild(
            'ReceiveNotifications',
            (string) $this->user1->getReceiveNotifications()
        );
        $errors = $xml->addChild('Errors');
        $error = $errors->addChild('Error');
        $error->addChild('ErrorID', 'Error 1');
        $error->addChild('ErrorMessage', 'Non-fatal Error');
        $body = $xml->asXML();

        // Set up the container to capture the request.
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
        self::assertIsString($body);

        // Make the request.
        $client->getUser($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $body = strrpos($decodedBody, 'Package=') === 0 ? substr($decodedBody, 8, null) : '';
        $packageAsXml = simplexml_load_string($body);
        
        self::assertEquals($packageAsXml->getName(), 'SmarterU');
        $elements = [];
        foreach ($packageAsXml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $packageAsXml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $packageAsXml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getUser', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        $userIdentifier = [];
        foreach ($packageAsXml->Parameters->User->children() as $identifier) {
            $userIdentifier[] = $identifier->getName();
        }
        self::assertCount(1, $userIdentifier);
        self::assertContains('ID', $userIdentifier);
        self::assertEquals(
            $query->getId(),
            $packageAsXml->Parameters->User->ID
        );
    }

    /**
     * Test that getUser() passes the correct input into the SmarterU API when
     * all required information is present and the query uses the email address
     * as the user identifier.
     */
    public function testGetUserProducesCorrectInputForEmail() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setEmail($this->user1->getEmail());

        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        /**
         * The response needs a body because getUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by getUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $user = $info->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild('Language', $this->user1->getLanguage());
        $user->addChild(
            'AllowFeedback',
            (string) $this->user1->getAllowFeedback()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild(
            'AuthenticationType',
            $this->user1->getAuthenticationType()
        );
        $user->addChild('Timezone', $this->user1->getTimezone());
        $user->addChild('AlternateEmail', $this->user1->getAlternateEmail());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('Organization', $this->user1->getOrganization());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('Supervisors');
        $user->addChild('PhonePrimary', $this->user1->getPhonePrimary());
        $user->addChild('PhoneAlternate', $this->user1->getPhoneAlternate());
        $user->addChild('PhoneMobile', $this->user1->getPhoneMobile());
        $user->addChild('SendMailTo', $this->user1->getSendMailTo());
        $user->addChild('SendEmailTo', $this->user1->getSendEmailTo());
        $user->addChild('Fax', $this->user1->getFax());
        $user->addChild('Address1', $this->user1->getAddress1());
        $user->addChild('Address2', $this->user1->getAddress2());
        $user->addChild('City', $this->user1->getCity());
        $user->addChild('PostalCode', $this->user1->getPostalCode());
        $user->addChild('Province', $this->user1->getProvince());
        $user->addChild('Country', $this->user1->getCountry());
        $user->addChild(
            'SendWeeklyTaskReminder',
            (string) $this->user1->getLearnerNotifications()
        );
        $user->addChild(
            'SendWeeklyProgressSummary',
            (string) $this->user1->getSupervisorNotifications()
        );
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $user->addChild('Roles');
        $user->addChild('CustomFields');
        $user->addChild('Venues');
        $user->addChild('Wages');
        $user->addChild(
            'ReceiveNotifications',
            (string) $this->user1->getReceiveNotifications()
        );
        $errors = $xml->addChild('Errors');
        $error = $errors->addChild('Error');
        $error->addChild('ErrorID', 'Error 1');
        $error->addChild('ErrorMessage', 'Non-fatal Error');
        $body = $xml->asXML();

        // Set up the container to capture the request.
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
        self::assertIsString($body);

        // Make the request.
        $client->getUser($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $body = strrpos($decodedBody, 'Package=') === 0 ? substr($decodedBody, 8, null) : '';
        $packageAsXml = simplexml_load_string($body);
        
        self::assertEquals($packageAsXml->getName(), 'SmarterU');
        $elements = [];
        foreach ($packageAsXml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $packageAsXml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $packageAsXml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getUser', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        $userIdentifier = [];
        foreach ($packageAsXml->Parameters->User->children() as $identifier) {
            $userIdentifier[] = $identifier->getName();
        }
        self::assertCount(1, $userIdentifier);
        self::assertContains('Email', $userIdentifier);
        self::assertEquals(
            $query->getEmail(),
            $packageAsXml->Parameters->User->Email
        );
    }

    /**
     * Test that getUser() passes the correct input into the SmarterU API when
     * all required information is present and the query uses the employee ID
     * as the user identifier.
     */
    public function testGetUserProducesCorrectInputForEmployeeID() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setEmployeeId($this->user1->getEmployeeId());

        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        /**
         * The response needs a body because getUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by getUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $user = $info->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild('Language', $this->user1->getLanguage());
        $user->addChild(
            'AllowFeedback',
            (string) $this->user1->getAllowFeedback()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild(
            'AuthenticationType',
            $this->user1->getAuthenticationType()
        );
        $user->addChild('Timezone', $this->user1->getTimezone());
        $user->addChild('AlternateEmail', $this->user1->getAlternateEmail());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('Organization', $this->user1->getOrganization());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('Supervisors');
        $user->addChild('PhonePrimary', $this->user1->getPhonePrimary());
        $user->addChild('PhoneAlternate', $this->user1->getPhoneAlternate());
        $user->addChild('PhoneMobile', $this->user1->getPhoneMobile());
        $user->addChild('SendMailTo', $this->user1->getSendMailTo());
        $user->addChild('SendEmailTo', $this->user1->getSendEmailTo());
        $user->addChild('Fax', $this->user1->getFax());
        $user->addChild('Address1', $this->user1->getAddress1());
        $user->addChild('Address2', $this->user1->getAddress2());
        $user->addChild('City', $this->user1->getCity());
        $user->addChild('PostalCode', $this->user1->getPostalCode());
        $user->addChild('Province', $this->user1->getProvince());
        $user->addChild('Country', $this->user1->getCountry());
        $user->addChild(
            'SendWeeklyTaskReminder',
            (string) $this->user1->getLearnerNotifications()
        );
        $user->addChild(
            'SendWeeklyProgressSummary',
            (string) $this->user1->getSupervisorNotifications()
        );
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $user->addChild('Roles');
        $user->addChild('CustomFields');
        $user->addChild('Venues');
        $user->addChild('Wages');
        $user->addChild(
            'ReceiveNotifications',
            (string) $this->user1->getReceiveNotifications()
        );
        $errors = $xml->addChild('Errors');
        $error = $errors->addChild('Error');
        $error->addChild('ErrorID', 'Error 1');
        $error->addChild('ErrorMessage', 'Non-fatal Error');
        $body = $xml->asXML();

        // Set up the container to capture the request.
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
        self::assertIsString($body);

        // Make the request.
        $client->getUser($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $body = strrpos($decodedBody, 'Package=') === 0 ? substr($decodedBody, 8, null) : '';
        $packageAsXml = simplexml_load_string($body);
        
        self::assertEquals($packageAsXml->getName(), 'SmarterU');
        $elements = [];
        foreach ($packageAsXml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $packageAsXml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $packageAsXml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getUser', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        $userIdentifier = [];
        foreach ($packageAsXml->Parameters->User->children() as $identifier) {
            $userIdentifier[] = $identifier->getName();
        }
        self::assertCount(1, $userIdentifier);
        self::assertContains('EmployeeID', $userIdentifier);
        self::assertEquals(
            $query->getEmployeeId(),
            $packageAsXml->Parameters->User->EmployeeID
        );
    }

    /**
     * Test that getUser() throws an exception when the request results
     * in an HTTP error.
     */
    public function testGetUserThrowsExceptionWhenHTTPErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId($this->user1->getId());

        $response = new Response(404);

        $container = [];
        $history = Middleware::history($container);

        $mock = (new MockHandler([$response]));
            
        $handlerStack = HandlerStack::create($mock);

        $handlerStack->push($history);
            
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client->setHttpClient($httpClient);

        self::expectException(ClientException::class);
        self::expectExceptionMessage('Client error: ');
        $client->getUser($query);
    }

    /**
     * Test that getUser() throws an exception when the SmarterU API
     * returns a fatal error.
     */
    public function testGetUserThrowsExceptionWhenFatalErrorReturned() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId($this->user1->getId());

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
    
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Failed');
        $xml->addChild('Info');
        $errors = $xml->addChild('Errors');
        $error1 = $errors->addChild('Error');
        $error1->addChild('ErrorID', 'Error1');
        $error1->addChild('ErrorMessage', 'Testing');
        $error2 = $errors->addChild('Error');
        $error2->addChild('ErrorID', 'Error2');
        $error2->addChild('ErrorMessage', '123');
        $body = $xml->asXML();

        $response = new Response(200, [], $body);
    
        $container = [];
        $history = Middleware::history($container);

        $mock = (new MockHandler([$response]));
            
        $handlerStack = HandlerStack::create($mock);

        $handlerStack->push($history);
            
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client->setHttpClient($httpClient);

        self::expectException(SmarterUException::class);
        self::expectExceptionMessage(
            'SmarterU rejected the request due to the following error(s): Error1: Testing, Error2: 123'
        );
        $client->getUser($query);
    }

    /**
     * Test that getUser() returns the expected output when the SmarterU API
     * does not return any Users.
     */
    public function testGetUserReturnsNullWhenUserDoesNotExist() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId($this->user1->getId());

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Failed');
        $info = $xml->addChild('Info');
        $user = $info->addChild('User');
        $errors = $xml->addChild('Errors');
        $error = $errors->addChild('Error');
        $error->addChild('ErrorID', 'GU:03');
        $error->addChild('ErrorMessage', 'The user requested does not exist.');
        $body = $xml->asXML();
    
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
            
        // Make the request.
        $result = $client->getUser($query);
        
        self::assertNull($result);
    }

    /**
     * Test that getUser() returns the expected output when the SmarterU API
     * does not return any errors.
     */
    public function testGetUserReturnsExpectedResult() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId($this->user1->getId());
        
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $user = $info->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild('Language', $this->user1->getLanguage());
        $user->addChild(
            'AllowFeedback',
            (string) $this->user1->getAllowFeedback()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild(
            'AuthenticationType',
            $this->user1->getAuthenticationType()
        );
        $user->addChild('Timezone', $this->user1->getTimezone());
        $user->addChild('AlternateEmail', $this->user1->getAlternateEmail());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('Organization', $this->user1->getOrganization());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('Supervisors');
        $user->addChild('PhonePrimary', $this->user1->getPhonePrimary());
        $user->addChild('PhoneAlternate', $this->user1->getPhoneAlternate());
        $user->addChild('PhoneMobile', $this->user1->getPhoneMobile());
        $user->addChild('SendMailTo', $this->user1->getSendMailTo());
        $user->addChild('SendEmailTo', $this->user1->getSendEmailTo());
        $user->addChild('Fax', $this->user1->getFax());
        $user->addChild('Address1', $this->user1->getAddress1());
        $user->addChild('Address2', $this->user1->getAddress2());
        $user->addChild('City', $this->user1->getCity());
        $user->addChild('PostalCode', $this->user1->getPostalCode());
        $user->addChild('Province', $this->user1->getProvince());
        $user->addChild('Country', $this->user1->getCountry());
        $user->addChild(
            'SendWeeklyTaskReminder',
            (string) $this->user1->getLearnerNotifications()
        );
        $user->addChild(
            'SendWeeklyProgressSummary',
            (string) $this->user1->getSupervisorNotifications()
        );
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $user->addChild('Roles');
        $user->addChild('CustomFields');
        $user->addChild('Venues');
        $user->addChild('Wages');
        $user->addChild(
            'ReceiveNotifications',
            (string) $this->user1->getReceiveNotifications()
        );
        $errors = $xml->addChild('Errors');
        $body = $xml->asXML();
    
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
            
        // Make the request.
        $result = $client->getUser($query);
        
        self::assertInstanceOf(User::class, $result);
        self::assertEquals($this->user1->getId(), $result->getId());
        self::assertEquals(
            $this->user1->getEmail(),
            $result->getEmail()
        );
        self::assertEquals(
            $this->user1->getEmployeeId(),
            $result->getEmployeeId()
        );
        self::assertEquals(
            $this->user1->getCreatedDate(),
            $result->getCreatedDate()
        );
        self::assertEquals(
            $this->user1->getGivenName(),
            $result->getGivenName()
        );
        self::assertEquals(
            $this->user1->getSurname(),
            $result->getSurname()
        );
        self::assertEquals(
            $this->user1->getLanguage(),
            $result->getLanguage()
        );
        self::assertEquals(
            $this->user1->getAllowFeedback(),
            $result->getAllowFeedback()
        );
        self::assertEquals(
            $this->user1->getStatus(),
            $result->getStatus()
        );
        self::assertEquals(
            $this->user1->getAuthenticationType(),
            $result->getAuthenticationType()
        );
        self::assertEquals(
            $this->user1->getTimezone(),
            $result->getTimezone()
        );
        self::assertEquals(
            $this->user1->getAlternateEmail(),
            $result->getAlternateEmail()
        );
        self::assertEquals(
            $this->user1->getHomeGroup(),
            $result->getHomeGroup()
        );
        self::assertEquals(
            $this->user1->getOrganization(),
            $result->getOrganization()
        );
        self::assertEquals(
            $this->user1->getTitle(),
            $result->getTitle()
        );
        self::assertEquals(
            $this->user1->getDivision(),
            $result->getDivision()
        );
        self::assertEquals(
            $this->user1->getPhonePrimary(),
            $result->getPhonePrimary()
        );
        self::assertEquals(
            $this->user1->getPhoneAlternate(),
            $result->getPhoneAlternate()
        );
        self::assertEquals(
            $this->user1->getPhoneMobile(),
            $result->getPhoneMobile()
        );
        self::assertEquals(
            $this->user1->getSendMailTo(),
            $result->getSendMailTo()
        );
        self::assertEquals(
            $this->user1->getSendEmailTo(),
            $result->getSendEmailTo()
        );
        self::assertEquals(
            $this->user1->getFax(),
            $result->getFax()
        );
        self::assertEquals(
            $this->user1->getAddress1(),
            $result->getAddress1()
        );
        self::assertEquals(
            $this->user1->getAddress2(),
            $result->getAddress2()
        );
        self::assertEquals(
            $this->user1->getCity(),
            $result->getCity()
        );
        self::assertEquals(
            $this->user1->getPostalCode(),
            $result->getPostalCode()
        );
        self::assertEquals(
            $this->user1->getProvince(),
            $result->getProvince()
        );
        self::assertEquals(
            $this->user1->getCountry(),
            $result->getCountry()
        );
        self::assertEquals(
            $this->user1->getLearnerNotifications(),
            $result->getLearnerNotifications()
        );
        self::assertEquals(
            $this->user1->getSupervisorNotifications(),
            $result->getSupervisorNotifications()
        );
        self::assertEquals(
            $this->user1->getTeams(),
            $result->getTeams()
        );
        self::assertEquals(
            $this->user1->getReceiveNotifications(),
            $result->getReceiveNotifications()
        );
    }
}
