<?php

/**
 * Contains Tests\SmarterU\Queries\GetUserQueryTest
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/25
 */

declare(strict_types=1);

namespace Tests\SmarterU\Queries;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\GetUserQuery;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\Queries\GetUserQuery;
 */
class GetUserQueryTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $accountApi = 'account';
        $userApi = 'user';
        $id = '12';
        $email = 'test@phpunit.com';
        $employeeId = '13';
        $method = 'method';
        $query = (new GetUserQuery())
            ->setAccountApi($accountApi)
            ->setUserApi($userApi)
            ->setMethod($method)
            ->setId($id);

        self::assertEquals($accountApi, $query->getAccountApi());
        self::assertEquals($userApi, $query->getUserApi());
        self::assertEquals($id, $query->getId());
        self::assertEquals($method, $query->getMethod());
        self::assertNull($query->getEmail());
        self::assertNull($query->getEmployeeId());

        /**
         * The three user identifiers are mutually exclusive, so calling the
         * setter for one should set the other two to null.
         */
        $query->setEmail($email);
        self::assertEquals($email, $query->getEmail());
        self::assertNull($query->getId());
        self::assertNull($query->getEmployeeId());

        $query->setEmployeeId($employeeId);
        self::assertEquals($employeeId, $query->getEmployeeId());
        self::assertNull($query->getId());
        self::assertNull($query->getEmail());
    }
}
