<?php

/**
 * Contains Tests\CBS\SmarterU\Queries\ListGroupsQueryTest
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/09
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Queries;

use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\ListGroupsQuery;
use CBS\SmarterU\Queries\Tags\MatchTag;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Queries\ListGroupsQuery;
 */
class ListGroupsQueryTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
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
            ->setAccountApi($accountApi)
            ->setUserApi($userApi)
            ->setGroupName($groupName)
            ->setGroupStatus($groupStatus)
            ->setTags($tags);

        self::assertEquals($accountApi, $query->getAccountApi());
        self::assertEquals($userApi, $query->getUserApi());
        self::assertInstanceOf(MatchTag::class, $query->getGroupName());
        self::assertEquals($groupName->getMatchType(), $query->getGroupName()->getMatchType());
        self::assertEquals($groupName->getValue(), $query->getGroupName()->getValue());
        self::assertEquals($groupStatus, $query->getGroupStatus());
        self::assertIsArray($query->getTags());
        self::assertCount(2, $query->getTags());
        self::assertContains($tag1, $query->getTags());
        self::assertContains($tag2, $query->getTags());
    }

    /**
     * Tests that XML generation throws the expected exception when the
     * required tag identifier is not set.
     */
    public function testExceptionIsThrownWhenTagIdentifierNotSet() {
        $accountApi = 'account';
        $userApi = 'user';
        $tag = new Tag();
        $query = (new ListGroupsQuery())
            ->setTags([$tag]);
        
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Tags must include a tag identifier when creating a ListGroups query.'
        );
        $xml = $query->toXml($accountApi, $userApi);
    }

    /**
     * Tests that XML generation produces the expected result when the query
     * does not contain any information (i.e. do not filter the results, list
     * all groups).
     */
    public function testXMLGeneratedAsExpectedWhenQueryIsEmpty() {
        $accountApi = 'account';
        $userApi = 'user';
        $query = new ListGroupsQuery();

        $xml = $query->toXml($accountApi, $userApi);
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
    public function testXMLGeneratedAsExpectedWithAllInfo() {
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

        $xml = $query->toXml($accountApi, $userApi);
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
