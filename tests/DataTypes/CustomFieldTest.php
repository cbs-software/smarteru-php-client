<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\CustomFieldTest.php.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
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
    public function testAgreement(): void {
        $name = 'My Custom Field';
        $value = 'This is the field\'s value.';

        $customField = (new CustomField())
            ->setName($name)
            ->setValue($value);

        self::assertEquals($name, $customField->getName());
        self::assertEquals($value, $customField->getValue());
    }
}
