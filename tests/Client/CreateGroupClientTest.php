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
use CBS\SmarterU\DataTypes\LearningModule;
use CBS\SmarterU\DataTypes\SubscriptionVariant;
use CBS\SmarterU\DataTypes\Tag;
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
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->createGroup(
            $accountApi,
            $userApi,
            $this->group
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
        $expectedBody = 'Package=' . $client->getXMLGenerator()->createGroup(
            $accountApi,
            $userApi,
            $group
        );
        self::assertEquals($decodedBody, $expectedBody);
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
        self::assertEquals($this->group->getName(), $result->getName());
        self::assertEquals($this->group->getGroupId(), $result->getGroupId());
    }
}
