<?php

/**
 * Contains Tests\CBS\SmarterU\GrantPermissionsClientTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/12
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\ErrorCode;
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
 * Tests CBS\SmarterU\Client::grantPermissions().
 */
class GrantPermissionsClientTest extends TestCase {
    /**
     * Test that Client::grantPermissions() throws the expected exception when
     * the "$permissions" array contains a value that is not a string.
     */
    public function testGrantPermissionsThrowsExceptionWhenPermissionIsNotString() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $user = new User();
        $group = new Group();
        $permission = 1;
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$permissions" must be an array of strings.'
        );
        $client->grantPermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::grantPermissions() throws the expected exception when
     * the "$permissions" array contains a string that is not one of the valid
     * permissions defined by the SmarterU API.
     */
    public function testGrantPermissionsThrowsExceptionWhenPermissionIsInvalid() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $user = new User();
        $group = new Group();
        $permission = 'invalid';
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"' . $permission . '" is not one of the valid permissions.'
        );
        $client->grantPermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::grantPermissions() throws the expected exception when
     * the User who is being granted permissions does not have an email address
     * or an employee ID.
     */
    public function testGrantPermissionsThrowsExceptionWhenNoUserIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $user = new User();
        $group = new Group();
        $permission = 'MANAGE_USERS';
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'A User\'s permissions cannot be updated without either an email address or an employee ID.'
        );
        $client->grantPermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::grantPermissions() throws the expected exception when
     * the Group in which the User is being granted permissions does not have a
     * name or an ID.
     */
    public function testGrantPermissionsThrowsExceptionWhenNoGroupIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $user = (new User())
            ->setEmail('test@test.com')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true);
        $group = new Group();
        $permission = 'MANAGE_USERS';
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot assign permissions in a Group that has no name or ID.'
        );
        $client->grantPermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::grantPermissions() sends the expected input into the
     * SmarterU API when all required information is present and only one
     * permission is being granted.
     */
    public function testGrantPermissionsProducesCorrectInputSinglePermission() {
        $email = 'test@test.com';
        $user = (new User())
            ->setEmail($email)
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true);
        $group = (new Group())
            ->setName('My Group');

        $permission = 'MANAGE_USERS';

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Email>$email</Email>
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
        $response = $client->grantPermissions($user, $group, [$permission]);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->changePermissions(
            $accountApi,
            $userApi,
            $user,
            $group,
            [$permission],
            'Grant'
        );
        self::assertEquals($decodedBody, $expectedBody);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($response->getEmail(), $user->getEmail());
    }

    /**
     * Test that Client::grantPermissions() sends the expected input into the
     * SmarterU API when all required information is present and multiple
     * permissions are being granted.
     */
    public function testGrantPermissionsProducesCorrectInputMultiplePermissions() {
        $email = 'test@test.com';
        $user = (new User())
            ->setEmail($email)
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true);
        $group = (new Group())
            ->setName('My Group');

        $permission = 'MANAGE_USERS';
        $permission2 = 'INSTRUCTOR';
        $permission3 = 'PROCTOR';
        $permission4 = 'MARKER';

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <Email>$email</Email>
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
        $response = $client->grantPermissions(
            $user,
            $group,
            [$permission, $permission2, $permission3, $permission4]
        );

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->changePermissions(
            $accountApi,
            $userApi,
            $user,
            $group,
            [$permission, $permission2, $permission3, $permission4],
            'Grant'
        );
        self::assertEquals($decodedBody, $expectedBody);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($response->getEmail(), $user->getEmail());
    }

    /**
     * Test that Client::grantPermissions() throws the expected exception
     * when an HTTP error occurs and prevents the request from being made.
     */
    public function testGrantPermissionsThrowsExceptionWhenHttpErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $email = 'test@test.com';
        $name = 'My Group';
        $user = (new User())
            ->setEmail($email)
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true);
        $group = (new Group())
            ->setName($name);

        $permission = 'MANAGE_GROUP';

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
        $client->grantPermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::grantPermissions() throws the expected exception
     * when the SmarterU API returns a fatal error.
     */
    public function testGrantPermissionsThrowsExceptionWhenFatalErrorReturned() {
        $email = 'test@test.com';
        $name = 'My Group';
        $user = (new User())
            ->setEmail($email)
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true);
        $group = (new Group())
            ->setName($name);

        $permission = 'MANAGE_GROUP';

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $code = 'UT:99';
        $message = 'An error mocked for unit testing';
        $body = <<<XML
        <SmarterU>
            <Result>Failed</Result>
            <Info>
            </Info>
            <Errors>
                <Error>
                    <ErrorID>$code</ErrorID>
                    <ErrorMessage>$message</ErrorMessage>
                </Error>
            </Errors>
        </SmarterU>
        XML;

        // Set up the container to capture the request.
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
            $client->grantPermissions($user, $group, [$permission]);
        } catch (SmarterUException $error) {
            $exception = $error;
        }

        self::assertInstanceOf(SmarterUException::class, $exception);
        self::assertEquals(Client::SMARTERU_EXCEPTION_MESSAGE, $exception->getMessage());

        $errorCodes = $error->getErrorCodes();
        self::assertIsArray($errorCodes);
        self::assertCount(1, $errorCodes);

        $errorCode = reset($errorCodes);
        self::assertInstanceOf(ErrorCode::class, $errorCode);
        self::assertEquals($code, $errorCode->getErrorCode());
        self::assertEquals($message, $errorCode->getErrorMessage());
    }
}
