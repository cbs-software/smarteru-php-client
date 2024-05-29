<?php

/**
 * Contains CBS\SmarterU\tests\Client\GetUserGroupsClientTest.
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/03
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU;

use CBS\SmarterU\DataTypes\ErrorCode;
use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\ListGroupsQuery;
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
 * Tests CBS\SmarterU\Client::listGroups().
 */
class ListGroupsClientTest extends TestCase {
    /**
     * Test that listGroups() passes the correct input into the SmarterU API
     * when the query is blank (i.e. list all groups without filtering results).
     */
    public function testListGroupsProducesCorrectInputForEmptyQuery() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = new ListGroupsQuery();

        $group1Name = 'Group 1';
        $group1Id = '1';
        $group2Name = 'Group 2';
        $group2Id = '2';
        $group3Name = 'Group 3';
        $group3Id = '3';

        /**
         * The response needs a body because listGroups() will try to
         * process the body once the response has been received, however
         * this test is about making sure the request made by listGroups()
         * is correct. The processing of the response will be tested further
         * down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Groups>
                    <Group>
                        <Name>$group1Name</Name>
                        <GroupID>$group1Id</GroupID>
                    </Group>
                    <Group>
                        <Name>$group2Name</Name>
                        <GroupID>$group2Id</GroupID>
                    </Group>
                    <Group>
                        <Name>$group3Name</Name>
                        <GroupID>$group3Id</GroupID>
                    </Group>
                </Groups>
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
        $client->listGroups($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->listGroups(
            $accountApi,
            $userApi,
            $query
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that listGroups() passes the correct input into the SmarterU API
     * when all optional information is present.
     */
    public function testListGroupsProducesCorrectInputWithAllOptionalInfo() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $groupName = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue('My Group');
        $groupStatus = 'Active';
        $tag1 = (new Tag())
            ->setTagId('1')
            ->setTagValues('Some values');
        $tag2 = (new Tag())
            ->setTagName('My Tag')
            ->setTagValues('Tag 2 values');
        $tags = [$tag1, $tag2];

        $query = (new ListGroupsQuery())
            ->setAccountApi($accountApi)
            ->setUserApi($userApi)
            ->setGroupName($groupName)
            ->setGroupStatus($groupStatus)
            ->setTags($tags);

        $group1Name = 'Group 1';
        $group1Id = '1';
        $group2Name = 'Group 2';
        $group2Id = '2';
        $group3Name = 'Group 3';
        $group3Id = '3';

        /**
         * The response needs a body because listGroups() will try to
         * process the body once the response has been received, however
         * this test is about making sure the request made by listGroups()
         * is correct. The processing of the response will be tested further
         * down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Groups>
                    <Group>
                        <Name>$group1Name</Name>
                        <GroupID>$group1Id</GroupID>
                    </Group>
                    <Group>
                        <Name>$group2Name</Name>
                        <GroupID>$group2Id</GroupID>
                    </Group>
                    <Group>
                        <Name>$group3Name</Name>
                        <GroupID>$group3Id</GroupID>
                    </Group>
                </Groups>
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
        $client->listGroups($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->listGroups(
            $accountApi,
            $userApi,
            $query
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that listGroups() throws an exception when the request results
     * in an HTTP error.
     */
    public function testListGroupsThrowsExceptionWhenHTTPErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = new ListGroupsQuery();

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
        $client->listGroups($query);
    }

    /**
     * Test that listGroups() throws an exception when the SmarterU API
     * returns a fatal error.
     */
    public function testListGroupsThrowsExceptionWhenFatalErrorReturned() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = new ListGroupsQuery();

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
                'request' => "<?xml version=\"1.0\"?>\n<SmarterU>\n<AccountAPI>********</AccountAPI><UserAPI>********</UserAPI><Method>listGroups</Method><Parameters><Group><Filters/></Group></Parameters></SmarterU>\n",
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
            $client->listGroups($query);
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
     * Test that listGroups() produces the correct output when no errors occur
     * and only a single group is returned.
     */
    public function testListGroupsReturnsExpectedSingleGroup() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = new ListGroupsQuery();

        $groupName = 'Group 1';
        $groupId = '1';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Groups>
                    <Group>
                        <Name>$groupName</Name>
                        <GroupID>$groupId</GroupID>
                    </Group>
                </Groups>
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
        $result = $client->listGroups($query);

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertInstanceOf(Group::class, $result[0]);
        self::assertEquals($groupName, $result[0]->getName());
        self::assertEquals($groupId, $result[0]->getGroupId());
    }

    /**
     * Test that listGroups() produces the correct output when no errors occur
     * and multiple groups are returned.
     */
    public function testListGroupsReturnsExpectedMultipleGroups() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = new ListGroupsQuery();

        $group1Name = 'Group 1';
        $group1Id = '1';
        $group2Name = 'Group 2';
        $group2Id = '2';
        $group3Name = 'Group 3';
        $group3Id = '3';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Groups>
                    <Group>
                        <Name>$group1Name</Name>
                        <GroupID>$group1Id</GroupID>
                    </Group>
                    <Group>
                        <Name>$group2Name</Name>
                        <GroupID>$group2Id</GroupID>
                    </Group>
                    <Group>
                        <Name>$group3Name</Name>
                        <GroupID>$group3Id</GroupID>
                    </Group>
                </Groups>
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
        $result = $client->listGroups($query);

        self::assertIsArray($result);
        self::assertCount(3, $result);
        self::assertInstanceOf(Group::class, $result[0]);
        self::assertEquals($group1Name, $result[0]->getName());
        self::assertEquals($group1Id, $result[0]->getGroupId());
        self::assertInstanceOf(Group::class, $result[1]);
        self::assertEquals($group2Name, $result[1]->getName());
        self::assertEquals($group2Id, $result[1]->getGroupId());
        self::assertInstanceOf(Group::class, $result[2]);
        self::assertEquals($group3Name, $result[2]->getName());
        self::assertEquals($group3Id, $result[2]->getGroupId());
    }
}
