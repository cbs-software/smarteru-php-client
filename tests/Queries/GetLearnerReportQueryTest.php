<?php

/**
 * Contains Tests\CBS\SmarterU\Queries\GetLearnerReportQueryTest.php.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Queries;

use CBS\SmarterU\DataTypes\CustomField;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Queries\GetLearnerReportQuery;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Queries\GetLearnerReportQuery.
 */
class GetLearnerReportQueryTest extends TestCase {
    /**
     * Test agreement between getters and setters.
     */
    public function testAgreement(): void {
        $page = 2;
        $pageSize = 40;
        $enrollmentId = '1';
        $groupStatus = 'Active';
        $groupNames = ['My Group', 'My Other Group'];
        $tag1 = (new Tag())
            ->setTagName('My Tag')
            ->setTagValues('Tag 1\'s Values');
        $tag2 = (new Tag())
            ->setTagId('3')
            ->setTagValues('Tag 2\'s Values');
        $groupTags = [$tag1, $tag2];
        $learningModuleStatus = 'Archived';
        $learningModuleNames = ['Course 1', 'Course 2'];
        $enrollmentStatuses = ['Enrolled', 'In Progress', 'Completed'];
        $completedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-09-22'));
        $completedDates = [$completedDate1];
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
        $userStatus = 'Inactive';
        $userEmailAddresses = ['test@test.com', 'test2@test.com'];
        $userEmployeeIds = ['4', '5', '6'];
        $columns = ['ALTERNATE_EMAIL', 'COMPLETED_DATE'];
        $customField1 = (new CustomField())
            ->setName('My Custom Field')
            ->setValue('Field 1\'s Value');
        $customFields = [$customField1];

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId($enrollmentId);

        // Test the default values.
        self::assertEquals(1, $query->getPage());
        self::assertEquals(50, $query->getPageSize());
        self::assertEquals($enrollmentId, $query->getEnrollmentId());
        self::assertNull($query->getGroupStatus());
        self::assertEmpty($query->getGroupNames());
        self::assertEmpty($query->getGroupTags());
        self::assertNull($query->getLearningModuleStatus());
        self::assertEmpty($query->getLearningModuleNames());
        self::assertEmpty($query->getEnrollmentStatuses());
        self::assertEmpty($query->getCompletedDates());
        self::assertEmpty($query->getDueDates());
        self::assertEmpty($query->getEnrolledDates());
        self::assertEmpty($query->getGracePeriodDates());
        self::assertEmpty($query->getLastAccessedDates());
        self::assertEmpty($query->getStartedDates());
        self::assertNull($query->getCreatedDate());
        self::assertNull($query->getModifiedDate());
        self::assertNull($query->getUserStatus());
        self::assertEmpty($query->getUserEmailAddresses());
        self::assertEmpty($query->getUserEmployeeIds());
        self::assertEmpty($query->getColumns());
        self::assertEmpty($query->getCustomFields());

        // Test the setters.
        $query
            ->setPage($page)
            ->setPageSize($pageSize)
            ->setGroupStatus($groupStatus)
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
            ->setUserStatus($userStatus)
            ->setColumns($columns)
            ->setCustomFields($customFields);

        self::assertEquals($page, $query->getPage());
        self::assertEquals($pageSize, $query->getPageSize());
        self::assertEquals($groupStatus, $query->getGroupStatus());
        self::assertEquals($groupTags, $query->getGroupTags());
        self::assertEquals($learningModuleStatus, $query->getLearningModuleStatus());
        self::assertEquals($learningModuleNames, $query->getLearningModuleNames());
        self::assertEquals($enrollmentStatuses, $query->getEnrollmentStatuses());
        self::assertEquals($completedDates, $query->getCompletedDates());
        self::assertEquals($dueDates, $query->getDueDates());
        self::assertEquals($enrolledDates, $query->getEnrolledDates());
        self::assertEquals($gracePeriodDates, $query->getGracePeriodDates());
        self::assertEquals($lastAccessedDates, $query->getLastAccessedDates());
        self::assertEquals($startedDates, $query->getStartedDates());
        self::assertEquals($createdDate, $query->getCreatedDate());
        self::assertEquals($modifiedDate, $query->getModifiedDate());
        self::assertEquals($userStatus, $query->getUserStatus());
        self::assertEquals($columns, $query->getColumns());
        self::assertEquals($customFields, $query->getCustomFields());

        // Test the mutually exclusive properties.
        $query->setGroupNames($groupNames);
        self::assertEquals($groupNames, $query->getGroupNames());
        self::assertNull($query->getGroupStatus());
        $query->setGroupStatus($groupStatus);
        self::assertEquals($groupStatus, $query->getGroupStatus());
        self::assertEmpty($query->getGroupNames());

        $query->setUserEmailAddresses($userEmailAddresses);
        $query->setUserEmployeeIds($userEmployeeIds);
        self::assertEquals($userEmailAddresses, $query->getUserEmailAddresses());
        self::assertEquals($userEmployeeIds, $query->getUserEmployeeIds());
        self::assertNull($query->getUserStatus());
        $query->setUserStatus($userStatus);
        self::assertEquals($userStatus, $query->getUserStatus());
        self::assertEmpty($query->getUserEmailAddresses());
        self::assertEmpty($query->getUserEmployeeIds());
    }

    /**
     * Test that GetLearnerReportQuery::setGroupStatus() throws an exception
     * when the provided status is invalid.
     */
    public function testSetGroupStatusThrowsExceptionWhenStatusIsInvalid(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$groupStatus" may only be "Active", "Inactive", or "All".'
        );
        $query->setGroupStatus('Invalid');
    }

    /**
     * Test that GetLearnerReportQuery::setGroupNames() throws an exception
     * when the provided name is not a string.
     */
    public function testSetGroupNamesThrowsExceptionWhenNameNotString(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$groupNames" must be an array of strings.'
        );
        $query->setGroupNames(['Group1', 2, 'Group3']);
    }

    /**
     * Test that GetLearnerReportQuery::setGroupTags() throws an exception
     * when the provided tag is not an instance of CBS\SmarterU\DataTypes\Tag.
     */
    public function testSetGroupTagsThrowsExceptionWhenTagNotInstanceOfTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$groupTags" must be an array of CBS\SmarterU\DataTypes\Tag instances.'
        );
        $query->setGroupTags(['Not a tag']);
    }

    /**
     * Test that GetLearnerReportQuery::setLearningModuleStatus() throws an
     * exception when the provided status is invalid.
     */
    public function testSetLearningModuleStatusThrowsExceptionWhenStatusIsInvalid(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$learningModuleStatus" must be either "Active", "Inactive", or "Archived".'
        );
        $query->setLearningModuleStatus('Invalid');
    }

    /**
     * Test that GetLearnerReportQuery::setLearningModuleNames() throws an
     * exception when the provided name is not a string.
     */
    public function testSetLearningModuleNamesThrowsExceptionWhenNameNotString(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$learningModuleNames" must be an array of strings.'
        );
        $query->setLearningModuleNames([1]);
    }

    /**
     * Test that GetLearnerReportQuery::setEnrollmentStatuses() throws an
     * exception when the provided status is not a string.
     */
    public function testSetEnrollmentStatusesThrowsExceptionWhenStatusNotString(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$enrollmentStatuses" must be an array of strings.'
        );
        $query->setEnrollmentStatuses([1]);
    }

    /**
     * Test that GetLearnerReportQuery::setEnrollmentStatuses() throws an
     * exception when the provided status is not one of the valid statuses
     * recognized by the SmarterU API.
     */
    public function testSetEnrollmentStatusesThrowsExceptionWhenStatusNotValid(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$enrollmentStatuses" must contain only valid statuses recognized by the SmarterU API.'
        );
        $query->setEnrollmentStatuses(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setCompletedDates() throws an
     * exception when the provided value is not an instance of
     * CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function testSetCompletedDatesThrowsExceptionWhenDatesNotInstanceOfDateRangeTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$completedDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
        );
        $query->setCompletedDates(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setDueDates() throws an
     * exception when the provided value is not an instance of
     * CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function testSetDueDatesThrowsExceptionWhenDatesNotInstanceOfDateRangeTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$dueDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
        );
        $query->setDueDates(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setEnrolledDates() throws an
     * exception when the provided value is not an instance of
     * CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function testSetEnrolledDatesThrowsExceptionWhenDatesNotInstanceOfDateRangeTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$enrolledDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
        );
        $query->setEnrolledDates(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setGracePeriodDates() throws an
     * exception when the provided value is not an instance of
     * CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function testSetGracePeriodDatesThrowsExceptionWhenDatesNotInstanceOfDateRangeTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$gracePeriodDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
        );
        $query->setGracePeriodDates(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setLastAccessedDates() throws an
     * exception when the provided value is not an instance of
     * CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function testSetLastAccessedDatesThrowsExceptionWhenDatesNotInstanceOfDateRangeTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$lastAccessedDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
        );
        $query->setLastAccessedDates(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setStartedDates() throws an
     * exception when the provided value is not an instance of
     * CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function testSetStartedDatesThrowsExceptionWhenDatesNotInstanceOfDateRangeTag(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$startedDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
        );
        $query->setStartedDates(['invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setUserStatus() throws an exception
     * when the provided status is invalid.
     */
    public function testSetUserStatusThrowsExceptionWhenStatusInvalid(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$userStatus" may only be set to "Active", "Inactive", or "All".'
        );
        $query->setUserStatus('Invalid');
    }

    /**
     * Test that GetLearnerReportQuery::setUserEmailAddresses() throws an
     * exception when one of the provided email addresses is not a string.
     */
    public function testSetUserEmailAddressesThrowsExceptionWhenEmailNotString(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$userEmailAddresses" must be an array of email addresses as strings.'
        );
        $query->setUserEmailAddresses(['Test@test.com', 2, 'test2@test.com']);
    }

    /**
     * Test that GetLearnerReportQuery::setUserEmployeeIds() throws an
     * exception when one of the provided employee IDs is not a string.
     */
    public function testSetUserEmployeeIdsThrowsExceptionWhenIdNotString(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$userEmployeeIds" must be an array of employee IDs as strings.'
        );
        $query->setUserEmployeeIds(['1', 2, '3']);
    }

    /**
     * Test that GetLearnerReportQuery::setColumns() throws an exception when
     * one of the provided columns is not one of the columns accepted by the
     * SmarterU API.
     */
    public function testSetColumnsThrowsExceptionWhenColumnNotValid(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$columns" may only contain the columns defined by the SmarterU API.'
        );
        $query->setColumns(['Invalid']);
    }

    /**
     * Test that GetLearnerReportQuery::setCustomFields() throws an exception
     * when one of the provided values is not an instance of
     * CBS\SmarterU\DataTypes\CustomField.
     */
    public function testSetCustomFieldsThrowsExceptionWhenNotInstanceOfCustomField(): void {
        $query = new GetLearnerReportQuery();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$customFields" must be an array of CBS\SmarterU\DataTypes\CustomField instances.'
        );
        $query->setCustomFields(['Invalid']);
    }
}
