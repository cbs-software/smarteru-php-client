<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\GetGroupXMLTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/07
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\GetGroupQuery;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::getGroup().
 */
class GetGroupXMLTest extends TestCase {
    /**
     * Tests that XML generation throws the expected exception when the
     * required group identifier is not set.
     */
    public function testExceptionIsThrownWhenGroupIdentifierNotSet() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = (new GetGroupQuery())
            ->setAccountApi($accountApi)
            ->setUserApi($userApi);

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Group identifier must be specified when creating a GetGroupQuery.'
        );
        $xml = $xmlGenerator->getGroup($accountApi, $userApi, $query);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the group is identified by its name.
     */
    public function testXMLGeneratedAsExpectedForGroupName() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $name = 'My Group';
        $query = (new GetGroupQuery())
            ->setName($name);

        $xml = $xmlGenerator->getGroup($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xmlAsElement = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xmlAsElement->getName());
        self::assertCount(4, $xmlAsElement);
        $elements = [];
        foreach ($xmlAsElement->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xmlAsElement->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xmlAsElement->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getGroup', $xmlAsElement->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xmlAsElement->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);

        $groupTag = [];
        foreach ($xmlAsElement->Parameters->Group->children() as $group) {
            $groupTag[] = $group->getName();
        }
        self::assertCount(1, $groupTag);
        self::assertContains('Name', $groupTag);
        self::assertEquals($name, $xmlAsElement->Parameters->Group->Name);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the group is identified by its ID.
     */
    public function testXMLGeneratedAsExpectedForGroupID() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $groupId = '1';
        $query = (new GetGroupQuery())
            ->setGroupId($groupId);

        $xml = $xmlGenerator->getGroup($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xmlAsElement = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xmlAsElement->getName());
        self::assertCount(4, $xmlAsElement);
        $elements = [];
        foreach ($xmlAsElement->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xmlAsElement->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xmlAsElement->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getGroup', $xmlAsElement->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xmlAsElement->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);

        $groupTag = [];
        foreach ($xmlAsElement->Parameters->Group->children() as $group) {
            $groupTag[] = $group->getName();
        }
        self::assertCount(1, $groupTag);
        self::assertContains('GroupID', $groupTag);
        self::assertEquals($groupId, $xmlAsElement->Parameters->Group->GroupID);
    }
}
