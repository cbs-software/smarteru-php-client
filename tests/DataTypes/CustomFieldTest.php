<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\CustomFieldTest.php.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/19
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\CustomField;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\CustomField.
 */
class CustomFieldTest extends TestCase {
    /**
     * Test agreement between getters and setters.
     */
    public function testAgreement() {
        $name = 'My Custom Field';
        $value = 'This is the field\'s value.';

        $customField = (new CustomField())
            ->setName($name)
            ->setValue($value);

        self::assertEquals($name, $customField->getName());
        self::assertEquals($value, $customField->getValue());
    }
}
