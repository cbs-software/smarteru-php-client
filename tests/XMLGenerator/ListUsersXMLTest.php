<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\ListUsersXMLTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\ListUsersQuery;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Queries\Tags\MatchTag;
use CBS\SmarterU\XMLGenerator;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::listUsers().
 */
class ListUsersXMLTest extends TestCase {
    /**
     * Tests that XML generation produces the expected result when all
     * required and optional information is present.
     */
    public function testEmittedXMLIsAsExpectedWithAllInfo() {
        $xmlGenerator = new XMLGenerator();
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

        $xml = $xmlGenerator->listUsers($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        self::assertCount(4, $xml);
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('listUsers', $xml->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        $userInfo = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $userInfo[] = $user->getName();
        }
        self::assertCount(5, $userInfo);
        self::assertContains('Page', $userInfo);
        self::assertEquals(
            $page,
            (int) $xml->Parameters->User->Page
        );
        self::assertContains('PageSize', $userInfo);
        self::assertEquals(
            $pageSize,
            (int) $xml->Parameters->User->PageSize
        );
        self::assertContains('SortField', $userInfo);
        self::assertEquals(
            $sortField,
            $xml->Parameters->User->SortField
        );
        self::assertContains('SortOrder', $userInfo);
        self::assertEquals(
            $sortOrder,
            $xml->Parameters->User->SortOrder
        );
        self::assertContains('Filters', $userInfo);
        $filters = [];
        foreach ($xml->Parameters->User->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(7, $filters);
        self::assertContains('Users', $filters);
        self::assertContains('HomeGroup', $filters);
        self::assertContains('GroupName', $filters);
        self::assertContains('UserStatus', $filters);
        self::assertContains('CreatedDate', $filters);
        self::assertContains('ModifiedDate', $filters);
        self::assertContains('Teams', $filters);
        $users = [];
        foreach ($xml->Parameters->User->Filters->Users->children() as $user) {
            $users[] = $user->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('UserIdentifier', $users);
        $userIdentifiers = [];
        foreach ($xml->Parameters->User->Filters->Users->UserIdentifier->children() as $identifier) {
            $userIdentifiers[] = $identifier->getName();
        }
        self::assertCount(3, $userIdentifiers);
        self::assertContains('Email', $userIdentifiers);
        self::assertEquals(
            $email->getMatchType(),
            $xml->Parameters->User->Filters->Users->UserIdentifier->Email->MatchType
        );
        self::assertEquals(
            $email->getValue(),
            $xml->Parameters->User->Filters->Users->UserIdentifier->Email->Value
        );
        self::assertContains('EmployeeID', $userIdentifiers);
        self::assertEquals(
            $employeeId->getMatchType(),
            $xml->Parameters->User->Filters->Users->UserIdentifier->EmployeeID->MatchType
        );
        self::assertEquals(
            $employeeId->getValue(),
            $xml->Parameters->User->Filters->Users->UserIdentifier->EmployeeID->Value
        );
        self::assertContains('Name', $userIdentifiers);
        self::assertEquals(
            $name->getMatchType(),
            $xml->Parameters->User->Filters->Users->UserIdentifier->Name->MatchType
        );
        self::assertEquals(
            $name->getValue(),
            $xml->Parameters->User->Filters->Users->UserIdentifier->Name->Value
        );
        self::assertEquals(
            $homeGroup,
            $xml->Parameters->User->Filters->HomeGroup
        );
        self::assertEquals(
            $groupName,
            $xml->Parameters->User->Filters->GroupName
        );
        self::assertEquals(
            $userStatus,
            $xml->Parameters->User->Filters->UserStatus
        );
        $createdDateTag = [];
        foreach ($xml->Parameters->User->Filters->CreatedDate->children() as $date) {
            $createdDateTag[] = $date->getName();
        }
        self::assertCount(2, $createdDateTag);
        self::assertContains('CreatedDateFrom', $createdDateTag);
        self::assertContains('CreatedDateTo', $createdDateTag);
        self::assertEquals(
            $createdDate->getDateFrom()->format('d/m/Y'),
            $xml->Parameters->User->Filters->CreatedDate->CreatedDateFrom
        );
        self::assertEquals(
            $createdDate->getDateTo()->format('d/m/Y'),
            $xml->Parameters->User->Filters->CreatedDate->CreatedDateTo
        );
        $modifiedDateTag = [];
        foreach ($xml->Parameters->User->Filters->ModifiedDate->children() as $date) {
            $modifiedDateTag[] = $date->getName();
        };
        self::assertCount(2, $modifiedDateTag);
        self::assertContains('ModifiedDateFrom', $modifiedDateTag);
        self::assertContains('ModifiedDateTo', $modifiedDateTag);
        self::assertEquals(
            $modifiedDate->getDateFrom()->format('d/m/Y'),
            $xml->Parameters->User->Filters->ModifiedDate->ModifiedDateFrom
        );
        self::assertEquals(
            $modifiedDate->getDateTo()->format('d/m/Y'),
            $xml->Parameters->User->Filters->ModifiedDate->ModifiedDateTo
        );
        $teamName = [];
        foreach ($xml->Parameters->User->Filters->Teams->children() as $team) {
            $teamName[] = $team->getName();
        }
        self::assertCount(2, $teamName);
        self::assertContains('TeamName', $teamName);
        $teamNames = [];
        foreach ($xml->Parameters->User->Filters->Teams->TeamName as $team) {
            $teamNames[] = $team;
        }
        self::assertEquals($team1, $teamNames[0]);
        self::assertEquals($team2, $teamNames[1]);
    }

    /**
     * Tests that XML generation produces the expected result when all
     * required information but no optional information is present.
     */
    public function testEmittedXMLIsAsExpectedWithoutOptionalInfo() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = new ListUsersQuery();

        $xml = $xmlGenerator->listUsers($accountApi, $userApi, $query);
        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        self::assertCount(4, $xml);
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('listUsers', $xml->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('User', $parameters);
        $userInfo = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $userInfo[] = $user->getName();
        }
        self::assertCount(3, $userInfo);
        self::assertContains('Page', $userInfo);
        self::assertEquals(
            $query->getPage(),
            (int) $xml->Parameters->User->Page
        );
        self::assertContains('PageSize', $userInfo);
        self::assertEquals(
            $query->getPageSize(),
            (int) $xml->Parameters->User->PageSize
        );
        self::assertContains('Filters', $userInfo);
        $filters = [];
        foreach ($xml->Parameters->User->Filters->children() as $filter) {
            $filters[] = $filter->getName();
        }
        self::assertCount(1, $filters);
        self::assertContains('UserStatus', $filters);
        self::assertEquals(
            $query->getUserStatus(),
            $xml->Parameters->User->Filters->UserStatus
        );
    }
}
