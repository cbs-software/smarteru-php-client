<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\GetUserXMLTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/02
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\GetUserQuery;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::getUser().
 */
class GetUserXMLTest extends TestCase {
    /**
     * Tests that XML generation throws the expected exception when the
     * required user identifier is not set.
     */
    public function testExceptionIsThrownWhenUserIdentifierNotSet() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = (new GetUserQuery())
            ->setMethod('getUser');

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'User identifier must be specified when creating a GetUserQuery.'
        );
        $xml = $xmlGenerator->getUser($accountApi, $userApi, $query);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the user is identified by their ID.
     */
    public function testXMLGeneratedAsExpectedWithId() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $id = '12';
        $method = 'getUser';
        $query = (new GetUserQuery())
            ->setMethod($method)
            ->setId($id);

        $xml = $xmlGenerator->getUser($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        self::assertCount(4, $xml);
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getUser', $xml->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        self::assertEquals('User', $xml->Parameters->User->getName());

        $users = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $users[] = $user->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('ID', $users);
        self::assertEquals($id, $xml->Parameters->User->ID);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the user is identified by their email.
     */
    public function testXMLGeneratedAsExpectedWithEmail() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $email = 'phpunit@test.com';
        $method = 'getUser';
        $query = (new GetUserQuery())
            ->setMethod($method)
            ->setEmail($email);

        $xml = $xmlGenerator->getUser($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        self::assertCount(4, $xml);
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getUser', $xml->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        self::assertEquals('User', $xml->Parameters->User->getName());

        $users = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $users[] = $user->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('Email', $users);
        self::assertEquals($email, $xml->Parameters->User->Email);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the user is identified by their employee ID.
     */
    public function testXMLGeneratedAsExpectedWithEmployeeId() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $employeeId = '12';
        $method = 'getUser';
        $query = (new GetUserQuery())
            ->setMethod($method)
            ->setEmployeeId($employeeId);

        $xml = $xmlGenerator->getUser($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        self::assertCount(4, $xml);
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getUser', $xml->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        self::assertEquals('User', $xml->Parameters->User->getName());

        $users = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $users[] = $user->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('EmployeeID', $users);
        self::assertEquals(
            $employeeId,
            $xml->Parameters->User->EmployeeID
        );
    }
}
