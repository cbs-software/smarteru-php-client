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

use CBS\SmarterU\DataTypes\GroupPermissions;
use CBS\SmarterU\DataTypes\Permission;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\GetUserQuery;
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
 * Tests CBS\SmarterU\Client::getUserGroups().
 */
class GetUserGroupsClientTest extends TestCase {
    /**
     * Test that getUserGroups() passes the correct input into the SmarterU API
     * when all required information is present and the query uses the ID as
     * the user identifier.
     */
    public function testGetUserProducesCorrectInputForUserID() {
        $accountApi = 'account';
        $userApi = 'user';
        $id = '1';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId($id);

        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        /**
         * The response needs a body because getUserGroups() will try to
         * process the body once the response has been received, however
         * this test is about making sure the request made by getUserGroups()
         * is correct. The processing of the response will be tested further
         * down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <UserGroups>
                    <Group>
                        <Name>My Group</Name>
                        <Identifier>My Group</Identifier>
                        <IsHomeGroup>1</IsHomeGroup>

                        <Permissions>
                            <Permission>MANAGE_USERS</Permission>
                        </Permissions>
                    </Group>
                </UserGroups>
            </Info>
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
        $client->getUserGroups($query);

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
        self::assertEquals('getUserGroups', $packageAsXml->Method);
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
     * Test that getUserGroups() passes the correct input into the SmarterU API
     * when all required information is present and the query uses the ID as
     * the user identifier.
     */
    public function testGetUserProducesCorrectInputForEmailAddress() {
        $accountApi = 'account';
        $userApi = 'user';
        $email = 'test@test.com';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setEmail($email);

        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        /**
         * The response needs a body because getUserGroups() will try to
         * process the body once the response has been received, however
         * this test is about making sure the request made by getUserGroups()
         * is correct. The processing of the response will be tested further
         * down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <UserGroups>
                    <Group>
                        <Name>My Group</Name>
                        <Identifier>My Group</Identifier>
                        <IsHomeGroup>1</IsHomeGroup>

                        <Permissions>
                            <Permission>MANAGE_USERS</Permission>
                        </Permissions>
                    </Group>
                </UserGroups>
            </Info>
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
        $client->getUserGroups($query);

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
        self::assertEquals('getUserGroups', $packageAsXml->Method);
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
     * Test that getUserGroups() passes the correct input into the SmarterU API
     * when all required information is present and the query uses the employee
     * ID as the user identifier.
     */
    public function testGetUserProducesCorrectInputForEmployeeID() {
        $accountApi = 'account';
        $userApi = 'user';
        $employeeId = '1';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setEmployeeId($employeeId);

        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        /**
         * The response needs a body because getUserGroups() will try to
         * process the body once the response has been received, however this
         * test is about making sure the request made by getUserGroups() is
         * correct. The processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <UserGroups>
                    <Group>
                        <Name>My Group</Name>
                        <Identifier>My Group</Identifier>
                        <IsHomeGroup>1</IsHomeGroup>

                        <Permissions>
                            <Permission>MANAGE_USERS</Permission>
                        </Permissions>
                    </Group>
                </UserGroups>
            </Info>
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
        $client->getUserGroups($query);

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
        self::assertEquals('getUserGroups', $packageAsXml->Method);
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
     * Test that getUserGroups() throws an exception when the request results
     * in an HTTP error.
     */
    public function testGetUserThrowsExceptionWhenHTTPErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId('1');

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
        $client->getUserGroups($query);
    }

    /**
     * Test that getUserGroups() throws an exception when the SmarterU API
     * returns a fatal error.
     */
    public function testGetUserThrowsExceptionWhenFatalErrorReturned() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId('1');

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
        $client->getUserGroups($query);
    }

    /**
     * Test that getUserGroups() returns the expected output when the SmarterU API
     * returns a non-fatal error.
     */
    public function testGetUserGroupsHandlesNonFatalError() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId('1');
        
        $name1 = 'My Group';
        $identifier1 = '1';
        $isHomeGroup1 = '0';
        $permission1 = 'MANAGE_USERS';
        $permission2 = 'MANAGE_GROUP_USERS';
        $name2 = 'Other Group';
        $identifier2 = '2';
        $isHomeGroup2 = '1';
        $name3 = 'Third Group';
        $identifier3 = '3';
        $isHomeGroup3 = '0';
        
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <UserGroups>
                    <Group>
                        <Name>$name1</Name>
                        <Identifier>$identifier1</Identifier>
                        <IsHomeGroup>$isHomeGroup1</IsHomeGroup>
                        <Permissions>
                            <Permission>$permission1</Permission>
                            <Permission>$permission2</Permission>
                        </Permissions>
                    </Group>
                    <Group>
                        <Name>$name2</Name>
                        <Identifier>$identifier2</Identifier>
                        <IsHomeGroup>$isHomeGroup2</IsHomeGroup>
                        <Permissions>
                        </Permissions>
                    </Group>
                    <Group>
                        <Name>$name3</Name>
                        <Identifier>$identifier3</Identifier>
                        <IsHomeGroup>$isHomeGroup3</IsHomeGroup>
                        <Permissions>
                            <Permission>$permission1</Permission>
                        </Permissions>
                    </Group>
                </UserGroups>
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
    
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);
            
        // Make the request.
        $result = $client->getUserGroups($query);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];

        self::assertIsArray($response);
        self::assertCount(3, $response);
        foreach ($response as $group) {
            self::assertIsArray($group);
            self::assertArrayHasKey('Name', $group);
            self::assertArrayHasKey('Identifier', $group);
            self::assertArrayHasKey('IsHomeGroup', $group);
            self::assertArrayHasKey('Permissions', $group);
            self::assertIsArray($group['Permissions']);
        }
        self::assertEquals($response[0]['Name'], $name1);
        self::assertEquals($response[0]['Identifier'], $identifier1);
        self::assertEquals($response[0]['IsHomeGroup'], $isHomeGroup1);
        self::assertCount(2, $response[0]['Permissions']);
        self::assertContains($permission1, $response[0]['Permissions']);
        self::assertContains($permission2, $response[0]['Permissions']);
        self::assertEquals($response[1]['Name'], $name2);
        self::assertEquals($response[1]['Identifier'], $identifier2);
        self::assertEquals($response[1]['IsHomeGroup'], $isHomeGroup2);
        self::assertCount(0, $response[1]['Permissions']);
        self::assertEquals($response[2]['Name'], $name3);
        self::assertEquals($response[2]['Identifier'], $identifier3);
        self::assertEquals($response[2]['IsHomeGroup'], $isHomeGroup3);
        self::assertCount(1, $response[2]['Permissions']);
        self::assertContains($permission1, $response[2]['Permissions']);

        self::assertIsArray($errors);
        self::assertCount(2, $errors);
        self::assertArrayHasKey('Error1', $errors);
        self::assertEquals($errors['Error1'], 'Testing');
        self::assertArrayHasKey('Error2', $errors);
        self::assertEquals($errors['Error2'], '123');
    }

    /**
     * Test that getUserGroups() returns the expected output when the SmarterU API
     * returns a single Group with no errors.
     */
    public function testGetUserGroupsReturnsExpectedSingleGroup() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId('1');
        
        $name1 = 'My Group';
        $identifier1 = '1';
        $isHomeGroup1 = '0';
        $permission1 = 'MANAGE_USERS';
        $permission2 = 'MANAGE_GROUP_USERS';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <UserGroups>
                    <Group>
                        <Name>$name1</Name>
                        <Identifier>$identifier1</Identifier>
                        <IsHomeGroup>$isHomeGroup1</IsHomeGroup>
                        <Permissions>
                            <Permission>$permission1</Permission>
                            <Permission>$permission2</Permission>
                        </Permissions>
                    </Group>
                </UserGroups>
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
        $result = $client->getUserGroups($query);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];

        self::assertIsArray($response);
        self::assertCount(1, $response);
        foreach ($response as $group) {
            self::assertIsArray($group);
            self::assertArrayHasKey('Name', $group);
            self::assertArrayHasKey('Identifier', $group);
            self::assertArrayHasKey('IsHomeGroup', $group);
            self::assertArrayHasKey('Permissions', $group);
            self::assertIsArray($group['Permissions']);
        }
        self::assertEquals($response[0]['Name'], $name1);
        self::assertEquals($response[0]['Identifier'], $identifier1);
        self::assertEquals($response[0]['IsHomeGroup'], $isHomeGroup1);
        self::assertCount(2, $response[0]['Permissions']);
        self::assertContains($permission1, $response[0]['Permissions']);
        self::assertContains($permission2, $response[0]['Permissions']);
        self::assertIsArray($errors);
        self::assertCount(0, $errors);
    }

    /**
     * Test that getUserGroups() returns the expected output when the SmarterU API
     * returns multiple Groups without any errors.
     */
    public function testGetUserGroupsReturnsExpectedMultipleGroups() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetUserQuery())
            ->setId('1');
        
        $name1 = 'My Group';
        $identifier1 = '1';
        $isHomeGroup1 = '0';
        $permission1 = 'MANAGE_USERS';
        $permission2 = 'MANAGE_GROUP_USERS';
        $name2 = 'Other Group';
        $identifier2 = '2';
        $isHomeGroup2 = '1';
        $name3 = 'Third Group';
        $identifier3 = '3';
        $isHomeGroup3 = '0';
        
        $createdDate = '2022-07-29';
        $modifiedDate = '2022-07-30';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <UserGroups>
                    <Group>
                        <Name>$name1</Name>
                        <Identifier>$identifier1</Identifier>
                        <IsHomeGroup>$isHomeGroup1</IsHomeGroup>
                        <Permissions>
                            <Permission>$permission1</Permission>
                            <Permission>$permission2</Permission>
                        </Permissions>
                    </Group>
                    <Group>
                        <Name>$name2</Name>
                        <Identifier>$identifier2</Identifier>
                        <IsHomeGroup>$isHomeGroup2</IsHomeGroup>
                        <Permissions>
                        </Permissions>
                    </Group>
                    <Group>
                        <Name>$name3</Name>
                        <Identifier>$identifier3</Identifier>
                        <IsHomeGroup>$isHomeGroup3</IsHomeGroup>
                        <Permissions>
                            <Permission>$permission1</Permission>
                        </Permissions>
                    </Group>
                </UserGroups>
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
        $result = $client->getUserGroups($query);
        
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Response', $result);
        self::assertArrayHasKey('Errors', $result);

        $response = $result['Response'];
        $errors = $result['Errors'];

        self::assertIsArray($response);
        self::assertCount(3, $response);
        foreach ($response as $group) {
            self::assertIsArray($group);
            self::assertArrayHasKey('Name', $group);
            self::assertArrayHasKey('Identifier', $group);
            self::assertArrayHasKey('IsHomeGroup', $group);
            self::assertArrayHasKey('Permissions', $group);
            self::assertIsArray($group['Permissions']);
        }
        self::assertEquals($response[0]['Name'], $name1);
        self::assertEquals($response[0]['Identifier'], $identifier1);
        self::assertEquals($response[0]['IsHomeGroup'], $isHomeGroup1);
        self::assertCount(2, $response[0]['Permissions']);
        self::assertContains($permission1, $response[0]['Permissions']);
        self::assertContains($permission2, $response[0]['Permissions']);
        self::assertEquals($response[1]['Name'], $name2);
        self::assertEquals($response[1]['Identifier'], $identifier2);
        self::assertEquals($response[1]['IsHomeGroup'], $isHomeGroup2);
        self::assertCount(0, $response[1]['Permissions']);
        self::assertEquals($response[2]['Name'], $name3);
        self::assertEquals($response[2]['Identifier'], $identifier3);
        self::assertEquals($response[2]['IsHomeGroup'], $isHomeGroup3);
        self::assertCount(1, $response[2]['Permissions']);
        self::assertContains($permission1, $response[2]['Permissions']);

        self::assertIsArray($errors);
        self::assertCount(0, $errors);
    }
}
