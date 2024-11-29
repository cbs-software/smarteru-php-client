<?php

/**
 * Contains Tests\CBS\SmarterU\RevokePermissionsClientTest.php
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
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
 * Tests CBS\SmarterU\Client::revokePermissions().
 */
class RevokePermissionsClientTest extends TestCase {
    /**
     * Test that Client::revokePermissions() throws the expected exception when
     * the "$permissions" array contains a value that is not a string.
     */
    public function testRevokePermissionsThrowsExceptionWhenPermissionIsNotString(): void {
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
        $client->revokePermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::revokePermissions() throws the expected exception when
     * the "$permissions" array contains a string that is not one of the valid
     * permissions defined by the SmarterU API.
     */
    public function testRevokePermissionsThrowsExceptionWhenPermissionIsInvalid(): void {
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
        $client->revokePermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::revokePermissions() throws the expected exception when
     * the User whose permissions are being revoked does not have an email address
     * or an employee ID.
     */
    public function testRevokePermissionsThrowsExceptionWhenNoUserIdentifier(): void {
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
        $client->revokePermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::revokePermissions() throws the expected exception when
     * the Group in which the User's permissions are being revoked does not have a
     * name or an ID.
     */
    public function testRevokePermissionsThrowsExceptionWhenNoGroupIdentifier(): void {
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
        $client->revokePermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::revokePermissions() sends the expected input into the
     * SmarterU API when all required information is present and only one
     * permission is being revoked.
     */
    public function testRevokePermissionsProducesCorrectInputSinglePermission(): void {
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
        $response = $client->revokePermissions($user, $group, [$permission]);

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
            'Deny'
        );
        self::assertEquals($decodedBody, $expectedBody);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($response->getEmail(), $user->getEmail());
    }

    /**
     * Test that Client::revokePermissions() sends the expected input into the
     * SmarterU API when all required information is present and multiple
     * permissions are being revoked.
     */
    public function testRevokePermissionsProducesCorrectInputMultiplePermissions(): void {
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
        $response = $client->revokePermissions(
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
            'Deny'
        );
        self::assertEquals($decodedBody, $expectedBody);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($response->getEmail(), $user->getEmail());
    }

    /**
     * Test that Client::revokePermissions() throws the expected exception
     * when an HTTP error occurs and prevents the request from being made.
     */
    public function testRevokePermissionsThrowsExceptionWhenHttpErrorOccurs(): void {
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
        $client->revokePermissions($user, $group, [$permission]);
    }

    /**
     * Test that Client::revokePermissions() throws the expected exception
     * when the SmarterU API returns a fatal error.
     */
    public function testRevokePermissionsThrowsExceptionWhenFatalErrorReturned(): void {
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
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logger->expects($this->once())->method('error')->with(
            $this->identicalTo('Failed to make request to SmarterU API. See context for request/response details.'),
            $this->identicalTo([
                'request' => "<?xml version=\"1.0\"?>\n<SmarterU><AccountAPI>********</AccountAPI><UserAPI>********</UserAPI><Method>updateUser</Method><Parameters><User><Identifier><Email>test@test.com</Email></Identifier><Info/><Profile/><Groups><Group><GroupName>My Group</GroupName><GroupAction>Add</GroupAction><GroupPermissions><Permission><Action>Deny</Action><Code>MANAGE_GROUP</Code></Permission></GroupPermissions></Group></Groups><Venues/><Wages/></User></Parameters></SmarterU>\n",
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
            $client->revokePermissions($user, $group, [$permission]);
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
