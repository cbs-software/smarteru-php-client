<?php

/**
 * Contains Tests\CBS\SmarterU\RemoveUsersFromGroupClientTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/12
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
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
 * Tests CBS\SmarterU\Client::removeUsersFromGroup().
 */
class RemoveUsersFromGroupClientTest extends TestCase {
    /**
     * Test that Client::removeUsersFromGroup() throws the expected exception
     * when the Group does not have a name or an ID.
     */
    public function testRemoveUsersFromGroupThrowsExceptionWhenNoGroupIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $user = new User();
        $group = new Group();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot add or remove users from a Group without a group name or ID.'
        );
        $client->removeUsersFromGroup([$user], $group);
    }

    /**
     * Test that Client::removeUsersFromGroup() throws the expected exception when
     * the "$users" array contains a value that is not an instace of User.
     */
    public function testRemoveUsersFromGroupThrowsExceptionWhenUsersNotInstanceOfUser() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $users = [1, 2, 3];
        $group = (new Group())
            ->setName('My Group');
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$users" must be an array of CBS\SmarterU\DataTypes\User instances'
        );
        $client->removeUsersFromGroup($users, $group);
    }

    /**
     * Test that Client::removeUsersFromGroup() throws the expected exception when
     * one of the provided Users does not have an email address or an employee
     * ID.
     */
    public function testRemoveUsersFromGroupThrowsExceptionWhenNoUserIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $user = new User();
        $group = (new Group())
            ->setName('My Group');
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'All Users being added to or removed from a Group must have an email address or employee ID.'
        );
        $client->removeUsersFromGroup([$user], $group);
    }

    /**
     * Test that Client::removeUsersFromGroup() sends the correct input into the
     * SmarterU API when all required information is present and only one
     * User is being added to the Group.
     */
    public function testRemoveUsersFromGroupProducesCorrectInputSingleUser() {
        $email = 'test@test.com';
        $name = 'My Group';
        $user = (new User())
            ->setEmail($email);
        $group = (new Group())
            ->setName($name);

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

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
        $client->removeUsersFromGroup([$user], $group);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->changeGroupMembers(
            $accountApi,
            $userApi,
            [$user],
            $group,
            'Remove'
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that Client::removeUsersFromGroup() sends the correct input into the
     * SmarterU API when all required information is present and multiple
     * Users are being added to the Group.
     */
    public function testRemoveUsersFromGroupProducesCorrectInputMultipleUsers() {
        $name = 'My Group';
        $user = (new User())
            ->setEmail('test@test.com');
        $user2 = (new User())
            ->setEmail('test2@test.com');
        $user3 = (new User())
            ->setEmail('test3@test.com');
        $group = (new Group())
            ->setName($name);

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

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
        $result = $client->removeUsersFromGroup([$user, $user2, $user3], $group);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->changeGroupMembers(
            $accountApi,
            $userApi,
            [$user, $user2, $user3],
            $group,
            'Remove'
        );
        self::assertEquals($decodedBody, $expectedBody);

        // Make sure the expected value is returned.
        self::assertInstanceOf(Group::class, $result);
        self::assertEquals($result->getName(), $group->getName());
    }

    /**
     * Test that Client::removeUsersFromGroup() throws the expected exception
     * when an HTTP error occurs and prevents the request from being made.
     */
    public function testRemoveUsersFromGroupThrowsExceptionWhenHttpErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $email = 'test@test.com';
        $name = 'My Group';
        $user = (new User())
            ->setEmail($email);
        $group = (new Group())
            ->setName($name);

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
        $client->removeUsersFromGroup([$user], $group);
    }

    /**
     * Test that Client::removeUsersFromGroup() throws the expected exception
     * when the SmarterU API returns a fatal error.
     */
    public function testRemoveUsersFromGroupThrowsExceptionWhenFatalErrorReturned() {
        $email = 'test@test.com';
        $name = 'My Group';
        $user = (new User())
            ->setEmail($email);
        $group = (new Group())
            ->setName($name);

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
            <Result>Failed</Result>
            <Info>
            </Info>
            <Errors>
                <Error>
                    <ErrorID>1</ErrorID>
                    <ErrorMessage>An error has occurred.</ErrorMessage>
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
        self::expectException(SmarterUException::class);
        self::expectExceptionMessage(
            'SmarterU rejected the request due to the following error(s): 1: An error has occurred'
        );
        $client->removeUsersFromGroup([$user], $group);
    }
}
