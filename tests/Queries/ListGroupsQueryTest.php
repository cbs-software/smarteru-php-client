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
}
