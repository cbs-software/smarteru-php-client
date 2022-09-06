<?php

/**
 * Contains Tests\SmarterU\DataTypes\GroupPermissionsTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/21
 */

declare(strict_types=1);

namespace Tests\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\GroupPermissions;
use CBS\SmarterU\DataTypes\Permission;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\DataTypes\GroupPermissions;
 */
class GroupPermissionsTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $groupName = 'phpunit';
        $groupId = '12';

        $email = 'phpunit@test.com';
        $employeeId = '4';
        $homeGroup = true;

        $action = 'Add';

        $permission1 = (new Permission())
            ->setAction('Grant')
            ->setCode('MANAGE_GROUP');

        $permission2 = (new Permission())
            ->setAction('Deny')
            ->setCode('CREATE_COURSE');

        $permissions = [$permission1, $permission2];

        $groupPermission = (new GroupPermissions())
            ->setGroupName($groupName)
            ->setGroupId($groupId)
            ->setPermissions($permissions)
            ->setEmail($email)
            ->setHomeGroup($homeGroup)
            ->setAction($action);

        self::assertEquals($groupName, $groupPermission->getGroupName());
        self::assertEquals($groupId, $groupPermission->getGroupId());
        self::assertEquals($email, $groupPermission->getEmail());
        self::assertNull($groupPermission->getEmployeeId());
        self::assertEquals($homeGroup, $groupPermission->getHomeGroup());
        self::assertEquals($action, $groupPermission->getAction());
        self::assertCount(2, $groupPermission->getPermissions());
        self::assertContains($permission1, $groupPermission->getPermissions());
        self::assertContains($permission2, $groupPermission->getPermissions());

        // Test that the mutually exclusive properties are mutually exclusive.

        $groupPermission->setEmployeeId($employeeId);
        self::assertEquals($employeeId, $groupPermission->getEmployeeId());
        self::assertNull($groupPermission->getEmail());

        $groupPermission->setEmail($email);
        self::assertEquals($email, $groupPermission->getEmail());
        self::assertNull($groupPermission->getEmployeeId());
    }
}
