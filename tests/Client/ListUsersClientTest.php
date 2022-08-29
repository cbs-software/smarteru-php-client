<?php

/**
 * Contains Tests\CBS\SmarterU\ListUsersClientTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/24
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\GroupPermissions;
use CBS\SmarterU\DataTypes\Permission;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\ListUsersQuery;
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
 * Tests CBS\SmarterU\Client::listUsers().
 */

class ListUsersClientTest extends TestCase {
    /**
     * A User to use for testing purposes.
     */
    protected User $user1;

    /**
     * A second User to use for testing purposes.
     */
    protected User $user2;

    /**
     * An inactive User to use for testing purposes.
     */
    protected User $user3;

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
            ->setGroups([$groupPermissions, $groupPermission2]);

        $this->user2 = (new User())
            ->setId('2')
            ->setEmail('phpunit2@test.com')
            ->setEmployeeId('2')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setPassword('password')
            ->setTimezone('EST')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit2@test1.com')
            ->setAuthenticationType('External')
            ->setSupervisors(['supervisor1', 'supervisor2'])
            ->setOrganization('organization')
            ->setTeams(['team1', 'team2'])
            ->setLanguage('English')
            ->setStatus('Active')
            ->setTitle('Title')
            ->setDivision('division')
            ->setAllowFeedback(true)
            ->setPhonePrimary('555-555-5556')
            ->setPhoneAlternate('555-555-1235')
            ->setPhoneMobile('555-555-4320')
            ->setFax('555-555-5431')
            ->setWebsite('https://localhost')
            ->setAddress1('124 Main St')
            ->setAddress2('Apt. 1')
            ->setCity('Anytown')
            ->setProvince('Pennsylvania')
            ->setCountry('United States')
            ->setPostalCode('12345')
            ->setSendMailTo('Personal')
            ->setReceiveNotifications(true)
            ->setHomeGroup('My Home Group')
            ->setGroups([$groupPermissions, $groupPermission2]);

        $this->user3 = (new User())
            ->setId('3')
            ->setEmail('phpunit3@test.com')
            ->setEmployeeId('3')
            ->setGivenName('Inactive')
            ->setSurname('User')
            ->setPassword('password')
            ->setTimezone('EST')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit3@test1.com')
            ->setAuthenticationType('External')
            ->setSupervisors(['supervisor1', 'supervisor2'])
            ->setOrganization('organization')
            ->setTeams(['team1', 'team2'])
            ->setLanguage('English')
            ->setStatus('Inactive')
            ->setTitle('Title')
            ->setDivision('division')
            ->setAllowFeedback(true)
            ->setPhonePrimary('555-555-5551')
            ->setPhoneAlternate('555-555-1232')
            ->setPhoneMobile('555-555-4323')
            ->setFax('555-555-5434')
            ->setWebsite('https://localhost')
            ->setAddress1('125 Main St')
            ->setAddress2('Apt. 1')
            ->setCity('Anytown')
            ->setProvince('Pennsylvania')
            ->setCountry('United States')
            ->setPostalCode('12345')
            ->setSendMailTo('Personal')
            ->setReceiveNotifications(true)
            ->setHomeGroup('My Home Group')
            ->setGroups([$groupPermissions, $groupPermission2]);
    }

    /**
     * Test that listUsers() sends the correct information when making an API
     * call.
     */
    public function testListUsersMakesCorrectAPICall() {
        $sortField = 'NAME';
        $sortOrder = 'ASC';
        $email = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue($this->user1->getEmail());
        $employeeId = (new MatchTag())
            ->setMatchType('CONTAINS')
            ->setValue($this->user1->getEmployeeId());
        $name = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue(
                $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
            );
        $now = new DateTime();
        $time1 = new DateTime('2022-07-25');
        $time2 = new DateTime('2022-07-26');
        $time3 = new DateTime('2022-07-28');
        $createdDate = (new DateRangeTag())
            ->setDateFrom($time2)
            ->setDateTo($now);
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom($time1)
            ->setDateTo($time3);
        $homeGroup = 'My Home Group';
        $groupName = 'My Group';
        $status = 'ACTIVE';
        $teams = ['team1', 'team2'];

        $accountApi = 'account';
        $userApi = 'user';

        $client = new Client($accountApi, $userApi);

        $query = (new ListUsersQuery())
            ->setSortField($sortField)
            ->setSortOrder($sortOrder)
            ->setEmail($email)
            ->setEmployeeId($employeeId)
            ->setName($name)
            ->setHomeGroup($homeGroup)
            ->setgroupName($groupName)
            ->setUserStatus($status)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setTeams($teams);

        $createdDate = '2022-07-20';
        $modifiedDate = '2022-07-29';

        /**
         * The response needs a body because listUsers() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by listUsers() is correct. The
         * processing of the response will be tested over the next few tests.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $users = $info->addChild('Users');
        $user = $info->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild(
            'Name',
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $info->addChild('TotalRecords', '1');
        $errors = $xml->addChild('Errors');
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

        // Make the request.
        $client->listUsers($query);

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
        self::assertEquals('listUsers', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        $userInfo = [];
        foreach ($packageAsXml->Parameters->User->children() as $info) {
            $userInfo[] = $info->getName();
        }
        self::assertCount(5, $userInfo);
        self::assertContains('Page', $userInfo);
        self::assertEquals(
            (string) $query->getPage(),
            $packageAsXml->Parameters->User->Page
        );
        self::assertContains('PageSize', $userInfo);
        self::assertEquals(
            (string) $query->getPageSize(),
            $packageAsXml->Parameters->User->PageSize
        );
        self::assertContains('SortField', $userInfo);
        self::assertEquals(
            $query->getSortField(),
            $packageAsXml->Parameters->User->SortField
        );
        self::assertContains('SortOrder', $userInfo);
        self::assertEquals(
            $query->getSortOrder(),
            $packageAsXml->Parameters->User->SortOrder
        );
        self::assertContains('Filters', $userInfo);
        $filters = [];
        foreach ($packageAsXml->Parameters->User->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(7, $filters);
        self::assertContains('Users', $filters);
        $users = [];
        foreach ($packageAsXml->Parameters->User->Filters->Users->children() as $user) {
            $users[] = $user->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('UserIdentifier', $users);
        $userIdentifiers = [];
        foreach ($packageAsXml->Parameters->User->Filters->Users->UserIdentifier->children() as $identifier) {
            $userIdentifiers[] = $identifier->getName();
        }
        self::assertCount(3, $userIdentifiers);
        self::assertContains('Email', $userIdentifiers);
        $emailTag = [];
        foreach ($packageAsXml->Parameters->User->Filters->Users->UserIdentifier->Email->children() as $tag) {
            $emailTag[] = $tag->getName();
        }
        self::assertCount(2, $emailTag);
        self::assertContains('MatchType', $emailTag);
        self::assertEquals(
            $email->getMatchType(),
            $packageAsXml->Parameters->User->Filters->Users->UserIdentifier->Email->MatchType
        );
        self::assertContains('Value', $emailTag);
        self::assertEquals(
            $email->getValue(),
            $packageAsXml->Parameters->User->Filters->Users->UserIdentifier->Email->Value
        );
        self::assertContains('EmployeeID', $userIdentifiers);
        $employeeIdTag = [];
        foreach ($packageAsXml->Parameters->User->Filters->Users->UserIdentifier->EmployeeID->children() as $tag) {
            $employeeIdTag[] = $tag->getName();
        }
        self::assertCount(2, $employeeIdTag);
        self::assertContains('MatchType', $employeeIdTag);
        self::assertEquals(
            $employeeId->getMatchType(),
            $packageAsXml->Parameters->User->Filters->Users->UserIdentifier->EmployeeID->MatchType
        );
        self::assertContains('Value', $employeeIdTag);
        self::assertEquals(
            $employeeId->getValue(),
            $packageAsXml->Parameters->User->Filters->Users->UserIdentifier->EmployeeID->Value
        );
        self::assertContains('Name', $userIdentifiers);
        $nameTag = [];
        foreach ($packageAsXml->Parameters->User->Filters->Users->UserIdentifier->Name->children() as $tag) {
            $nameTag[] = $tag->getName();
        }
        self::assertCount(2, $nameTag);
        self::assertContains('MatchType', $nameTag);
        self::assertEquals(
            $name->getMatchType(),
            $packageAsXml->Parameters->User->Filters->Users->UserIdentifier->Name->MatchType
        );
        self::assertContains('Value', $nameTag);
        self::assertEquals(
            $name->getValue(),
            $packageAsXml->Parameters->User->Filters->Users->UserIdentifier->Name->Value
        );
        self::assertContains('HomeGroup', $filters);
        self::assertEquals(
            $homeGroup,
            $packageAsXml->Parameters->User->Filters->HomeGroup
        );
        self::assertContains('GroupName', $filters);
        self::assertEquals(
            $groupName,
            $packageAsXml->Parameters->User->Filters->GroupName
        );
        self::assertContains('UserStatus', $filters);
        self::assertEquals(
            $status,
            $packageAsXml->Parameters->User->Filters->UserStatus
        );
        self::assertContains('CreatedDate', $filters);
        $createdDateTag = [];
        foreach ($packageAsXml->Parameters->User->Filters->CreatedDate->children() as $tag) {
            $createdDateTag[] = $tag->getName();
        }
        self::assertCount(2, $createdDateTag);
        self::assertContains('CreatedDateFrom', $createdDateTag);
        self::assertEquals(
            $time2->format('d/m/Y'),
            $packageAsXml->Parameters->User->Filters->CreatedDate->CreatedDateFrom
        );
        self::assertContains('CreatedDateTo', $createdDateTag);
        self::assertEquals(
            $now->format('d/m/Y'),
            $packageAsXml->Parameters->User->Filters->CreatedDate->CreatedDateTo
        );
        self::assertContains('ModifiedDate', $filters);
        $modifiedDateTag = [];
        foreach ($packageAsXml->Parameters->User->Filters->ModifiedDate->children() as $tag) {
            $modifiedDateTag[] = $tag->getName();
        }
        self::assertCount(2, $modifiedDateTag);
        self::assertContains('ModifiedDateFrom', $modifiedDateTag);
        self::assertEquals(
            $time1->format('d/m/Y'),
            $packageAsXml->Parameters->User->Filters->ModifiedDate->ModifiedDateFrom
        );
        self::assertContains('ModifiedDateTo', $modifiedDateTag);
        self::assertEquals(
            $time3->format('d/m/Y'),
            $packageAsXml->Parameters->User->Filters->ModifiedDate->ModifiedDateTo
        );
        self::assertContains('Teams', $filters);
        $teams = [];
        foreach ((array) $packageAsXml->Parameters->User->Filters->Teams->children() as $team) {
            $teams[] = $team;
        }
        $teams = $teams[0];
        self::assertCount(count($this->user1->getTeams()), $teams);
        foreach ($this->user1->getTeams() as $team) {
            self::assertContains($team, $teams);
        }
    }

    /**
     * Test that listUsers() throws an exception when an HTTP error occurs
     * while attempting to make a request to the SmarterU API.
     */
    public function testListUsersThrowsExceptionWhenHTTPErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new ListUsersQuery())
            ->setUserStatus('Active');

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
        $client->listUsers($query);
    }

    /**
     * Test that listUsers() throws an exception when the SmarterU API
     * returns a fatal error.
     */
    public function testListUsersThrowsExceptionWhenFatalErrorReturned() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new ListUsersQuery())
            ->setUserStatus('Active');

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
            'SmarterU rejected the request due to the following errors: Error1: Testing, Error2: 123'
        );
        $client->listUsers($query);
    }

    /**
     * Test that listUsers() returns the expected output when the SmarterU API
     * returns a non-fatal error.
     */
    public function testListUsersHandlesNonFatalError() {
        $sortField = 'NAME';
        $sortOrder = 'ASC';
        $email = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue($this->user1->getEmail());
        $employeeId = (new MatchTag())
            ->setMatchType('CONTAINS')
            ->setValue($this->user1->getEmployeeId());
        $name = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue(
                $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
            );
        $now = new DateTime();
        $time1 = new DateTime('2022-07-25');
        $time2 = new DateTime('2022-07-26');
        $time3 = new DateTime('2022-07-28');
        $createdDate = (new DateRangeTag())
            ->setDateFrom($time2)
            ->setDateTo($now);
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom($time1)
            ->setDateTo($time3);
        $groupName = 'My Group';
        $status = 'ACTIVE';
        $teams = ['team1', 'team2'];

        $accountApi = 'account';
        $userApi = 'user';

        $client = new Client($accountApi, $userApi);

        $query = (new ListUsersQuery())
            ->setSortField($sortField)
            ->setSortOrder($sortOrder)
            ->setEmail($email)
            ->setEmployeeId($employeeId)
            ->setName($name)
            ->setgroupName($groupName)
            ->setUserStatus($status)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setTeams($teams);

        $createdDate = '2022-07-20';
        $modifiedDate = '2022-07-29';

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $users = $info->addChild('Users');
        $user = $users->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild(
            'Name',
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $info->addChild('TotalRecords', '1');
        $errors = $xml->addChild('Errors');
        $error = $errors->addChild('Error');
        $error->addChild('ErrorID', 'Error 1');
        $error->addChild('ErrorMessage', 'Non-fatal Error');
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
        $result = $client->listUsers($query);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];
        
        self::assertIsArray($response);
        $user = $response[0];
        self::assertIsArray($user);
        self::assertArrayHasKey('ID', $user);
        self::assertEquals($this->user1->getId(), $user['ID']);
        self::assertArrayHasKey('Email', $user);
        self::assertEquals($this->user1->getEmail(), $user['Email']);
        self::assertArrayHasKey('EmployeeID', $user);
        self::assertEquals($this->user1->getEmployeeID(), $user['EmployeeID']);
        self::assertArrayHasKey('GivenName', $user);
        self::assertEquals($this->user1->getGivenName(), $user['GivenName']);
        self::assertArrayHasKey('Surname', $user);
        self::assertEquals($this->user1->getSurname(), $user['Surname']);
        self::assertArrayHasKey('Name', $user);
        self::assertEquals(
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname(),
            $user['Name']
        );
        self::assertArrayHasKey('Status', $user);
        self::assertEquals($this->user1->getStatus(), $user['Status']);
        self::assertArrayHasKey('Title', $user);
        self::assertEquals($this->user1->getTitle(), $user['Title']);
        self::assertArrayHasKey('Division', $user);
        self::assertEquals($this->user1->getDivision(), $user['Division']);
        self::assertArrayHasKey('HomeGroup', $user);
        self::assertEquals($this->user1->getHomeGroup(), $user['HomeGroup']);
        self::assertArrayHasKey('CreatedDate', $user);
        self::assertEquals($createdDate, $user['CreatedDate']);
        self::assertArrayHasKey('ModifiedDate', $user);
        self::assertEquals($modifiedDate, $user['ModifiedDate']);
        self::assertArrayHasKey('Teams', $user);
        self::assertCount(count($this->user1->getTeams()), $user['Teams']);
        foreach ($this->user1->getTeams() as $team) {
            self::assertContains($team, $user['Teams']);
        }

        self::assertIsArray($errors);
        self::assertCount(1, $errors);
        self::assertArrayHasKey('Error 1', $errors);
        self::assertEquals('Non-fatal Error', $errors['Error 1']);
    }

    /**
     * Test that listUsers() returns the expected output when the SmarterU API
     * does not return any errors and the query only matches 1 User.
     */
    public function testListUsersReturnsExpectedResultSingleUser() {
        $sortField = 'NAME';
        $sortOrder = 'ASC';
        $email = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue($this->user1->getEmail());
        $employeeId = (new MatchTag())
            ->setMatchType('CONTAINS')
            ->setValue($this->user1->getEmployeeId());
        $name = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue(
                $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
            );
        $now = new DateTime();
        $time1 = new DateTime('2022-07-25');
        $time2 = new DateTime('2022-07-26');
        $time3 = new DateTime('2022-07-28');
        $createdDate = (new DateRangeTag())
            ->setDateFrom($time2)
            ->setDateTo($now);
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom($time1)
            ->setDateTo($time3);
        $groupName = 'My Group';
        $status = 'ACTIVE';
        $teams = ['team1', 'team2'];

        $accountApi = 'account';
        $userApi = 'user';

        $client = new Client($accountApi, $userApi);

        $query = (new ListUsersQuery())
            ->setSortField($sortField)
            ->setSortOrder($sortOrder)
            ->setEmail($email)
            ->setEmployeeId($employeeId)
            ->setName($name)
            ->setgroupName($groupName)
            ->setUserStatus($status)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setTeams($teams);

        $createdDate = '2022-07-20';
        $modifiedDate = '2022-07-29';

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $users = $info->addChild('Users');
        $user = $users->addChild('User');
        $user->addChild('ID', $this->user1->getId());
        $user->addChild('Email', $this->user1->getEmail());
        $user->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user->addChild('GivenName', $this->user1->getGivenName());
        $user->addChild('Surname', $this->user1->getSurname());
        $user->addChild(
            'Name',
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
        );
        $user->addChild('Status', $this->user1->getStatus());
        $user->addChild('Title', $this->user1->getTitle());
        $user->addChild('Division', $this->user1->getDivision());
        $user->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user->addChild('CreatedDate', $createdDate);
        $user->addChild('ModifiedDate', $modifiedDate);
        $teams = $user->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams->addChild('Team', $team);
        }
        $info->addChild('TotalRecords', '1');
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
        $result = $client->listUsers($query);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];
        
        self::assertIsArray($response);
        self::assertCount(1, $response);
        $user = $response[0];
        self::assertIsArray($user);
        self::assertArrayHasKey('ID', $user);
        self::assertEquals($this->user1->getId(), $user['ID']);
        self::assertArrayHasKey('Email', $user);
        self::assertEquals($this->user1->getEmail(), $user['Email']);
        self::assertArrayHasKey('EmployeeID', $user);
        self::assertEquals($this->user1->getEmployeeID(), $user['EmployeeID']);
        self::assertArrayHasKey('GivenName', $user);
        self::assertEquals($this->user1->getGivenName(), $user['GivenName']);
        self::assertArrayHasKey('Surname', $user);
        self::assertEquals($this->user1->getSurname(), $user['Surname']);
        self::assertArrayHasKey('Name', $user);
        self::assertEquals(
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname(),
            $user['Name']
        );
        self::assertArrayHasKey('Status', $user);
        self::assertEquals($this->user1->getStatus(), $user['Status']);
        self::assertArrayHasKey('Title', $user);
        self::assertEquals($this->user1->getTitle(), $user['Title']);
        self::assertArrayHasKey('Division', $user);
        self::assertEquals($this->user1->getDivision(), $user['Division']);
        self::assertArrayHasKey('HomeGroup', $user);
        self::assertEquals($this->user1->getHomeGroup(), $user['HomeGroup']);
        self::assertArrayHasKey('CreatedDate', $user);
        self::assertEquals($createdDate, $user['CreatedDate']);
        self::assertArrayHasKey('ModifiedDate', $user);
        self::assertEquals($modifiedDate, $user['ModifiedDate']);
        self::assertArrayHasKey('Teams', $user);
        self::assertCount(count($this->user1->getTeams()), $user['Teams']);
        foreach ($this->user1->getTeams() as $team) {
            self::assertContains($team, $user['Teams']);
        }

        self::assertIsArray($errors);
        self::assertCount(0, $errors);
    }

    /**
     * Test that listUsers returns the expected output when the SmarterU API
     * does not return any errors and the query matches multiple Users.
     */
    public function testListUsersReturnsExpectedResultMultipleUsers() {
        $sortField = 'NAME';
        $sortOrder = 'ASC';
        $email = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue($this->user1->getEmail());
        $employeeId = (new MatchTag())
            ->setMatchType('CONTAINS')
            ->setValue($this->user1->getEmployeeId());
        $name = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue(
                $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
            );
        $now = new DateTime();
        $time1 = new DateTime('2022-07-25');
        $time2 = new DateTime('2022-07-26');
        $time3 = new DateTime('2022-07-28');
        $createdDate = (new DateRangeTag())
            ->setDateFrom($time2)
            ->setDateTo($now);
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom($time1)
            ->setDateTo($time3);
        $groupName = 'My Group';
        $status = 'ACTIVE';
        $teams = ['team1', 'team2'];

        $accountApi = 'account';
        $userApi = 'user';

        $client = new Client($accountApi, $userApi);

        $query = (new ListUsersQuery())
            ->setSortField($sortField)
            ->setSortOrder($sortOrder)
            ->setEmail($email)
            ->setEmployeeId($employeeId)
            ->setName($name)
            ->setgroupName($groupName)
            ->setUserStatus($status)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setTeams($teams);

        $createdDate = '2022-07-20';
        $modifiedDate = '2022-07-29';

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $users = $info->addChild('Users');
        $user1 = $users->addChild('User');
        $user1->addChild('ID', $this->user1->getId());
        $user1->addChild('Email', $this->user1->getEmail());
        $user1->addChild('EmployeeID', $this->user1->getEmployeeId());
        $user1->addChild('GivenName', $this->user1->getGivenName());
        $user1->addChild('Surname', $this->user1->getSurname());
        $user1->addChild(
            'Name',
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname()
        );
        $user1->addChild('Status', $this->user1->getStatus());
        $user1->addChild('Title', $this->user1->getTitle());
        $user1->addChild('Division', $this->user1->getDivision());
        $user1->addChild('HomeGroup', $this->user1->getHomeGroup());
        $user1->addChild('CreatedDate', $createdDate);
        $user1->addChild('ModifiedDate', $modifiedDate);
        $teams1 = $user1->addChild('Teams');
        foreach ($this->user1->getTeams() as $team) {
            $teams1->addChild('Team', $team);
        }
        $user2 = $users->addChild('User');
        $user2->addChild('ID', $this->user2->getId());
        $user2->addChild('Email', $this->user2->getEmail());
        $user2->addChild('EmployeeID', $this->user2->getEmployeeId());
        $user2->addChild('GivenName', $this->user2->getGivenName());
        $user2->addChild('Surname', $this->user2->getSurname());
        $user2->addChild(
            'Name',
            $this->user2->getGivenName() . ' ' . $this->user2->getSurname()
        );
        $user2->addChild('Status', $this->user2->getStatus());
        $user2->addChild('Title', $this->user2->getTitle());
        $user2->addChild('Division', $this->user2->getDivision());
        $user2->addChild('HomeGroup', $this->user2->getHomeGroup());
        $user2->addChild('CreatedDate', $createdDate);
        $user2->addChild('ModifiedDate', $modifiedDate);
        $teams2 = $user2->addChild('Teams');
        foreach ($this->user2->getTeams() as $team) {
            $teams2->addChild('Team', $team);
        }
        $user3 = $users->addChild('User');
        $user3->addChild('ID', $this->user3->getId());
        $user3->addChild('Email', $this->user3->getEmail());
        $user3->addChild('EmployeeID', $this->user3->getEmployeeId());
        $user3->addChild('GivenName', $this->user3->getGivenName());
        $user3->addChild('Surname', $this->user3->getSurname());
        $user3->addChild(
            'Name',
            $this->user3->getGivenName() . ' ' . $this->user3->getSurname()
        );
        $user3->addChild('Status', $this->user3->getStatus());
        $user3->addChild('Title', $this->user3->getTitle());
        $user3->addChild('Division', $this->user3->getDivision());
        $user3->addChild('HomeGroup', $this->user3->getHomeGroup());
        $user3->addChild('CreatedDate', $createdDate);
        $user3->addChild('ModifiedDate', $modifiedDate);
        $teams3 = $user3->addChild('Teams');
        foreach ($this->user3->getTeams() as $team) {
            $teams3->addChild('Team', $team);
        }
        $info->addChild('TotalRecords', '3');
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
        $result = $client->listUsers($query);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];
        
        self::assertIsArray($response);
        self::assertCount(3, $response);
        $user1 = $response[0];
        self::assertIsArray($user1);
        self::assertArrayHasKey('ID', $user1);
        self::assertEquals($this->user1->getId(), $user1['ID']);
        self::assertArrayHasKey('Email', $user1);
        self::assertEquals($this->user1->getEmail(), $user1['Email']);
        self::assertArrayHasKey('EmployeeID', $user1);
        self::assertEquals(
            $this->user1->getEmployeeID(),
            $user1['EmployeeID']
        );
        self::assertArrayHasKey('GivenName', $user1);
        self::assertEquals($this->user1->getGivenName(), $user1['GivenName']);
        self::assertArrayHasKey('Surname', $user1);
        self::assertEquals($this->user1->getSurname(), $user1['Surname']);
        self::assertArrayHasKey('Name', $user1);
        self::assertEquals(
            $this->user1->getGivenName() . ' ' . $this->user1->getSurname(),
            $user1['Name']
        );
        self::assertArrayHasKey('Status', $user1);
        self::assertEquals($this->user1->getStatus(), $user1['Status']);
        self::assertArrayHasKey('Title', $user1);
        self::assertEquals($this->user1->getTitle(), $user1['Title']);
        self::assertArrayHasKey('Division', $user1);
        self::assertEquals($this->user1->getDivision(), $user1['Division']);
        self::assertArrayHasKey('HomeGroup', $user1);
        self::assertEquals($this->user1->getHomeGroup(), $user1['HomeGroup']);
        self::assertArrayHasKey('CreatedDate', $user1);
        self::assertEquals($createdDate, $user1['CreatedDate']);
        self::assertArrayHasKey('ModifiedDate', $user1);
        self::assertEquals($modifiedDate, $user1['ModifiedDate']);
        self::assertArrayHasKey('Teams', $user1);
        self::assertCount(count($this->user1->getTeams()), $user1['Teams']);
        foreach ($this->user1->getTeams() as $team) {
            self::assertContains($team, $user1['Teams']);
        }

        $user2 = $response[1];
        self::assertIsArray($user2);
        self::assertArrayHasKey('ID', $user2);
        self::assertEquals($this->user2->getId(), $user2['ID']);
        self::assertArrayHasKey('Email', $user2);
        self::assertEquals($this->user2->getEmail(), $user2['Email']);
        self::assertArrayHasKey('EmployeeID', $user2);
        self::assertEquals(
            $this->user2->getEmployeeID(),
            $user2['EmployeeID']
        );
        self::assertArrayHasKey('GivenName', $user2);
        self::assertEquals($this->user2->getGivenName(), $user2['GivenName']);
        self::assertArrayHasKey('Surname', $user2);
        self::assertEquals($this->user2->getSurname(), $user2['Surname']);
        self::assertArrayHasKey('Name', $user2);
        self::assertEquals(
            $this->user2->getGivenName() . ' ' . $this->user2->getSurname(),
            $user2['Name']
        );
        self::assertArrayHasKey('Status', $user2);
        self::assertEquals($this->user1->getStatus(), $user2['Status']);
        self::assertArrayHasKey('Title', $user2);
        self::assertEquals($this->user1->getTitle(), $user2['Title']);
        self::assertArrayHasKey('Division', $user2);
        self::assertEquals($this->user1->getDivision(), $user2['Division']);
        self::assertArrayHasKey('HomeGroup', $user2);
        self::assertEquals($this->user1->getHomeGroup(), $user2['HomeGroup']);
        self::assertArrayHasKey('CreatedDate', $user2);
        self::assertEquals($createdDate, $user2['CreatedDate']);
        self::assertArrayHasKey('ModifiedDate', $user2);
        self::assertEquals($modifiedDate, $user2['ModifiedDate']);
        self::assertArrayHasKey('Teams', $user2);
        self::assertCount(count($this->user2->getTeams()), $user2['Teams']);
        foreach ($this->user2->getTeams() as $team) {
            self::assertContains($team, $user2['Teams']);
        }

        $user3 = $response[2];
        self::assertIsArray($user3);
        self::assertArrayHasKey('ID', $user3);
        self::assertEquals($this->user3->getId(), $user3['ID']);
        self::assertArrayHasKey('Email', $user3);
        self::assertEquals($this->user3->getEmail(), $user3['Email']);
        self::assertArrayHasKey('EmployeeID', $user3);
        self::assertEquals(
            $this->user3->getEmployeeID(),
            $user3['EmployeeID']
        );
        self::assertArrayHasKey('GivenName', $user3);
        self::assertEquals($this->user3->getGivenName(), $user3['GivenName']);
        self::assertArrayHasKey('Surname', $user3);
        self::assertEquals($this->user3->getSurname(), $user3['Surname']);
        self::assertArrayHasKey('Name', $user3);
        self::assertEquals(
            $this->user3->getGivenName() . ' ' . $this->user3->getSurname(),
            $user3['Name']
        );
        self::assertArrayHasKey('Status', $user3);
        self::assertEquals($this->user3->getStatus(), $user3['Status']);
        self::assertArrayHasKey('Title', $user3);
        self::assertEquals($this->user3->getTitle(), $user3['Title']);
        self::assertArrayHasKey('Division', $user3);
        self::assertEquals($this->user3->getDivision(), $user3['Division']);
        self::assertArrayHasKey('HomeGroup', $user3);
        self::assertEquals($this->user3->getHomeGroup(), $user3['HomeGroup']);
        self::assertArrayHasKey('CreatedDate', $user3);
        self::assertEquals($createdDate, $user3['CreatedDate']);
        self::assertArrayHasKey('ModifiedDate', $user3);
        self::assertEquals($modifiedDate, $user3['ModifiedDate']);
        self::assertArrayHasKey('Teams', $user3);
        self::assertCount(count($this->user3->getTeams()), $user3['Teams']);
        foreach ($this->user3->getTeams() as $team) {
            self::assertContains($team, $user3['Teams']);
        }

        self::assertIsArray($errors);
        self::assertCount(0, $errors);
    }
}
