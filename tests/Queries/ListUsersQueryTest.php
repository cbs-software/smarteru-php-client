<?php

/**
 * Contains Tests\SmarterU\Queries\ListUsersQueryTest
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/25
 */

declare(strict_types=1);

namespace Tests\SmarterU\Queries;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\ListUsersQuery;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Queries\Tags\MatchTag;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\Queries\ListUsersQuery;
 */
class ListUsersQueryTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $accountApi = 'account';
        $userApi = 'user';
        $page = 1;
        $pageSize = 50;
        $sortField = 'NAME';
        $sortOrder = 'ASC';
        $email = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue('phpunit@test.com');
        $employeeId = (new MatchTag())
            ->setMatchType('CONTAINS')
            ->setValue('2');
        $name = (new MatchTag())
            ->setMatchType('EXACT')
            ->setValue('Test User');
        $homeGroup = 'My Home Group';
        $groupName = 'Test Group';
        $userStatus = 'Active';
        $createdDateFrom = new DateTime('2022-07-25');
        $createdDateTo = new DateTime();
        $modifiedDateFrom = new DateTime('2022-07-20');
        $modifiedDateTo = new DateTime('2022-07-24');
        $createdDate = (new DateRangeTag())
            ->setDateFrom($createdDateFrom)
            ->setDateTo($createdDateTo);
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom($modifiedDateFrom)
            ->setDateTo($modifiedDateTo);
        $team1 = 'team1';
        $team2 = 'team2';
        $teams = [$team1, $team2];

        $query = (new ListUsersQuery())
            ->setAccountApi($accountApi)
            ->setUserApi($userApi)
            ->setPage($page)
            ->setPageSize($pageSize)
            ->setSortField($sortField)
            ->setSortOrder($sortOrder)
            ->setEmail($email)
            ->setEmployeeId($employeeId)
            ->setName($name)
            ->setHomeGroup($homeGroup)
            ->setGroupName($groupName)
            ->setUserStatus($userStatus)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setTeams($teams);

        self::assertEquals($accountApi, $query->getAccountApi());
        self::assertEquals($userApi, $query->getUserApi());
        self::assertEquals($page, $query->getPage());
        self::assertEquals($pageSize, $query->getPageSize());
        self::assertEquals($sortField, $query->getSortField());
        self::assertEquals($sortOrder, $query->getSortOrder());
        self::assertInstanceOf(MatchTag::class, $query->getEmail());
        self::assertEquals(
            $email->getMatchType(),
            $query->getEmail()->getMatchType()
        );
        self::assertEquals(
            $email->getValue(),
            $query->getEmail()->getValue()
        );
        self::assertInstanceOf(MatchTag::class, $query->getEmployeeId());
        self::assertEquals(
            $employeeId->getMatchType(),
            $query->getEmployeeId()->getMatchType()
        );
        self::assertEquals(
            $employeeId->getValue(),
            $query->getEmployeeId()->getValue()
        );
        self::assertInstanceOf(MatchTag::class, $query->getName());
        self::assertEquals(
            $name->getMatchType(),
            $query->getName()->getMatchType()
        );
        self::assertEquals(
            $name->getValue(),
            $query->getName()->getValue()
        );
        self::assertEquals($homeGroup, $query->getHomeGroup());
        self::assertEquals($groupName, $query->getGroupName());
        self::assertEquals($userStatus, $query->getUserStatus());
        self::assertInstanceOf(DateRangeTag::class, $query->getCreatedDate());
        self::assertEquals(
            $createdDateFrom,
            $query->getCreatedDate()->getDateFrom()
        );
        self::assertEquals(
            $createdDateTo,
            $query->getCreatedDate()->getDateTo()
        );
        self::assertInstanceOf(DateRangeTag::class, $query->getModifiedDate());
        self::assertEquals(
            $modifiedDateFrom,
            $query->getModifiedDate()->getDateFrom()
        );
        self::assertEquals(
            $modifiedDateTo,
            $query->getModifiedDate()->getDateTo()
        );
        self::assertCount(2, $query->getTeams());
        self::assertContains($team1, $query->getTeams());
        self::assertContains($team2, $query->getTeams());
    }
}
