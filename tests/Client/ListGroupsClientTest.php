<?php

/**
 * Contains CBS\SmarterU\tests\Client\GetUserGroupsClientTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/03
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU;

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
        self::assertEquals('listGroups', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);
        $group = [];
        foreach ($packageAsXml->Parameters->Group->children() as $groupTag) {
            $group[] = $groupTag->getName();
        }
        self::assertCount(1, $group);
        self::assertContains('Filters', $group);
        $filters = [];
        foreach ($packageAsXml->Parameters->Group->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(0, $filters);
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
        self::assertEquals('listGroups', $packageAsXml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($packageAsXml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);
        $group = [];
        foreach ($packageAsXml->Parameters->Group->children() as $groupTag) {
            $group[] = $groupTag->getName();
        }
        self::assertCount(1, $group);
        self::assertContains('Filters', $group);
        $filters = [];
        foreach ($packageAsXml->Parameters->Group->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(3, $filters);
        self::assertContains('GroupName', $filters);
        $groupNameTag = [];
        foreach ($packageAsXml->Parameters->Group->Filters->GroupName->children() as $tag) {
            $groupNameTag[] = $tag->getName();
        }
        self::assertCount(2, $groupNameTag);
        self::assertContains('MatchType', $groupNameTag);
        self::assertEquals(
            $query->getGroupName()->getMatchType(),
            $packageAsXml->Parameters->Group->Filters->GroupName->MatchType
        );
        self::assertContains('Value', $groupNameTag);
        self::assertEquals(
            $query->getGroupName()->getValue(),
            $packageAsXml->Parameters->Group->Filters->GroupName->Value
        );
        self::assertContains('GroupStatus', $filters);
        self::assertEquals(
            $query->getGroupStatus(),
            $packageAsXml->Parameters->Group->Filters->GroupStatus
        );
        self::assertContains('Tags2', $filters);
        $tags = [];
        foreach ($packageAsXml->Parameters->Group->Filters->Tags2->children() as $tag) {
            $tags[] = (array) $tag;
        }
        self::assertCount(2, $tags);
        self::assertIsArray($tags[0]);
        self::assertCount(2, $tags[0]);
        self::assertArrayHasKey('TagID', $tags[0]);
        self::assertEquals(
            $tags[0]['TagID'],
            $query->getTags()[0]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[0]);
        self::assertEquals(
            $tags[0]['TagValues'],
            $query->getTags()[0]->getTagValues()
        );
        self::assertIsArray($tags[1]);
        self::assertCount(2, $tags[1]);
        self::assertArrayHasKey('TagName', $tags[1]);
        self::assertEquals(
            $tags[1]['TagName'],
            $query->getTags()[1]->getTagName()
        );
        self::assertArrayHasKey('TagValues', $tags[1]);
        self::assertEquals(
            $tags[1]['TagValues'],
            $query->getTags()[1]->getTagValues()
        );
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
        self::expectExceptionMessage(
            'SmarterU rejected the request due to the following errors: Error1: Testing, Error2: 123'
        );
        $client->listGroups($query);
    }

    /**
     * Test that listGroups() produces the correct output when a non-fatal
     * error occurs and multiple groups are returned.
     */
    public function testListGroupsHandlesNonFatalError() {
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
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertIsArray($result['Response']);
        self::assertCount(3, $result['Response']);
        foreach ($result['Response'] as $group) {
            self::assertCount(2, $group);
            self::assertArrayHasKey('Name', $group);
            self::assertArrayHasKey('GroupID', $group);
        }
        self::assertEquals($group1Name, $result['Response'][0]['Name']);
        self::assertEquals($group1Id, $result['Response'][0]['GroupID']);
        self::assertEquals($group2Name, $result['Response'][1]['Name']);
        self::assertEquals($group2Id, $result['Response'][1]['GroupID']);
        self::assertEquals($group3Name, $result['Response'][2]['Name']);
        self::assertEquals($group3Id, $result['Response'][2]['GroupID']);

        self::assertArrayHasKey('Errors', $result);
        self::assertIsArray($result['Errors']);
        self::assertCount(2, $result['Errors']);
        self::assertArrayHasKey('Error1', $result['Errors']);
        self::assertEquals('Testing', $result['Errors']['Error1']);
        self::assertArrayHasKey('Error2', $result['Errors']);
        self::assertEquals('123', $result['Errors']['Error2']);
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
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertIsArray($result['Response']);
        self::assertCount(1, $result['Response']);
        foreach ($result['Response'] as $group) {
            self::assertCount(2, $group);
            self::assertArrayHasKey('Name', $group);
            self::assertArrayHasKey('GroupID', $group);
        }
        self::assertEquals($groupName, $result['Response'][0]['Name']);
        self::assertEquals($groupId, $result['Response'][0]['GroupID']);

        self::assertArrayHasKey('Errors', $result);
        self::assertIsArray($result['Errors']);
        self::assertCount(0, $result['Errors']);
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
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertIsArray($result['Response']);
        self::assertCount(3, $result['Response']);
        foreach ($result['Response'] as $group) {
            self::assertCount(2, $group);
            self::assertArrayHasKey('Name', $group);
            self::assertArrayHasKey('GroupID', $group);
        }
        self::assertEquals($group1Name, $result['Response'][0]['Name']);
        self::assertEquals($group1Id, $result['Response'][0]['GroupID']);
        self::assertEquals($group2Name, $result['Response'][1]['Name']);
        self::assertEquals($group2Id, $result['Response'][1]['GroupID']);
        self::assertEquals($group3Name, $result['Response'][2]['Name']);
        self::assertEquals($group3Id, $result['Response'][2]['GroupID']);

        self::assertArrayHasKey('Errors', $result);
        self::assertIsArray($result['Errors']);
        self::assertCount(0, $result['Errors']);
    }
}
