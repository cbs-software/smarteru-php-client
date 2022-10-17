<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\RequestExternalAuthorizationXMLTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/10/10
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::requestExternalAuthorization().
 */
class RequestExternalAuthorizationXMLTest extends TestCase {
    /**
     * Tests that the XML generation process for a requestExternalAuthorization
     * request throws an exception if the array provided as a parameter does
     * not have an "Email" key or an "EmployeeID" key.
     */
    public function testRequestExternalAuthorizationThrowsExceptionWhenNoIdentifier() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot request external authorization without an email address or employee ID'
        );
        $xml = $xmlGenerator->requestExternalAuthorization(
            $accountApi,
            $userApi,
            []
        );
    }

    /**
     * Tests that the XML generation process for a requestExternalAuthorization
     * request produces the expected output when the user is identified by
     * their email address.
     */
    public function testRequestExternalAuthorizationProducesExpectedOutputForEmail() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $email = 'test@test.com';
        $xml = $xmlGenerator->requestExternalAuthorization(
            $accountApi,
            $userApi,
            ['Email' => $email]
        );
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);

        self::assertEquals($xml->getName(), 'SmarterU');
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('requestExternalAuthorization', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Security', $parameters);
        $identifier = [];
        foreach ($xml->Parameters->Security->children() as $tag) {
            $identifier[] = $tag->getName();
        }
        self::assertCount(1, $identifier);
        self::assertContains('Email', $identifier);
        self::assertEquals($email, $xml->Parameters->Security->Email);
    }

    /**
     * Tests that the XML generation process for a requestExternalAuthorization
     * request produces the expected output when the user is identified by
     * their employee ID.
     */
    public function testRequestExternalAuthorizationProducesExpectedOutputForEmployeeId() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $employeeId = '1';
        $xml = $xmlGenerator->requestExternalAuthorization(
            $accountApi,
            $userApi,
            ['EmployeeID' => $employeeId]
        );
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);

        self::assertEquals($xml->getName(), 'SmarterU');
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('requestExternalAuthorization', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Security', $parameters);
        $identifier = [];
        foreach ($xml->Parameters->Security->children() as $tag) {
            $identifier[] = $tag->getName();
        }
        self::assertCount(1, $identifier);
        self::assertContains('EmployeeID', $identifier);
        self::assertEquals($employeeId, $xml->Parameters->Security->EmployeeID);
    }
}
