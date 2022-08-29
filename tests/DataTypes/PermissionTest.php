<?php

/**
 * Contains Tests\SmarterU\DataTypes\PermissionTest
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/21
 */

declare(strict_types=1);

namespace Tests\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\Permission;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\DataTypes\Permission;
 */
class PermissionTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $action = 'Grant';
        $code = 'MANAGE_GROUP';
        
        $permission = (new Permission())
            ->setAction($action)
            ->setCode($code);

        self::assertEquals($action, $permission->getAction());
        self::assertEquals($code, $permission->getCode());
    }
}
