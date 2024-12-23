<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\ListGroupsXMLTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\ListGroupsQuery;
use CBS\SmarterU\Queries\Tags\MatchTag;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::getGroup().
 */
class ListGroupsXMLTest extends TestCase {
    /**
     * Tests that XML generation throws the expected exception when the
     * required tag identifier is not set.
     */
    public function testExceptionIsThrownWhenTagIdentifierNotSet(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $tag = new Tag();
        $query = (new ListGroupsQuery())
            ->setTags([$tag]);

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Tags must include a tag identifier when creating a ListGroups query.'
        );
        $xml = $xmlGenerator->listGroups($accountApi, $userApi, $query);
    }

    /**
     * Tests that XML generation produces the expected result when the query
     * does not contain any information (i.e. do not filter the results, list
     * all groups).
     */
    public function testXMLGeneratedAsExpectedWhenQueryIsEmpty(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = new ListGroupsQuery();

        $xml = $xmlGenerator->listGroups($accountApi, $userApi, $query);
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
        self::assertEquals('listGroups', $xmlAsElement->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xmlAsElement->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);

        $group = [];
        foreach ($xmlAsElement->Parameters->Group->children() as $groupTag) {
            $group[] = $groupTag->getName();
        }
        self::assertCount(1, $group);
        self::assertContains('Filters', $group);
        $filters = [];
        foreach ($xmlAsElement->Parameters->Group->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(0, $filters);
    }

    /**
     * Tests that XML generation produces the expected result when all
     * information is present.
     */
    public function testXMLGeneratedAsExpectedWithAllInfo(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
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
            ->setGroupName($groupName)
            ->setGroupStatus($groupStatus)
            ->setTags($tags);

        $xml = $xmlGenerator->listGroups($accountApi, $userApi, $query);
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
        self::assertEquals('listGroups', $xmlAsElement->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xmlAsElement->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);

        $group = [];
        foreach ($xmlAsElement->Parameters->Group->children() as $groupTag) {
            $group[] = $groupTag->getName();
        }
        self::assertCount(1, $group);
        self::assertContains('Filters', $group);
        $filters = [];
        foreach ($xmlAsElement->Parameters->Group->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(3, $filters);
        self::assertContains('GroupName', $filters);
        $groupNameTag = [];
        foreach ($xmlAsElement->Parameters->Group->Filters->GroupName->children() as $tag) {
            $groupNameTag[] = $tag->getName();
        }
        self::assertCount(2, $groupNameTag);
        self::assertContains('MatchType', $groupNameTag);
        self::assertEquals(
            $query->getGroupName()->getMatchType(),
            $xmlAsElement->Parameters->Group->Filters->GroupName->MatchType
        );
        self::assertContains('Value', $groupNameTag);
        self::assertEquals(
            $query->getGroupName()->getValue(),
            $xmlAsElement->Parameters->Group->Filters->GroupName->Value
        );
        self::assertContains('GroupStatus', $filters);
        self::assertEquals(
            $query->getGroupStatus(),
            $xmlAsElement->Parameters->Group->Filters->GroupStatus
        );
        self::assertContains('Tags2', $filters);
        $tags = [];
        foreach ($xmlAsElement->Parameters->Group->Filters->Tags2->children() as $tag) {
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
}
