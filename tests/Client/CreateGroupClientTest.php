<?php

/**
 * Contains Tests\CBS\SmarterU\GetGroupClientTest.php
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\ErrorCode;
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
    public function testCreateGroupProducesExpectedInputWhenAllInfoIsPresent(): void {
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
    public function testCreateGroupProducesExpectedInputWithOnlyRequiredInfo(): void {
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
    public function testCreateGroupThrowsExceptionWhenHTTPErrorOccurs(): void {
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
    public function testCreateGroupThrowsExceptionWhenFatalErrorReturned(): void {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $codes = ['UT:01', 'UT:02'];
        $messages = [
            'An error mocked for unit testing',
            'Another error mocked for unit testing'
        ];
        $body = <<<XML
        <SmarterU>
            <Result>Failed</Result>
            <Errors>
                <Error>
                    <ErrorID>$codes[0]</ErrorID>
                    <ErrorMessage>$messages[0]</ErrorMessage>
                </Error>
                <Error>
                    <ErrorID>$codes[1]</ErrorID>
                    <ErrorMessage>$messages[1]</ErrorMessage>
                </Error>
            </Errors>
        </SmarterU>
        XML;

        $response = new Response(200, [], $body);

        $container = [];
        $history = Middleware::history($container);

        $mock = (new MockHandler([$response]));

        $handlerStack = HandlerStack::create($mock);

        $handlerStack->push($history);

        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logger->expects($this->once())->method('error')->with(
            $this->identicalTo('Failed to make request to SmarterU API. See context for request/response details.'),
            $this->identicalTo([
                'request' => "<?xml version=\"1.0\"?>\n<SmarterU><AccountAPI>********</AccountAPI><UserAPI>********</UserAPI><Method>createGroup</Method><Parameters><Group><Name>My Group</Name><GroupID>12</GroupID><Status>Active</Status><Description>This is a group created for testing.</Description><HomeGroupMessage>Home Group</HomeGroupMessage><NotificationEmails><NotificationEmail>phpunit@test.com</NotificationEmail><NotificationEmail>test@phpunit.com</NotificationEmail></NotificationEmails><UserHelpOverrideDefault>0</UserHelpOverrideDefault><UserHelpEnabled>1</UserHelpEnabled><UserHelpEmail>phpunit2@test.com,test2@phpunit.com</UserHelpEmail><UserHelpText>Help Message</UserHelpText><Tags2><Tag2><TagID>1</TagID><TagValues>Tag1 values</TagValues></Tag2><Tag2><TagID>2</TagID><TagValues>Tag2 values</TagValues></Tag2></Tags2><UserLimit><Enabled>1</Enabled><Amount>50</Amount></UserLimit><Users/><LearningModules><LearningModule><ID>4</ID><AllowSelfEnroll>1</AllowSelfEnroll><AutoEnroll>0</AutoEnroll></LearningModule><LearningModule><ID>5</ID><AllowSelfEnroll>0</AllowSelfEnroll><AutoEnroll>1</AutoEnroll></LearningModule></LearningModules><SubscriptionVariants><SubscriptionVariant><ID>6</ID><RequiresCredits>1</RequiresCredits></SubscriptionVariant><SubscriptionVariant><ID>7</ID><RequiresCredits>0</RequiresCredits></SubscriptionVariant></SubscriptionVariants><DashboardSetID>8</DashboardSetID></Group></Parameters></SmarterU>\n",
                'response' => $body
            ])
        );

        $client
            ->setHttpClient($httpClient)
            ->setLogger($logger);

        // Make the request. Because we want to inspect custom exception
        // properties we'll handle the try/catch/cache of the exception
        $exception = null;
        try {
            $client->createGroup($this->group);
        } catch (SmarterUException $error) {
            $exception = $error;
        }

        self::assertInstanceOf(SmarterUException::class, $exception);
        self::assertEquals(Client::SMARTERU_EXCEPTION_MESSAGE, $exception->getMessage());

        $errorCodes = $error->getErrorCodes();
        self::assertIsArray($errorCodes);
        self::assertCount(2, $errorCodes);

        $errorCode = reset($errorCodes);
        self::assertInstanceOf(ErrorCode::class, $errorCode);
        self::assertContains($errorCode->getErrorCode(), $codes);
        self::assertContains($errorCode->getErrorMessage(), $messages);

        $errorCode = next($errorCodes);
        self::assertInstanceOf(ErrorCode::class, $errorCode);
        self::assertContains($errorCode->getErrorCode(), $codes);
        self::assertContains($errorCode->getErrorMessage(), $messages);
    }

    /**
     * Test that createGroup() returns the expected output when the SmarterU
     * API returns no errors.
     */
    public function testCreateGroupProducesCorrectOutput(): void {
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
