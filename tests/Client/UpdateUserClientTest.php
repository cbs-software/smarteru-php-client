<?php

/**
 * Contains Tests\CBS\SmarterU\UpdateUserClientTest.php
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
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Queries\Tags\MatchTag;
use CBS\SmarterU\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Client::updateUser().
 */

class UpdateUserClientTest extends TestCase {
    /**
     * A User to use for testing purposes.
     */
    protected User $user1;

    /**
     * Set up the test User.
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
    }

    /**
     * Test that updateUser() passes the correct information into the API
     * when making the request if all required and optional information is
     * present.
     */
    public function testUpdateUserMakesCorrectAPICallWithAllInfo() {
        $accountApi = 'account';
        $userApi = 'user';
        $permission1 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_USERS');
        $permission2 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_GROUP');
        $groupPermissions = (new GroupPermissions())
            ->setGroupName('Group1')
            ->setAction('Add')
            ->setPermissions([$permission1, $permission2]);
        $groupPermission2 = (new GroupPermissions())
            ->setGroupName('Group2')
            ->setAction('Remove');
        $user = (new User())
            ->setId('4')
            ->setEmail('phpunit4@test.com')
            ->setEmployeeId('4')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setTimezone('EST')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit4@test1.com')
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
        $client = new Client($accountApi, $userApi);

        /**
         * The response needs a body because updateUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by updateUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', 'test@test.com');
        $info->addChild('EmployeeID', '1');
        $xml->addChild('Errors');
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
        $client->updateUser($user);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $body = strrpos($decodedBody, 'Package=') === 0 ? substr($decodedBody, 8, null) : '';
        $packageAsXml = simplexml_load_string($body);

        // Ensure that the package begins with a <SmarterU> tag and has the
        // correct children.
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
        self::assertEquals('updateUser', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        $userInfo = [];
        foreach ($packageAsXml->Parameters->User->children() as $userTag) {
            $userInfo[] = $userTag->getName();
        }
        self::assertCount(6, $userInfo);
        self::assertContains('Identifier', $userInfo);
        self::assertContains('Info', $userInfo);
        self::assertContains('Profile', $userInfo);
        self::assertContains('Groups', $userInfo);
        self::assertContains('Venues', $userInfo);
        self::assertContains('Wages', $userInfo);

        $identifier = [];
        foreach ($packageAsXml->Parameters->User->Identifier->children() as $id) {
            $identifier[] = $id->getName();
        }
        self::assertCount(1, $identifier);
        self::assertContains('Email', $identifier);
        self::assertEquals(
            $user->getEmail(),
            $packageAsXml->Parameters->User->Identifier->Email
        );

        // Ensure that the <Info> tag has the correct children.
        $infoTag = [];
        foreach ($packageAsXml->Parameters->User->Info->children() as $info) {
            $infoTag[] = $info->getName();
        }
        self::assertCount(8, $infoTag);
        self::assertContains('GivenName', $infoTag);
        self::assertEquals(
            $user->getGivenName(),
            $packageAsXml->Parameters->User->Info->GivenName
        );
        self::assertContains('Surname', $infoTag);
        self::assertEquals(
            $user->getSurname(),
            $packageAsXml->Parameters->User->Info->Surname
        );
        self::assertContains('TimeZone', $infoTag);
        self::assertEquals(
            $user->getTimezone(),
            $packageAsXml->Parameters->User->Info->TimeZone
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            (string) $user->getLearnerNotifications(),
            $packageAsXml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            (string) $user->getSupervisorNotifications(),
            $packageAsXml->Parameters->User->Info->SupervisorNotifications
        );
        self::assertContains('SendEmailTo', $infoTag);
        self::assertEquals(
            $user->getSendEmailTo(),
            $packageAsXml->Parameters->User->Info->SendEmailTo
        );
        self::assertContains('AlternateEmail', $infoTag);
        self::assertEquals(
            $user->getAlternateEmail(),
            $packageAsXml->Parameters->User->Info->AlternateEmail
        );
        self::assertContains('AuthenticationType', $infoTag);
        self::assertEquals(
            $user->getAuthenticationType(),
            $packageAsXml->Parameters->User->Info->AuthenticationType
        );

        // Ensure that the <Profile> tag has the correct children.
        $profileTag = [];
        foreach ($packageAsXml->Parameters->User->Profile->children() as $profile) {
            $profileTag[] = $profile->getName();
        }
        self::assertCount(22, $profileTag);
        self::assertContains('Supervisors', $profileTag);
        $supervisors = $packageAsXml->Parameters->User->Profile->Supervisors->asXML();
        $supervisor1 =
            '<Supervisors><Supervisor>'
            . $user->getSupervisors()[0]
            . '</Supervisor>';
        $supervisor2 =
            '<Supervisor>'
            . $user->getSupervisors()[1]
            . '</Supervisor></Supervisors>';
        self::assertStringContainsString($supervisor1, $supervisors);
        self::assertStringContainsString($supervisor2, $supervisors);
        self::assertContains('Organization', $profileTag);
        self::assertEquals(
            $user->getOrganization(),
            $packageAsXml->Parameters->User->Profile->Organization
        );
        self::assertContains('Teams', $profileTag);
        $teams = $packageAsXml->Parameters->User->Profile->Teams->asXML();
        $team1 = '<Teams><Team>' . $user->getTeams()[0] . '</Team>';
        $team2 = '<Team>' . $user->getTeams()[1] . '</Team></Teams>';
        self::assertStringContainsString($team1, $teams);
        self::assertStringContainsString($team2, $teams);
        self::assertContains('Language', $profileTag);
        self::assertEquals(
            $user->getLanguage(),
            $packageAsXml->Parameters->User->Profile->Language
        );
        self::assertContains('Status', $profileTag);
        self::assertEquals(
            $user->getStatus(),
            $packageAsXml->Parameters->User->Profile->Status
        );
        self::assertContains('Title', $profileTag);
        self::assertEquals(
            $user->getTitle(),
            $packageAsXml->Parameters->User->Profile->Title
        );
        self::assertContains('Division', $profileTag);
        self::assertEquals(
            $user->getDivision(),
            $packageAsXml->Parameters->User->Profile->Division
        );
        self::assertContains('AllowFeedback', $profileTag);
        self::assertEquals(
            (string) $user->getAllowFeedback(),
            $packageAsXml->Parameters->User->Profile->AllowFeedback
        );
        self::assertContains('PhonePrimary', $profileTag);
        self::assertEquals(
            $user->getPhonePrimary(),
            $packageAsXml->Parameters->User->Profile->PhonePrimary
        );
        self::assertContains('PhoneAlternate', $profileTag);
        self::assertEquals(
            $user->getPhoneAlternate(),
            $packageAsXml->Parameters->User->Profile->PhoneAlternate
        );
        self::assertContains('PhoneMobile', $profileTag);
        self::assertEquals(
            $user->getPhoneMobile(),
            $packageAsXml->Parameters->User->Profile->PhoneMobile
        );
        self::assertContains('Fax', $profileTag);
        self::assertEquals(
            $user->getFax(),
            $packageAsXml->Parameters->User->Profile->Fax
        );
        self::assertContains('Website', $profileTag);
        self::assertEquals(
            $user->getWebsite(),
            $packageAsXml->Parameters->User->Profile->Website
        );
        self::assertContains('Address1', $profileTag);
        self::assertEquals(
            $user->getAddress1(),
            $packageAsXml->Parameters->User->Profile->Address1
        );
        self::assertContains('Address2', $profileTag);
        self::assertEquals(
            $user->getAddress2(),
            $packageAsXml->Parameters->User->Profile->Address2
        );
        self::assertContains('City', $profileTag);
        self::assertEquals(
            $user->getCity(),
            $packageAsXml->Parameters->User->Profile->City
        );
        self::assertContains('Province', $profileTag);
        self::assertEquals(
            $user->getProvince(),
            $packageAsXml->Parameters->User->Profile->Province
        );
        self::assertContains('Country', $profileTag);
        self::assertEquals(
            $user->getCountry(),
            $packageAsXml->Parameters->User->Profile->Country
        );
        self::assertContains('PostalCode', $profileTag);
        self::assertEquals(
            $user->getPostalCode(),
            $packageAsXml->Parameters->User->Profile->PostalCode
        );
        self::assertContains('SendMailTo', $profileTag);
        self::assertEquals(
            $user->getSendMailTo(),
            $packageAsXml->Parameters->User->Profile->SendMailTo
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            (string) $user->getReceiveNotifications(),
            $packageAsXml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $user->getHomeGroup(),
            $packageAsXml->Parameters->User->Profile->HomeGroup
        );

        // Ensure that the <Groups> tag has the correct children.
        $group1 = $packageAsXml->Parameters->User->Groups->Group[0];
        $group2 = $packageAsXml->Parameters->User->Groups->Group[1];
        $group1Elements = [];
        foreach ($group1->children() as $group) {
            $group1Elements[] = $group->getName();
        }
        self::assertCount(3, $group1Elements);
        self::assertContains('GroupName', $group1Elements);
        self::assertEquals(
            $user->getGroups()[0]->getGroupName(),
            $group1->GroupName
        );
        self::assertContains('GroupAction', $group1Elements);
        self::assertEquals(
            $user->getGroups()[0]->getAction(),
            $group1->GroupAction
        );
        self::assertContains('GroupPermissions', $group1Elements);
        $permission1 = $group1->GroupPermissions->Permission[0];
        $permission2 = $group1->GroupPermissions->Permission[1];
        $permission1Tags = [];
        foreach ($permission1->children() as $tag) {
            $permission1Tags[] = $tag->getName();
        }
        self::assertCount(2, $permission1Tags);
        self::assertContains('Action', $permission1Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[0]->getAction(),
            $group1->GroupPermissions->Permission[0]->Action
        );
        self::assertContains('Code', $permission1Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[0]->getCode(),
            $group1->GroupPermissions->Permission[0]->Code
        );
        $permission2Tags = [];
        foreach ($permission2->children() as $tag) {
            $permission2Tags[] = $tag->getName();
        }
        self::assertCount(2, $permission2Tags);
        self::assertContains('Action', $permission2Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[1]->getAction(),
            $group1->GroupPermissions->Permission[1]->Action
        );
        self::assertContains('Code', $permission2Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[1]->getCode(),
            $group1->GroupPermissions->Permission[1]->Code
        );

        $group2Elements = [];
        foreach ($group2->children() as $group) {
            $group2Elements[] = $group->getName();
        }
        self::assertCount(3, $group2Elements);
        self::assertContains('GroupName', $group2Elements);
        self::assertEquals(
            $user->getGroups()[1]->getGroupName(),
            $group2->GroupName
        );
        self::assertContains('GroupAction', $group2Elements);
        self::assertEquals(
            $user->getGroups()[1]->getGroupName(),
            $group2->GroupName
        );
        self::assertContains('GroupPermissions', $group2Elements);
        $permissionTags = [];
        foreach ($group2->GroupPermissions->children() as $tag) {
            $permissionTags[] = $tag->getName();
        }
        self::assertCount(0, $permissionTags);

        // Ensure that the <Venues> and <Wages> tags are empty.
        self::assertCount(
            0,
            $packageAsXml->Parameters->User->Venues->children()
        );
        self::assertCount(
            0,
            $packageAsXml->Parameters->User->Wages->Children()
        );
    }

    /**
     * Test that updateUser() passes the correct information into the API
     * when making the request if all required but no optional information is
     * present.
     */
    public function testUpdateUserMakesCorrectAPICallWithoutOptionalInfo() {
        $accountApi = 'account';
        $userApi = 'user';
        $oldEmail = 'phpunit@test.com';
        $oldEmployeeId = '12';
        $permission1 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_USERS');
        $permission2 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_GROUP');
        $groupPermissions = (new GroupPermissions())
            ->setGroupName('Group1')
            ->setAction('Add')
            ->setPermissions([$permission1, $permission2]);
        $groupPermission2 = (new GroupPermissions())
            ->setGroupName('Group2')
            ->setAction('Remove');
        $user = (new User())
            ->setId('4')
            ->setOldEmail($oldEmail)
            ->setEmail('phpunit4@test.com')
            ->setOldEmployeeId($oldEmployeeId)
            ->setEmployeeId('4')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(false)
            ->setGroups([$groupPermissions, $groupPermission2]);
        $client = new Client($accountApi, $userApi);

        /**
         * The response needs a body because updateUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by updateUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', 'test@test.com');
        $info->addChild('EmployeeID', '1');
        $xml->addChild('Errors');
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
        $client->updateUser($user);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $body = strrpos($decodedBody, 'Package=') === 0 ? substr($decodedBody, 8, null) : '';
        $packageAsXml = simplexml_load_string($body);

        // Ensure that the package begins with a <SmarterU> tag and has the
        // correct children.
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
        self::assertEquals('updateUser', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        $userInfo = [];
        foreach ($packageAsXml->Parameters->User->children() as $userTag) {
            $userInfo[] = $userTag->getName();
        }
        self::assertCount(6, $userInfo);
        self::assertContains('Identifier', $userInfo);
        self::assertContains('Info', $userInfo);
        self::assertContains('Profile', $userInfo);
        self::assertContains('Groups', $userInfo);
        self::assertContains('Venues', $userInfo);
        self::assertContains('Wages', $userInfo);

        $identifier = [];
        foreach ($packageAsXml->Parameters->User->Identifier->children() as $id) {
            $identifier[] = $id->getName();
        }
        self::assertCount(1, $identifier);
        self::assertContains('Email', $identifier);
        self::assertEquals(
            $oldEmail,
            $packageAsXml->Parameters->User->Identifier->Email
        );

        // Ensure that the <Info> tag has the correct children.
        $infoTag = [];
        foreach ($packageAsXml->Parameters->User->Info->children() as $info) {
            $infoTag[] = $info->getName();
        }
        self::assertCount(4, $infoTag);
        self::assertContains('Email', $infoTag);
        self::assertEquals(
            $user->getEmail(),
            $packageAsXml->Parameters->User->Info->Email
        );
        self::assertContains('EmployeeID', $infoTag);
        self::assertEquals(
            $user->getEmployeeId(),
            $packageAsXml->Parameters->User->Info->EmployeeID
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            $user->getLearnerNotifications() ? '1' : '0',
            $packageAsXml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            $user->getSupervisorNotifications() ? '1' : '0',
            $packageAsXml->Parameters->User->Info->SupervisorNotifications
        );

        // Ensure that the <Profile> tag has the correct children.
        $profileTag = [];
        foreach ($packageAsXml->Parameters->User->Profile->children() as $profile) {
            $profileTag[] = $profile->getName();
        }
        self::assertCount(2, $profileTag);
        self::assertContains('Status', $profileTag);
        self::assertEquals(
            $user->getStatus(),
            $packageAsXml->Parameters->User->Profile->Status
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            (string) $user->getReceiveNotifications(),
            $packageAsXml->Parameters->User->Profile->ReceiveNotifications
        );

        // Ensure that the <Groups> tag has the correct children.
        $group1 = $packageAsXml->Parameters->User->Groups->Group[0];
        $group2 = $packageAsXml->Parameters->User->Groups->Group[1];
        $group1Elements = [];
        foreach ($group1->children() as $group) {
            $group1Elements[] = $group->getName();
        }
        self::assertCount(3, $group1Elements);
        self::assertContains('GroupName', $group1Elements);
        self::assertEquals(
            $user->getGroups()[0]->getGroupName(),
            $group1->GroupName
        );
        self::assertContains('GroupAction', $group1Elements);
        self::assertEquals(
            $user->getGroups()[0]->getAction(),
            $group1->GroupAction
        );
        self::assertContains('GroupPermissions', $group1Elements);
        $permission1 = $group1->GroupPermissions->Permission[0];
        $permission2 = $group1->GroupPermissions->Permission[1];
        $permission1Tags = [];
        foreach ($permission1->children() as $tag) {
            $permission1Tags[] = $tag->getName();
        }
        self::assertCount(2, $permission1Tags);
        self::assertContains('Action', $permission1Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[0]->getAction(),
            $group1->GroupPermissions->Permission[0]->Action
        );
        self::assertContains('Code', $permission1Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[0]->getCode(),
            $group1->GroupPermissions->Permission[0]->Code
        );
        $permission2Tags = [];
        foreach ($permission2->children() as $tag) {
            $permission2Tags[] = $tag->getName();
        }
        self::assertCount(2, $permission2Tags);
        self::assertContains('Action', $permission2Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[1]->getAction(),
            $group1->GroupPermissions->Permission[1]->Action
        );
        self::assertContains('Code', $permission2Tags);
        self::assertEquals(
            $user->getGroups()[0]->getPermissions()[1]->getCode(),
            $group1->GroupPermissions->Permission[1]->Code
        );

        $group2Elements = [];
        foreach ($group2->children() as $group) {
            $group2Elements[] = $group->getName();
        }
        self::assertCount(3, $group2Elements);
        self::assertContains('GroupName', $group2Elements);
        self::assertEquals(
            $user->getGroups()[1]->getGroupName(),
            $group2->GroupName
        );
        self::assertContains('GroupAction', $group2Elements);
        self::assertEquals(
            $user->getGroups()[1]->getGroupName(),
            $group2->GroupName
        );
        self::assertContains('GroupPermissions', $group2Elements);
        $permissionTags = [];
        foreach ($group2->GroupPermissions->children() as $tag) {
            $permissionTags[] = $tag->getName();
        }
        self::assertCount(0, $permissionTags);

        // Ensure that the <Venues> and <Wages> tags are empty.
        self::assertCount(
            0,
            $packageAsXml->Parameters->User->Venues->children()
        );
        self::assertCount(
            0,
            $packageAsXml->Parameters->User->Wages->Children()
        );
    }

    /**
     * Test that updateUser() throws an exception when the request results
     * in an HTTP error.
     */
    public function testUpdateUserThrowsExceptionWhenHTTPErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

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
        $client->updateUser($this->user1);
    }

    /**
     * Test that updateUser() throws an exception when the SmarterU API
     * returns a fatal error, as indicated by the value of the <Result>
     * tag.
     */
    public function testUpdateUserThrowsExceptionWhenFatalErrorReturned() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

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
        $client->updateUser($this->user1);
    }

    /**
     * Test that updateUser() returns the expected output when the SmarterU API
     * returns a non-fatal error.
     */
    public function testUpdateUserHandlesNonFatalError() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', $this->user1->getEmail());
        $info->addChild('EmployeeID', $this->user1->getEmployeeId());
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
        $result = $client->updateUser($this->user1);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertArrayHasKey('Email', $response);
        self::assertEquals($response['Email'], $this->user1->getEmail());
        self::assertArrayHasKey('EmployeeID', $response);
        self::assertEquals(
            $response['EmployeeID'],
            $this->user1->getEmployeeId()
        );

        self::assertIsArray($errors);
        self::assertCount(1, $errors);
        self::assertArrayHasKey('Error 1', $errors);
        self::assertEquals($errors['Error 1'], 'Non-fatal Error');
    }

    /**
     * Test that updateUser() returns the expected output when the SmarterU API
     * does not return any errors.
     */
    public function testUpdateUserReturnsExpectedResult() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', $this->user1->getEmail());
        $info->addChild('EmployeeID', $this->user1->getEmployeeId());
        $xml->addChild('Errors');
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
        $result = $client->updateUser($this->user1);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertArrayHasKey('Email', $response);
        self::assertEquals($response['Email'], $this->user1->getEmail());
        self::assertArrayHasKey('EmployeeID', $response);
        self::assertEquals(
            $response['EmployeeID'],
            $this->user1->getEmployeeId()
        );

        self::assertIsArray($errors);
        self::assertCount(0, $errors);
    }
}
