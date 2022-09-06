<?php

/**
 * Contains Tests\CBS\SmarterU\GetGroupClientTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/05
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\GroupPermissions;
use CBS\SmarterU\DataTypes\LearningModule;
use CBS\SmarterU\DataTypes\SubscriptionVariant;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\DataTypes\Permission;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\GetUserGroupsQuery;
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
 * Tests CBS\SmarterU\Client::createGroup().
 */
class CreateGroupClientTest extends TestCase {
    /**
     * A Group to pass into the Client for testing.
     */
    protected Group $group;

    /**
     * Set up the Group for testing.
     */
    public function setUp(): void {
        $name = 'My Group';
        $groupId = '12';
        $createdDate = new DateTime('2022/08/02');
        $modifiedDate = new DateTime();
        $description = 'This is a group created for testing.';
        $homeGroupMessage = 'Home Group';
        $email1 = 'phpunit@test.com';
        $email2 = 'test@phpunit.com';
        $notificationEmails = [$email1, $email2];
        $userHelpOverrideDefault = false;
        $userHelpEnabled = true;
        $helpEmail1 = 'phpunit2@test.com';
        $helpEmail2 = 'test2@phpunit.com';
        $userHelpEmail = [$helpEmail1, $helpEmail2];
        $userHelpText = 'Help Message';
        $tag1 = (new Tag())
            ->setTagId('1')
            ->setTagValues('Tag1 values');
        $tag2 = (new Tag())
            ->setTagId('2')
            ->setTagValues('Tag2 values');
        $tags = [$tag1, $tag2];
        $userLimitEnabled = true;
        $userLimitAmount = 50;
        $status = 'Active';
        $permission1 = (new Permission())
            ->setCode('MANAGE_USERS');
        $permission2 = (new Permission())
            ->setCode('MANAGE_COURSES');
        $user1 = (new GroupPermissions())
            ->setEmployeeId('2')
            ->setHomeGroup(true)
            ->setPermissions([$permission1, $permission2]);
        $user2 = (new GroupPermissions())
            ->setEmployeeId('3')
            ->setHomeGroup(false)
            ->setPermissions([]);
        $users = [$user1, $user2];
        $module1 = (new LearningModule())
            ->setId('4')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);
        $learningModules = [$module1, $module2];
        $variant1 = (new SubscriptionVariant())
            ->setId('6')
            ->setRequiresCredits(true);
        $variant2 = (new SubscriptionVariant())
            ->setId('7')
            ->setRequiresCredits(false);
        $subscriptionVariants = [$variant1, $variant2];
        $dashboardSetId = '8';

        $this->group = (new Group())
            ->setName($name)
            ->setGroupId($groupId)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setDescription($description)
            ->setHomeGroupMessage($homeGroupMessage)
            ->setNotificationEmails($notificationEmails)
            ->setUserHelpOverrideDefault($userHelpOverrideDefault)
            ->setUserHelpEnabled($userHelpEnabled)
            ->setUserHelpEmail($userHelpEmail)
            ->setUserHelpText($userHelpText)
            ->setTags($tags)
            ->setUserLimitEnabled($userLimitEnabled)
            ->setUserLimitAmount($userLimitAmount)
            ->setStatus($status)
            ->setUsers($users)
            ->setLearningModules($learningModules)
            ->setSubscriptionVariants($subscriptionVariants)
            ->setDashboardSetId($dashboardSetId);
    }

    /**
     * Test that Client::createGroup() produces the expected input for the API
     * when all required information and all optional information is present.
     */
    public function testCreateGroupProducesExpectedInputWhenAllInfoIsPresent() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $name = $this->group->getName();
        $groupId = $this->group->getGroupId();
        
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Group>$name</Group>
                <GroupID>$groupId</GroupID>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;
    
        // Set up the container to capture the request.
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
    
        // Make the request.
        $client->createGroup($this->group);
    
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
        self::assertEquals('createGroup', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);
    
        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);
        $groupInfo = [];
        foreach ($packageAsXml->Parameters->Group->children() as $info) {
            $groupInfo[] = $info->getName();
        }
        self::assertCount(16, $groupInfo);
        self::assertContains('Name', $groupInfo);
        self::assertEquals(
            $this->group->getName(),
            $packageAsXml->Parameters->Group->Name
        );
        self::assertContains('GroupID', $groupInfo);
        self::assertEquals(
            $this->group->getGroupId(),
            $packageAsXml->Parameters->Group->GroupID
        );
        self::assertContains('Status', $groupInfo);
        self::assertEquals(
            $this->group->getStatus(),
            $packageAsXml->Parameters->Group->Status
        );
        self::assertContains('Description', $groupInfo);
        self::assertEquals(
            $this->group->getDescription(),
            $packageAsXml->Parameters->Group->Description
        );
        self::assertContains('HomeGroupMessage', $groupInfo);
        self::assertEquals(
            $this->group->getHomeGroupMessage(),
            $packageAsXml->Parameters->Group->HomeGroupMessage
        );
        self::assertContains('NotificationEmails', $groupInfo);
        $emails = [];
        foreach ($packageAsXml->Parameters->Group->NotificationEmails->children() as $email) {
            $emails[] = (string) $email;
        }
        self::assertEquals(
            count($emails),
            count($this->group->getNotificationEmails())
        );
        foreach ($emails as $email) {
            self::assertContains(
                $email,
                $this->group->getNotificationEmails()
            );
        }
        self::assertContains('UserHelpOverrideDefault', $groupInfo);
        self::assertEquals(
            $this->group->getUserHelpOverrideDefault() ? '1' : '0',
            $packageAsXml->Parameters->Group->UserHelpOverrideDefault
        );
        self::assertContains('UserHelpEnabled', $groupInfo);
        self::assertEquals(
            $this->group->getUserHelpEnabled() ? '1' : '0',
            $packageAsXml->Parameters->Group->UserHelpEnabled
        );
        self::assertContains('UserHelpEmail', $groupInfo);
        self::assertEquals(
            implode(',', $this->group->getUserHelpEmail()),
            $packageAsXml->Parameters->Group->UserHelpEmail
        );
        self::assertContains('UserHelpText', $groupInfo);
        self::assertEquals(
            $this->group->getUserHelpText(),
            $packageAsXml->Parameters->Group->UserHelpText
        );
        self::assertContains('Tags2', $groupInfo);
        $tags = [];
        foreach ($packageAsXml->Parameters->Group->Tags2->children() as $tag) {
            $tags[] = (array) $tag;
        }
        self::assertCount(2, $tags);
        self::assertIsArray($tags[0]);
        self::assertCount(2, $tags[0]);
        self::assertArrayHasKey('TagID', $tags[0]);
        self::assertEquals(
            $tags[0]['TagID'],
            $this->group->getTags()[0]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[0]);
        self::assertEquals(
            $tags[0]['TagValues'],
            $this->group->getTags()[0]->getTagValues()
        );
        self::assertIsArray($tags[1]);
        self::assertCount(2, $tags[1]);
        self::assertArrayHasKey('TagID', $tags[1]);
        self::assertEquals(
            $tags[1]['TagID'],
            $this->group->getTags()[1]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[1]);
        self::assertEquals(
            $tags[1]['TagValues'],
            $this->group->getTags()[1]->getTagValues()
        );
        self::assertContains('UserLimit', $groupInfo);
        $limitTags = [];
        foreach ($packageAsXml->Parameters->Group->UserLimit->children() as $limitTag) {
            $limitTags[] = $limitTag->getName();
        }
        self::assertCount(2, $limitTags);
        self::assertContains('Enabled', $limitTags);
        self::assertEquals(
            $this->group->getUserLimitEnabled() ? '1' : '0',
            (string) $packageAsXml->Parameters->Group->UserLimit->Enabled
        );
        self::assertContains('Amount', $limitTags);
        self::assertEquals(
            $this->group->getUserLimitAmount(),
            (int) $packageAsXml->Parameters->Group->UserLimit->Amount
        );
        $users = [];
        foreach ($packageAsXml->Parameters->Group->Users->children() as $user) {
            $users[] = (array) $user;
        }
        self::assertCount(2, $users);
        foreach ($users as $user) {
            self::assertIsArray($user);
            self::assertCount(3, $user);
            self::assertArrayHasKey('EmployeeID', $user);
            self::assertArrayHasKey('HomeGroup', $user);
            self::assertArrayHasKey('Permissions', $user);
        }
        self::assertEquals(
            $users[0]['EmployeeID'],
            $this->group->getUsers()[0]->getEmployeeId()
        );
        self::assertEquals(
            $users[0]['HomeGroup'],
            $this->group->getUsers()[0]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[0]['Permissions'];
        $codes = $codeSegment['Code'];
        self::assertEquals(
            count($codes),
            count($this->group->getUsers()[0]->getPermissions())
        );
        foreach ($this->group->getUsers()[0]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertEquals(
            $users[1]['EmployeeID'],
            $this->group->getUsers()[1]->getEmployeeId()
        );
        self::assertEquals(
            $users[1]['HomeGroup'],
            $this->group->getUsers()[1]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[1]['Permissions'];
        self::assertEquals(
            count($codeSegment),
            count($this->group->getUsers()[1]->getPermissions())
        );
        foreach ($this->group->getUsers()[1]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertContains('LearningModules', $groupInfo);
        $modules = [];
        foreach ($packageAsXml->Parameters->Group->LearningModules->LearningModule as $module) {
            $modules[] = (array) $module;
        }

        self::assertEquals(
            count($modules),
            count($this->group->getLearningModules())
        );
        foreach ($modules as $module) {
            self::assertIsArray($module);
            self::assertCount(3, $module);
            self::assertArrayHasKey('ID', $module);
            self::assertArrayHasKey('AllowSelfEnroll', $module);
            self::assertArrayHasKey('AutoEnroll', $module);
        }
        self::assertEquals(
            $modules[0]['ID'],
            $this->group->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $this->group->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $this->group->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $this->group->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $this->group->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $this->group->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );

        self::assertContains('SubscriptionVariants', $groupInfo);
        $variants = [];
        foreach ($packageAsXml->Parameters->Group->SubscriptionVariants->SubscriptionVariant as $variant) {
            $variants[] = (array) $variant;
        }

        self::assertEquals(
            count($variants),
            count($this->group->getSubscriptionVariants())
        );
        foreach ($variants as $variant) {
            self::assertIsArray($variant);
            self::assertCount(2, $variant);
            self::assertArrayHasKey('ID', $variant);
            self::assertArrayHasKey('RequiresCredits', $variant);
        }
        self::assertEquals(
            $variants[0]['ID'],
            $this->group->getSubscriptionVariants()[0]->getId()
        );
        self::assertEquals(
            $variants[0]['RequiresCredits'],
            $this->group->getSubscriptionVariants()[0]->getRequiresCredits() ? '1' : '0'
        );
        self::assertEquals(
            $variants[1]['ID'],
            $this->group->getSubscriptionVariants()[1]->getId()
        );
        self::assertEquals(
            $variants[1]['RequiresCredits'],
            $this->group->getSubscriptionVariants()[1]->getRequiresCredits() ? '1' : '0'
        );
        self::assertContains('DashboardSetID', $groupInfo);
        self::assertEquals(
            $packageAsXml->Parameters->Group->DashboardSetID,
            $this->group->getDashboardSetId()
        );
    }

    /**
     * Test that Client::createGroup() produces the expected input for the API
     * when all required information but no optional information is present.
     */
    public function testCreateGroupProducesExpectedInputWithOnlyRequiredInfo() {
        $accountApi = 'account';
        $userApi = 'user';
        $method = 'method';
        $name = 'Test Group';
        $status = 'Active';
        $description = 'A group for testing';
        $homeGroupMessage = 'Home Group';
        $email1 = 'test@test.com';
        $email2 = 'phpunit@test.com';
        $notificationEmails = [$email1, $email2];
        $users = $this->group->getUsers();
        $learningModules = $this->group->getLearningModules();

        $group = (new Group())
            ->setName($name)
            ->setStatus($status)
            ->setDescription($description)
            ->setHomeGroupMessage($homeGroupMessage)
            ->setNotificationEmails($notificationEmails)
            ->setUsers($users)
            ->setLearningModules($learningModules);

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $groupId = $this->group->getGroupId();
        
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Group>$name</Group>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;
    
        // Set up the container to capture the request.
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
    
        // Make the request.
        $client->createGroup($group);
    
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
        self::assertEquals('createGroup', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);
        $groupTags = [];
        foreach ($packageAsXml->Parameters->Group->children() as $tag) {
            $groupTags[] = $tag->getName();
        }
        self::assertCount(7, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals(
            $group->getName(),
            $packageAsXml->Parameters->Group->Name
        );
        self::assertContains('Status', $groupTags);
        self::assertEquals(
            $group->getStatus(),
            $packageAsXml->Parameters->Group->Status
        );
        self::assertContains('Description', $groupTags);
        self::assertEquals(
            $group->getDescription(),
            $packageAsXml->Parameters->Group->Description
        );
        self::assertContains('HomeGroupMessage', $groupTags);
        self::assertEquals(
            $group->getHomeGroupMessage(),
            $packageAsXml->Parameters->Group->HomeGroupMessage
        );
        self::assertContains('NotificationEmails', $groupTags);
        $emails = [];
        foreach ((array) $packageAsXml->Parameters->Group->NotificationEmails->NotificationEmail as $email) {
            $emails[] = $email;
        }
        self::assertEquals(
            count($emails),
            count($group->getNotificationEmails())
        );
        foreach ($emails as $email) {
            self::assertContains($email, $group->getNotificationEmails());
        }
        $users = [];
        foreach ($packageAsXml->Parameters->Group->Users->children() as $user) {
            $users[] = (array) $user;
        }
        self::assertCount(2, $users);
        foreach ($users as $user) {
            self::assertIsArray($user);
            self::assertCount(3, $user);
            self::assertArrayHasKey('EmployeeID', $user);
            self::assertArrayHasKey('HomeGroup', $user);
            self::assertArrayHasKey('Permissions', $user);
        }
        self::assertEquals(
            $users[0]['EmployeeID'],
            $group->getUsers()[0]->getEmployeeId()
        );
        self::assertEquals(
            $users[0]['HomeGroup'],
            $group->getUsers()[0]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[0]['Permissions'];
        $codes = $codeSegment['Code'];
        self::assertEquals(
            count($codes),
            count($group->getUsers()[0]->getPermissions())
        );
        foreach ($group->getUsers()[0]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertEquals(
            $users[1]['EmployeeID'],
            $group->getUsers()[1]->getEmployeeId()
        );
        self::assertEquals(
            $users[1]['HomeGroup'],
            $group->getUsers()[1]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[1]['Permissions'];
        self::assertEquals(
            count($codeSegment),
            count($group->getUsers()[1]->getPermissions())
        );
        foreach ($group->getUsers()[1]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertContains('LearningModules', $groupTags);
        $modules = [];
        foreach ($packageAsXml->Parameters->Group->LearningModules->LearningModule as $module) {
            $modules[] = (array) $module;
        }

        self::assertEquals(
            count($modules),
            count($group->getLearningModules())
        );
        foreach ($modules as $module) {
            self::assertIsArray($module);
            self::assertCount(3, $module);
            self::assertArrayHasKey('ID', $module);
            self::assertArrayHasKey('AllowSelfEnroll', $module);
            self::assertArrayHasKey('AutoEnroll', $module);
        }
        self::assertEquals(
            $modules[0]['ID'],
            $group->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $group->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $group->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $group->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $group->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $group->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );
    }

    /**
     * Test that createGroup() throws an exception when the request results
     * in an HTTP error.
     */
    public function testCreateGroupThrowsExceptionWhenHTTPErrorOccurs() {
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
        $client->createGroup($this->group);
    }

    /**
     * Test that createGroup() throws an exception when the SmarterU API
     * returns a fatal error.
     */
    public function testCreateGroupThrowsExceptionWhenFatalErrorReturned() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
            <Result>Failed</Result>
            <Errors>
                <Error>
                    <ErrorID>Error1</ErrorID>
                    <ErrorMessage>Testing</ErrorMessage>
                </Error>
                <Error>
                    <ErrorID>Error2</ErrorID>
                    <ErrorMessage>123</ErrorMessage>
                </Error>
            </Errors>
        </SmarterU>
        XML;
    
        $xml = simplexml_load_string($xmlString);
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
        self::expectExceptionMessage('Error1: Testing, Error2: 123');
        $client->createGroup($this->group);
    }

    /**
     * Test that createGroup() returns the expected output when the SmarterU
     * API returns no errors.
     */
    public function testCreateGroupProducesCorrectOutput() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $name = $this->group->getName();
        $groupId = $this->group->getGroupId();

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Group>$name</Group>
                <GroupID>$groupId</GroupID>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;
    
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
            
        // Make the request.
        $result = $client->createGroup($this->group);
        
        self::assertInstanceOf(Group::class, $result);
        self::assertEquals($this->group, $result);
    }
}
