<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\TagTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/03
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
    public function testAgreement() {
        $tagId = '2';
        $tagName = 'My Tag';
        $tagValues = 'This, is, my, tag, for, testing';

        $tag = (new Tag())
            ->setTagId($tagId)
            ->setTagValues($tagValues);

        self::assertEquals($tagId, $tag->getTagId());
        self::assertEquals($tagValues, $tag->getTagValues());
        self::assertNull($tag->getTagName());

        // Test that tagName and tagId are mutually exclusive, so that
        // when one is set the other becomes null.
        $tag->setTagName($tagName);

        self::assertEquals($tagName, $tag->getTagName());
        self::assertNull($tag->getTagId());

        $tag->setTagId($tagId);

        self::assertEquals($tagId, $tag->getTagId());
        self::assertNull($tag->getTagName());
    }
}
