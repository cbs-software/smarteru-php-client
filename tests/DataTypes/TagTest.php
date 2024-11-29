<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\TagTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\Tag.
 */
class TagTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement(): void {
        $tagId = '2';
        $tagName = 'My Tag';
        $tagValues = 'This, is, my, tag, for, testing';

        $tag = (new Tag())
            ->setTagId($tagId)
            ->setTagName($tagName)
            ->setTagValues($tagValues);

        self::assertEquals($tagId, $tag->getTagId());
        self::assertEquals($tagValues, $tag->getTagValues());
        self::assertEquals($tagName, $tag->getTagName());
    }
}
