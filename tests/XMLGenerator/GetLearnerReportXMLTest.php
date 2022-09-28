<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\GetLearnerReportXMLTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/26
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\CustomField;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Queries\GetLearnerReportQuery;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use DateTime;
use SimpleXMLElement;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::getLearnerReport()
 */
class GetLearnerReportXMLTest extends TestCase {
    /**
     * Test that XMLGenerator::getLearnerReport() throws an exception when the
     * query doesn't have either a group status or a list of group names.
     */
    public function testGetLearnerReportThrowsExceptionWhenNoGroupIdentifier() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1');

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'GetLearnerReport must contain either a Group status or a list of Group names.'
        );
        $xmlGenerator->getLearnerReport($accountApi, $userApi, $query);
    }

    /**
     * Test that XMLGenerator::getLearnerReport() throws an exception when the
     * query contains a Tag that doesn't have a name or an ID.
     */
    public function testGetLearnerReportThrowsExceptionWhenNoTagIdentifier() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $tag = new Tag();
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setGroupTags([$tag]);

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Tags must have either an ID or a name.'
        );
        $xmlGenerator->getLearnerReport($accountApi, $userApi, $query);
    }

    /**
     * Test that XMLGenerator::getLearnerReport() throws an exception when the
     * query doesn't have either a user status or a list of user identifiers.
     */
    public function testGetLearnerReportThrowsExceptionWhenNoUserIdentifier() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active');

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'GetLearnerReport requires either a User Status or User Identifiers.'
        );
        $xmlGenerator->getLearnerReport($accountApi, $userApi, $query);
    }

    /**
     * Test that XMLGenerator::getLearnerReport() produces the expected output
     * when all required values are present, but all optional values are blank.
     */
    public function testGetLearnerReportProducesExpectedOutputWithoutOptionalInfo() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setUserStatus('Active');

        $xml = $xmlGenerator->getLearnerReport($accountApi, $userApi, $query);
        self::assertIsString($xml);

        $xml = simplexml_load_string($xml);

        self::assertEquals($xml->getName(), 'SmarterU');
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getLearnerReport', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(3, $parameters);
        self::assertContains('Report', $parameters);
        $report = [];
        foreach ($xml->Parameters->Report->children() as $reportTag) {
            $report[] = $reportTag->getName();
        }
        self::assertCount(3, $report);
        self::assertContains('Page', $report);
        self::assertEquals(
            (int) $xml->Parameters->Report->Page,
            $query->getPage()
        );
        self::assertContains('PageSize', $report);
        self::assertEquals(
            (int) $xml->Parameters->Report->PageSize,
            $query->getPageSize()
        );
        self::assertContains('Filters', $report);
        $filters = [];
        foreach ($xml->Parameters->Report->Filters->children() as $tag) {
            $filters[] = $tag->getName();
        }
        self::assertCount(4, $filters);
        self::assertContains('EnrollmentID', $filters);
        self::assertEquals(
            $query->getEnrollmentId(),
            $xml->Parameters->Report->Filters->EnrollmentID
        );
        self::assertContains('Groups', $filters);
        $groups = [];
        foreach ($xml->Parameters->Report->Filters->Groups->children() as $tag) {
            $groups[] = $tag->getName();
        }
        self::assertCount(1, $groups);
        self::assertContains('GroupStatus', $groups);
        self::assertEquals(
            $query->getGroupStatus(),
            $xml->Parameters->Report->Filters->Groups->GroupStatus
        );
        self::assertContains('Enrollments', $filters);
        self::assertCount(0, $xml->Parameters->Report->Filters->Enrollments->children());
        self::assertContains('Users', $filters);
        $users = [];
        foreach ($xml->Parameters->Report->Filters->Users->children() as $tag) {
            $users[] = $tag->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('UserStatus', $users);
        self::assertEquals(
            $query->getUserStatus(),
            $xml->Parameters->Report->Filters->Users->UserStatus
        );
        self::assertContains('Columns', $parameters);
        self::assertCount(0, $xml->Parameters->CustomFields->children());
        self::assertContains('CustomFields', $parameters);
        self::assertCount(0, $xml->Parameters->CustomFields->children());
    }

    /**
     * Test that XMLGenerator::getLearnerReport() produces the expected output
     * when all required and optional values are present.
     */
    public function testGetLearnerReportProducesExpectedOutputWithAllInfo() {
        $enrollmentId = '1';
        $groupNames = ['My Group', 'My Other Group'];
        $tag1 = (new Tag())
            ->setTagName('My Tag')
            ->setTagValues('Tag 1\'s Values');
        $tag2 = (new Tag())
            ->setTagId('3')
            ->setTagValues('Tag 2\'s Values');
        $groupTags = [$tag1, $tag2];
        $learningModuleStatus = 'Active';
        $learningModuleNames = ['Course 1', 'Course 2'];
        $enrollmentStatuses = ['Enrolled', 'In Progress', 'Completed'];
        $completedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-07-31'));
        $completedDate2 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-01'))
            ->setDateTo(new DateTime('2022-09-30'));
        $completedDates = [$completedDate1, $completedDate2];
        $dueDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-08-01'))
            ->setDateTo(new DateTime('2022-09-30'));
        $dueDates = [$dueDate1];
        $enrolledDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-01-01'))
            ->setDateTo(new DateTime('2022-06-30'));
        $enrolledDates = [$enrolledDate1];
        $gracePeriodDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-10-01'))
            ->setDateTo(new DateTime('2022-10-07'));
        $gracePeriodDates = [$gracePeriodDate1];
        $lastAccessedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-14'))
            ->setDateTo(new DateTime('2022-09-21'));
        $lastAccessedDates = [$lastAccessedDate1];
        $startedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-01'))
            ->setDateTo(new DateTime('2022-09-22'));
        $startedDates = [$startedDate1];
        $createdDate = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-07-02'));
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-22'))
            ->setDateTo(new DateTime());
        $userEmailAddresses = ['test@test.com', 'test2@test.com'];
        $userEmployeeIds = ['4', '5', '6'];
        $columns = ['ALTERNATE_EMAIL', 'COMPLETED_DATE'];
        $customField1 = (new CustomField())
            ->setName('My Custom Field');
        $customField2 = (new CustomField())
            ->setName('Other Custom Field');
        $customFields = [$customField1, $customField2];

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId($enrollmentId)
            ->setGroupNames($groupNames)
            ->setGroupTags($groupTags)
            ->setLearningModuleStatus($learningModuleStatus)
            ->setLearningModuleNames($learningModuleNames)
            ->setEnrollmentStatuses($enrollmentStatuses)
            ->setCompletedDates($completedDates)
            ->setDueDates($dueDates)
            ->setEnrolledDates($enrolledDates)
            ->setGracePeriodDates($gracePeriodDates)
            ->setLastAccessedDates($lastAccessedDates)
            ->setStartedDates($startedDates)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setUserEmailAddresses($userEmailAddresses)
            ->setUserEmployeeIds($userEmployeeIds)
            ->setColumns($columns)
            ->setCustomFields($customFields);

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';

        $xml = $xmlGenerator->getLearnerReport($accountApi, $userApi, $query);
        self::assertIsString($xml);

        $xml = simplexml_load_string($xml);

        self::assertEquals($xml->getName(), 'SmarterU');
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getLearnerReport', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(3, $parameters);
        self::assertContains('Report', $parameters);
        $report = [];
        foreach ($xml->Parameters->Report->children() as $reportTag) {
            $report[] = $reportTag->getName();
        }
        self::assertCount(3, $report);
        self::assertContains('Page', $report);
        self::assertEquals(
            (int) $xml->Parameters->Report->Page,
            $query->getPage()
        );
        self::assertContains('PageSize', $report);
        self::assertEquals(
            (int) $xml->Parameters->Report->PageSize,
            $query->getPageSize()
        );
        self::assertContains('Filters', $report);
        $filters = [];
        foreach ($xml->Parameters->Report->Filters->children() as $tag) {
            $filters[] = $tag->getName();
        }
        self::assertCount(5, $filters);
        self::assertContains('EnrollmentID', $filters);
        self::assertEquals(
            $query->getEnrollmentId(),
            $xml->Parameters->Report->Filters->EnrollmentID
        );
        self::assertContains('Groups', $filters);
        $groups = [];
        foreach ($xml->Parameters->Report->Filters->Groups->children() as $tag) {
            $groups[] = $tag->getName();
        }
        self::assertCount(2, $groups);
        self::assertContains('GroupNames', $groups);
        $names = (array) $xml->Parameters->Report->Filters->Groups->GroupNames->GroupName;
        self::assertCount(2, $names);
        foreach ($groupNames as $name) {
            self::assertContains($name, $names);
        }
        self::assertContains('GroupTags2', $groups);
        $tags = (array) $xml->Parameters->Report->Filters->Groups->GroupTags2;
        $tags = $tags['GroupTag2'];
        self::assertCount(2, $tags);
        $firstTag = [];
        foreach ($tags[0]->children() as $tag) {
            $firstTag[] = $tag->getName();
        }
        self::assertContains('TagName', $firstTag);
        self::assertEquals($tag1->getTagName(), $tags[0]->TagName);
        self::assertEquals($tag1->getTagValues(), $tags[0]->TagValues);
        $secondTag = [];
        foreach ($tags[1]->children() as $tag) {
            $secondTag[] = $tag->getName();
        }
        self::assertContains('TagID', $secondTag);
        self::assertEquals($tag2->getTagId(), $tags[1]->TagID);
        self::assertEquals($tag2->getTagValues(), $tags[1]->TagValues);
        self::assertContains('LearningModules', $filters);
        //print_r($xml->Parameters->Report->Filters->LearningModules);
        $modules = [];
        foreach ($xml->Parameters->Report->Filters->LearningModules->children() as $tag) {
            $modules[] = $tag->getName();
        }
        self::assertCount(8, $modules);
        self::assertContains('LearningModule', $modules);
        $module = [];
        foreach ($xml->Parameters->Report->Filters->LearningModules->LearningModule->children() as $tag) {
            $module[] = $tag->getName();
        }
        self::assertCount(2, $module);
        self::assertContains('LearningModuleStatus', $module);
        self::assertEquals(
            $learningModuleStatus,
            $xml->Parameters->Report->Filters->LearningModules->LearningModule
                ->LearningModuleStatus
        );
        self::assertContains('LearningModuleNames', $module);
        $names = (array) $xml->Parameters->Report->Filters->LearningModules
            ->LearningModule->LearningModuleNames->LearningModuleName;
        self::assertCount(2, $learningModuleNames);
        foreach ($learningModuleNames as $name) {
            self::assertContains($name, $names);
        }
        self::assertContains('EnrollmentStatuses', $modules);
        $statuses = (array) $xml->Parameters->Report->Filters->LearningModules
            ->EnrollmentStatuses->EnrollmentStatus;
        self::assertCount(3, $statuses);
        foreach ($enrollmentStatuses as $status) {
            self::assertContains($status, $statuses);
        }
        self::assertContains('CompletedDates', $modules);
        $dates = (array) $xml->Parameters->Report->Filters->LearningModules->CompletedDates;
        $dates = $dates['CompletedDate'];
        self::assertCount(2, $dates);
        foreach ($dates as $date) {
            $dateRangeTag = [];
            foreach ($date->children() as $tag) {
                $dateRangeTag[] = $tag->getName();
            }
            self::assertCount(2, $dateRangeTag);
            self::assertContains('CompletedDateFrom', $dateRangeTag);
            self::assertContains('CompletedDateTo', $dateRangeTag);
        }
        self::assertEquals(
            $dates[0]->CompletedDateFrom,
            $completedDate1->getDateFrom()->format('d-M-y')
        );
        self::assertEquals(
            $dates[0]->CompletedDateTo,
            $completedDate1->getDateTo()->format('d-M-y')
        );
        self::assertEquals(
            $dates[1]->CompletedDateFrom,
            $completedDate2->getDateFrom()->format('d-M-y')
        );
        self::assertEquals(
            $dates[1]->CompletedDateTo,
            $completedDate2->getDateTo()->format('d-M-y')
        );
        self::assertContains('DueDates', $modules);
        $dates = (array) $xml->Parameters->Report->Filters->LearningModules->DueDates;
        $dates = (array) $dates['DueDate'];
        self::assertCount(2, $dates);
        self::assertArrayHasKey('DueDateFrom', $dates);
        self::assertEquals(
            $dates['DueDateFrom'],
            $dueDate1->getDateFrom()->format('d-M-y')
        );
        self::assertArrayHasKey('DueDateTo', $dates);
        self::assertEquals(
            $dates['DueDateTo'],
            $dueDate1->getDateTo()->format('d-M-y')
        );
        self::assertContains('EnrolledDates', $modules);
        $dates = (array) $xml->Parameters->Report->Filters->LearningModules->EnrolledDates;
        $dates = (array) $dates['EnrolledDate'];
        self::assertCount(2, $dates);
        self::assertArrayHasKey('EnrolledDateFrom', $dates);
        self::assertEquals(
            $dates['EnrolledDateFrom'],
            $enrolledDate1->getDateFrom()->format('d-M-y')
        );
        self::assertArrayHasKey('EnrolledDateTo', $dates);
        self::assertEquals(
            $dates['EnrolledDateTo'],
            $enrolledDate1->getDateTo()->format('d-M-y')
        );
        self::assertContains('GracePeriodDates', $modules);
        $dates = (array) $xml->Parameters->Report->Filters->LearningModules->GracePeriodDates;
        $dates = (array) $dates['GracePeriodDate'];
        self::assertCount(2, $dates);
        self::assertArrayHasKey('GracePeriodDateFrom', $dates);
        self::assertEquals(
            $dates['GracePeriodDateFrom'],
            $gracePeriodDate1->getDateFrom()->format('d-M-y')
        );
        self::assertArrayHasKey('GracePeriodDateTo', $dates);
        self::assertEquals(
            $dates['GracePeriodDateTo'],
            $gracePeriodDate1->getDateTo()->format('d-M-y')
        );
        self::assertContains('LastAccessedDates', $modules);
        $dates = (array) $xml->Parameters->Report->Filters->LearningModules->LastAccessedDates;
        $dates = (array) $dates['LastAccessedDate'];
        self::assertCount(2, $dates);
        self::assertArrayHasKey('LastAccessedDateFrom', $dates);
        self::assertEquals(
            $dates['LastAccessedDateFrom'],
            $lastAccessedDate1->getDateFrom()->format('d-M-y')
        );
        self::assertArrayHasKey('LastAccessedDateTo', $dates);
        self::assertEquals(
            $dates['LastAccessedDateTo'],
            $lastAccessedDate1->getDateTo()->format('d-M-y')
        );
        self::assertContains('StartedDates', $modules);
        $dates = (array) $xml->Parameters->Report->Filters->LearningModules->StartedDates;
        $dates = (array) $dates['StartedDate'];
        self::assertCount(2, $dates);
        self::assertArrayHasKey('StartedDateFrom', $dates);
        self::assertEquals(
            $dates['StartedDateFrom'],
            $startedDate1->getDateFrom()->format('d-M-y')
        );
        self::assertArrayHasKey('StartedDateTo', $dates);
        self::assertEquals(
            $dates['StartedDateTo'],
            $startedDate1->getDateTo()->format('d-M-y')
        );
        self::assertContains('Enrollments', $filters);
        $enrollments = [];
        foreach ($xml->Parameters->Report->Filters->Enrollments->children() as $tag) {
            $enrollments[] = $tag->getName();
        }
        self::assertCount(2, $enrollments);
        self::assertContains('CreatedDate', $enrollments);
        $created = [];
        foreach ($xml->Parameters->Report->Filters->Enrollments->CreatedDate->children() as $tag) {
            $created[] = $tag->getName();
        }
        self::assertCount(2, $created);
        self::assertContains('CreatedDateFrom', $created);
        self::assertEquals(
            $createdDate->getDateFrom()->format('d-M-y'),
            $xml->Parameters->Report->Filters->Enrollments->CreatedDate->CreatedDateFrom
        );
        self::assertContains('CreatedDateTo', $created);
        self::assertEquals(
            $createdDate->getDateTo()->format('d-M-y'),
            $xml->Parameters->Report->Filters->Enrollments->CreatedDate->CreatedDateTo
        );
        self::assertContains('ModifiedDate', $enrollments);
        $modified = [];
        foreach ($xml->Parameters->Report->Filters->Enrollments->ModifiedDate->children() as $tag) {
            $modified[] = $tag->getName();
        }
        self::assertCount(2, $modified);
        self::assertContains('ModifiedDateFrom', $modified);
        self::assertEquals(
            $modifiedDate->getDateFrom()->format('d-M-y'),
            $xml->Parameters->Report->Filters->Enrollments->ModifiedDate->ModifiedDateFrom
        );
        self::assertContains('ModifiedDateTo', $modified);
        self::assertEquals(
            $modifiedDate->getDateTo()->format('d-M-y'),
            $xml->Parameters->Report->Filters->Enrollments->ModifiedDate->ModifiedDateTo
        );
        self::assertContains('Users', $filters);
        $users = [];
        foreach ($xml->Parameters->Report->Filters->Users->children() as $tag) {
            $users[] = $tag->getName();
        }
        self::assertCount(1, $users);
        self::assertContains('UserIdentifier', $users);
        $identifier = [];
        foreach ($xml->Parameters->Report->Filters->Users->UserIdentifier->children() as $tag) {
            $identifier[] = $tag->getName();
        }
        self::assertContains('EmailAddress', $identifier);
        self::assertContains('EmployeeID', $identifier);
        $emails = (array) $xml->Parameters->Report->Filters->Users->UserIdentifier->EmailAddress;
        foreach ($userEmailAddresses as $email) {
            self::assertContains($email, $emails);
        }
        $ids = (array) $xml->Parameters->Report->Filters->Users->UserIdentifier->EmployeeID;
        foreach ($userEmployeeIds as $id) {
            self::assertContains($id, $ids);
        }
        self::assertContains('Columns', $parameters);
        $columnTags = (array) $xml->Parameters->Columns->ColumnName;
        foreach ($columns as $column) {
            self::assertContains($column, $columnTags);
        }
        self::assertContains('CustomFields', $parameters);
        $fields = (array) $xml->Parameters->CustomFields;
        $fields = $fields['FieldName'];
        foreach ($customFields as $field) {
            self::assertContains($field->getName(), $fields);
        }
    }
}
