<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\EmployeeIdIdentifierTest.php.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\EmployeeIdIdentifier;
use CBS\SmarterU\DataTypes\IUserIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\EmployeeIdIdentifier.
 */
class EmployeeIdIdentifierTest extends TestCase {
    /**
     * Test that EmployeeIdIdentifier implements IUserIdentifier.
     */
    public function testImplementsInterface(): void {
        $identifier = new EmployeeIdIdentifier('EMP001');
        self::assertInstanceOf(IUserIdentifier::class, $identifier);
    }

    /**
     * Test that getUserIdentifier returns the employee ID passed to the constructor.
     */
    public function testGetUserIdentifierReturnsEmployeeId(): void {
        $employeeId = 'EMP001';
        $identifier = new EmployeeIdIdentifier($employeeId);
        self::assertSame($employeeId, $identifier->getUserIdentifier());
    }

    /**
     * Test that getApiType returns 'EmployeeID'.
     */
    public function testGetApiTypeReturnsEmployeeID(): void {
        $identifier = new EmployeeIdIdentifier('EMP001');
        self::assertSame('EmployeeID', $identifier->getApiType());
    }
}
