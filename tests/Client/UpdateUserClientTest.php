<?php

/**
 * Contains Tests\CBS\SmarterU\UpdateUserClientTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/25
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\ErrorCode;
use CBS\SmarterU\DataTypes\Timezone;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Queries\Tags\MatchTag;
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
 * Tests CBS\SmarterU\Client::updateUser().
 */

class UpdateUserClientTest extends TestCase {
    /**
     * A User to use for testing purposes.
     */
    protected User $user1;

    /**
     * Set up the test User.
     */
    public function setUp(): void {
        $this->user1 = (new User())
            ->setId('1')
            ->setEmail('phpunit@test.com')
            ->setEmployeeId('1')
            ->setGivenName('PHP')
            ->setSurname('Unit')
            ->setPassword('password')
            ->setTimezone(Timezone::fromProvidedName('EST'))
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit@test1.com')
            ->setAuthenticationType('External')
            ->setSupervisors(['supervisor1', 'supervisor2'])
            ->setOrganization('organization')
            ->setTeams(['team1', 'team2'])
            ->setLanguage('English')
            ->setStatus('Active')
            ->setTitle('Title')
            ->setDivision('division')
            ->setAllowFeedback(true)
            ->setPhonePrimary('555-555-5555')
            ->setPhoneAlternate('555-555-1234')
            ->setPhoneMobile('555-555-4321')
            ->setFax('555-555-5432')
            ->setWebsite('https://localhost')
            ->setAddress1('123 Main St')
            ->setAddress2('Apt. 1')
            ->setCity('Anytown')
            ->setProvince('Pennsylvania')
            ->setCountry('United States')
            ->setPostalCode('12345')
            ->setSendMailTo('Personal')
            ->setReceiveNotifications(true)
            ->setHomeGroup('My Home Group');
    }

    /**
     * Test that updateUser() passes the correct information into the API
     * when making the request if all required and optional information is
     * present.
     */
    public function testUpdateUserMakesCorrectAPICallWithAllInfo() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = (new User())
            ->setId('4')
            ->setEmail('phpunit4@test.com')
            ->setEmployeeId('4')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setTimezone(Timezone::fromProvidedName('EST'))
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit4@test1.com')
            ->setAuthenticationType('External')
            ->setSupervisors(['supervisor1', 'supervisor2'])
            ->setOrganization('organization')
            ->setTeams(['team1', 'team2'])
            ->setLanguage('English')
            ->setStatus('Inactive')
            ->setTitle('Title')
            ->setDivision('division')
            ->setAllowFeedback(true)
            ->setPhonePrimary('555-555-5551')
            ->setPhoneAlternate('555-555-1232')
            ->setPhoneMobile('555-555-4323')
            ->setFax('555-555-5434')
            ->setWebsite('https://localhost')
            ->setAddress1('125 Main St')
            ->setAddress2('Apt. 1')
            ->setCity('Anytown')
            ->setProvince('Pennsylvania')
            ->setCountry('United States')
            ->setPostalCode('12345')
            ->setSendMailTo('Personal')
            ->setReceiveNotifications(true)
            ->setHomeGroup('My Home Group');
        $client = new Client($accountApi, $userApi);

        /**
         * The response needs a body because updateUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by updateUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', 'test@test.com');
        $info->addChild('EmployeeID', '1');
        $xml->addChild('Errors');
        $body = $xml->asXML();

        // Set up the container to capture the request.
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $client->updateUser($user);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->updateUser(
            $accountApi,
            $userApi,
            $user
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that updateUser() passes the correct information into the API
     * when making the request if all required but no optional information is
     * present.
     */
    public function testUpdateUserMakesCorrectAPICallWithoutOptionalInfo() {
        $accountApi = 'account';
        $userApi = 'user';
        $oldEmail = 'phpunit@test.com';
        $oldEmployeeId = '12';
        $user = (new User())
            ->setId('4')
            ->setOldEmail($oldEmail)
            ->setEmail('phpunit4@test.com')
            ->setOldEmployeeId($oldEmployeeId)
            ->setEmployeeId('4')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(false);
        $client = new Client($accountApi, $userApi);

        /**
         * The response needs a body because updateUser() will try to process
         * the body once the response has been received, however this test is
         * about making sure the request made by updateUser() is correct. The
         * processing of the response will be tested further down.
         */
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', 'test@test.com');
        $info->addChild('EmployeeID', '1');
        $xml->addChild('Errors');
        $body = $xml->asXML();

        // Set up the container to capture the request.
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $client->updateUser($user);

        // XML translation clears out the old email/employee ID values so that
        // any future updateUser queries on the same User object do not
        // mistakenly use the old data to identify the User after the email
        // and/or employee ID have already been updated by a previous query.
        // They must be set again to produce the expected output below.
        $user->setOldEmail($oldEmail);
        $user->setOldEmployeeID($oldEmployeeId);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->updateUser(
            $accountApi,
            $userApi,
            $user
        );
        self::assertEquals($decodedBody, $expectedBody);
    }

    /**
     * Test that updateUser() throws an exception when the request results
     * in an HTTP error.
     */
    public function testUpdateUserThrowsExceptionWhenHTTPErrorOccurs() {
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
        $client->updateUser($this->user1);
    }

    /**
     * Test that updateUser() throws an exception when the SmarterU API
     * returns a fatal error, as indicated by the value of the <Result>
     * tag.
     */
    public function testUpdateUserThrowsExceptionWhenFatalErrorReturned() {
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
            $client->updateUser($this->user1);
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
     * Test that updateUser() returns the expected output when the SmarterU API
     * does not return any errors.
     */
    public function testUpdateUserReturnsExpectedResult() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;
        $xml = simplexml_load_string($xmlString);
        $xml->addChild('Result', 'Success');
        $info = $xml->addChild('Info');
        $info->addChild('Email', $this->user1->getEmail());
        $info->addChild('EmployeeID', $this->user1->getEmployeeId());
        $xml->addChild('Errors');
        $body = $xml->asXML();

        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $result = $client->updateUser($this->user1);

        self::assertInstanceOf(User::class, $result);
        self::assertEquals($result->getEmail(), $this->user1->getEmail());
        self::assertEquals(
            $result->getEmployeeId(),
            $this->user1->getEmployeeId()
        );
    }
}
