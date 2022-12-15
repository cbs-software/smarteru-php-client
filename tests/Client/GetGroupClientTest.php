<?php

/**
 * Contains Tests\CBS\SmarterU\Client\GetGroupClientTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/08
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU;

use CBS\SmarterU\DataTypes\ErrorCode;
use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\GetGroupQuery;
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
 * Tests CBS\SmarterU\Client::getGroup().
 */
class GetGroupClientTest extends TestCase {
    /**
     * Test that getGroup() passes the correct input into the SmarterU API
     * when all required information is present and the query uses the group
     * name as the group identifier.
     */
    public function testGetGroupProducesCorrectInputForGroupName() {
        $accountApi = 'account';
        $userApi = 'user';
        $name = 'My Group';
        $client = new Client($accountApi, $userApi);

        $groupId = '1';
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';
        $description = 'This is a group description.';
        $homeGroupMessage = 'My Home Group';
        $email1 = 'test@test.com';
        $email2 = 'phpunit@test.com';
        $userCount = '5';
        $learningModuleCount = '2';
        $tag1Id = '1';
        $tag1Name = 'First Tag';
        $tag1Values = 'Tag, 1\'s, values';
        $tag2Id = '2';
        $tag2Name = 'Second Tag';
        $tag2Values = 'Some, values';
        $status = 'Active';

        /**
         * The response needs a body because getGroup() will try to
         * process the body once the response has been received, however
         * this test is about making sure the request made by getGroup()
         * is correct. The processing of the response will be tested further
         * down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Group>
                    <Name>$name</Name>
                    <GroupID>$groupId</GroupID>
                    <CreatedDate>$createdDate</CreatedDate>
                    <ModifiedDate>$modifiedDate</ModifiedDate>
                    <Description>$description</Description>
                    <HomeGroupMessage>$homeGroupMessage</HomeGroupMessage>
                    <NotificationEmails>
                        <NotificationEmail>$email1</NotificationEmail>
                        <NotificationEmail>$email2</NotificationEmail>
                    </NotificationEmails>
                    <UserCount>$userCount</UserCount>
                    <LearningModuleCount>$learningModuleCount</LearningModuleCount>
                    <Tags2>
                        <Tag2>
                            <TagID>$tag1Id</TagID>
                            <TagName>$tag1Name</TagName>
                            <TagValues>$tag1Values</TagValues>
                        </Tag2>
                        <Tag2>
                            <TagID>$tag2Id</TagID>
                            <TagName>$tag2Name</TagName>
                            <TagValues>$tag2Values</TagValues>
                        </Tag2>
                    </Tags2>
                    <Status>$status</Status>
                </Group>
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
        $client->readGroupByName($name);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->getGroup(
            $accountApi,
            $userApi,
            (new GetGroupQuery())
                ->setName($name)
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that getGroup() passes the correct input into the SmarterU API
     * when all required information is present and the query uses the group
     * ID as the group identifier.
     */
    public function testGetGroupProducesCorrectInputForGroupID() {
        $accountApi = 'account';
        $userApi = 'user';
        $groupId = '1';
        $client = new Client($accountApi, $userApi);

        $name = 'My Group';
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';
        $description = 'This is a group description.';
        $homeGroupMessage = 'My Home Group';
        $email1 = 'test@test.com';
        $email2 = 'phpunit@test.com';
        $userCount = '5';
        $learningModuleCount = '2';
        $tag1Id = '1';
        $tag1Name = 'First Tag';
        $tag1Values = 'Tag, 1\'s, values';
        $tag2Id = '2';
        $tag2Name = 'Second Tag';
        $tag2Values = 'Some, values';
        $status = 'Active';

        /**
         * The response needs a body because getGroup() will try to
         * process the body once the response has been received, however
         * this test is about making sure the request made by getGroup()
         * is correct. The processing of the response will be tested further
         * down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Group>
                    <Name>$name</Name>
                    <GroupID>$groupId</GroupID>
                    <CreatedDate>$createdDate</CreatedDate>
                    <ModifiedDate>$modifiedDate</ModifiedDate>
                    <Description>$description</Description>
                    <HomeGroupMessage>$homeGroupMessage</HomeGroupMessage>
                    <NotificationEmails>
                        <NotificationEmail>$email1</NotificationEmail>
                        <NotificationEmail>$email2</NotificationEmail>
                    </NotificationEmails>
                    <UserCount>$userCount</UserCount>
                    <LearningModuleCount>$learningModuleCount</LearningModuleCount>
                    <Tags2>
                        <Tag2>
                            <TagID>$tag1Id</TagID>
                            <TagName>$tag1Name</TagName>
                            <TagValues>$tag1Values</TagValues>
                        </Tag2>
                        <Tag2>
                            <TagID>$tag2Id</TagID>
                            <TagName>$tag2Name</TagName>
                            <TagValues>$tag2Values</TagValues>
                        </Tag2>
                    </Tags2>
                    <Status>$status</Status>
                </Group>
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
        $client->readGroupById($groupId);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->getGroup(
            $accountApi,
            $userApi,
            (new GetGroupQuery())
                ->setGroupId($groupId)
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that getGroup() throws an exception when the request results
     * in an HTTP error.
     */
    public function testGetGroupThrowsExceptionWhenHTTPErrorOccurs() {
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
        $client->readGroupById('1');
    }

    /**
     * Test that getGroup() throws an exception when the SmarterU API
     * returns a fatal error.
     */
    public function testGetGroupThrowsExceptionWhenFatalErrorReturned() {
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

        $client->setHttpClient($httpClient);

        // Make the request. Because we want to inspect custom exception
        // properties we'll handle the try/catch/cache of the exception
        $exception = null;
        try {
            $client->readGroupByName('My Group');
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
     * Test that getGroup() returns null when the SmarterU API throws an error
     * indicating that the Group requested does not exist.
     */
    public function testGetGroupReturnsNullWhenGroupDoesNotExist() {
        $accountApi = 'account';
        $userApi = 'user';
        $groupId = '1';
        $client = new Client($accountApi, $userApi);

        $name = 'My Group';
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';
        $description = 'This is a group description.';
        $homeGroupMessage = 'My Home Group';
        $email1 = 'test@test.com';
        $email2 = 'phpunit@test.com';
        $userCount = '5';
        $learningModuleCount = '2';
        $tag1Id = '1';
        $tag1Name = 'First Tag';
        $tag1Values = 'Tag, 1\'s, values';
        $tag2Id = '2';
        $tag2Name = 'Second Tag';
        $tag2Values = 'Some, values';
        $status = 'Active';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Failed</Result>
            <Info>
                <Group>
                </Group>
            </Info>
            <Errors>
                <Error>
                    <ErrorID>GG:03</ErrorID>
                    <ErrorMessage>The requested group does not exist.</ErrorMessage>
                </Error>
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
        $result = $client->readGroupById($groupId);

        self::assertNull($result);
    }

    /**
     * Test that getGroup() returns the expected output when the SmarterU API
     * does not return any errors.
     */
    public function testGetGroupProducesCorrectOutput() {
        $accountApi = 'account';
        $userApi = 'user';
        $groupId = '1';
        $client = new Client($accountApi, $userApi);

        $name = 'My Group';
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';
        $description = 'This is a group description.';
        $homeGroupMessage = 'My Home Group';
        $email1 = 'test@test.com';
        $email2 = 'phpunit@test.com';
        $userCount = '5';
        $learningModuleCount = '2';
        $tag1Id = '1';
        $tag1Name = 'First Tag';
        $tag1Values = 'Tag, 1\'s, values';
        $tag2Id = '2';
        $tag2Name = 'Second Tag';
        $tag2Values = 'Some, values';
        $status = 'Active';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Group>
                    <Name>$name</Name>
                    <GroupID>$groupId</GroupID>
                    <CreatedDate>$createdDate</CreatedDate>
                    <ModifiedDate>$modifiedDate</ModifiedDate>
                    <Description>$description</Description>
                    <HomeGroupMessage>$homeGroupMessage</HomeGroupMessage>
                    <NotificationEmails>
                        <NotificationEmail>$email1</NotificationEmail>
                        <NotificationEmail>$email2</NotificationEmail>
                    </NotificationEmails>
                    <UserCount>$userCount</UserCount>
                    <LearningModuleCount>$learningModuleCount</LearningModuleCount>
                    <Tags2>
                        <Tag2>
                            <TagID>$tag1Id</TagID>
                            <TagName>$tag1Name</TagName>
                            <TagValues>$tag1Values</TagValues>
                        </Tag2>
                        <Tag2>
                            <TagID>$tag2Id</TagID>
                            <TagName>$tag2Name</TagName>
                            <TagValues>$tag2Values</TagValues>
                        </Tag2>
                    </Tags2>
                    <Status>$status</Status>
                </Group>
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
        $result = $client->readGroupById($groupId);

        self::assertInstanceOf(Group::class, $result);
        self::assertEquals($name, $result->getName());
        self::assertEquals(new DateTime($createdDate), $result->getCreatedDate());
        self::assertEquals(new DateTime($modifiedDate), $result->getModifiedDate());
        self::assertEquals($description, $result->getDescription());
        self::assertEquals($homeGroupMessage, $result->getHomeGroupMessage());
        self::assertCount(2, $result->getNotificationEmails());
        self::assertContains($email1, $result->getNotificationEmails());
        self::assertContains($email2, $result->getNotificationEmails());
        self::assertEquals((int) $userCount, $result->getUserCount());
        self::assertEquals((int) $learningModuleCount, $result->getLearningModuleCount());
        self::assertEquals($status, $result->getStatus());
        self::assertCount(2, $result->getTags());
        self::assertEquals($tag1Id, $result->getTags()[0]->getTagId());
        self::assertEquals($tag1Name, $result->getTags()[0]->getTagName());
        self::assertEquals($tag1Values, $result->getTags()[0]->getTagValues());
        self::assertEquals($tag2Id, $result->getTags()[1]->getTagId());
        self::assertEquals($tag2Name, $result->getTags()[1]->getTagName());
        self::assertEquals($tag2Values, $result->getTags()[1]->getTagValues());
    }
}
