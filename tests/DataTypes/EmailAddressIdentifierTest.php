<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\EmailAddressIdentifierTest.php.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\EmailAddressIdentifier;
use CBS\SmarterU\DataTypes\IUserIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\EmailAddressIdentifier.
 */
class EmailAddressIdentifierTest extends TestCase {
    /**
     * Test that EmailAddressIdentifier implements IUserIdentifier.
     */
    public function testImplementsInterface(): void {
        $identifier = new EmailAddressIdentifier('user@example.com');
        self::assertInstanceOf(IUserIdentifier::class, $identifier);
    }

    /**
     * Test that getUserIdentifier returns the email address passed to the constructor.
     */
    public function testGetUserIdentifierReturnsEmailAddress(): void {
        $email = 'user@example.com';
        $identifier = new EmailAddressIdentifier($email);
        self::assertSame($email, $identifier->getUserIdentifier());
    }

    /**
     * Test that getApiType returns 'Email'.
     */
    public function testGetApiTypeReturnsEmail(): void {
        $identifier = new EmailAddressIdentifier('user@example.com');
        self::assertSame('Email', $identifier->getApiType());
    }
}
