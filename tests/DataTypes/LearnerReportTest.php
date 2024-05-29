<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\LearnerReportTest.php.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\CustomField;
use CBS\SmarterU\DataTypes\LearnerReport;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\LearnerReport.
 */
class LearnerReportTest extends TestCase {
    /**
     * Test agreement between getters and setters.
     */
    public function testAgreement() {
        $id = '1';
        $courseName = 'My Course';
        $surname = 'User';
        $givenName = 'Test';
        $learningModuleId = '2';
        $userId = '3';
        $userEmail = 'test@test.com';
        $alternateEmail = 'test1@test.com';
        $employeeId = '4';
        $division = 'My Division';
        $title = 'My Title';
        $groupId = '5';
        $groupName = 'My Group';
        $courseDuration =  '1w';
        $courseSessionId = '6';
        $createdDate = new DateTime('2022/09/19');
        $modifiedDate = new DateTime('2022/09/20');
        $enrolledDate = new DateTime('2022/09/21');
        $dueDate = new DateTime('2022/09/25');
        $startedDate = new DateTime('2022/09/22');
        $lastAccessedDate = new DateTime('2022/09/24');
        $completedDate = new DateTime('2022/09/23');
        $grade = 'A';
        $gradePercentage = 98.0;
        $points = 49;
        $progress = '100%';
        $subscriptionName = 'My Subscription';
        $variantName = 'My Variant';
        $variantStartDate = new DateTime('2022/09/18');
        $variantEndDate = new DateTime('2022/09/26');
        $roleId = '7';
        $field1 = (new CustomField())
            ->setName('Field 1')
            ->setValue('Value1');
        $field2 = (new CustomField())
            ->setName('Field2')
            ->setValue('Value2');
        $customFields = [$field1, $field2];

        $report = (new LearnerReport())
            ->setId($id)
            ->setCourseName($courseName)
            ->setSurname($surname)
            ->setGivenName($givenName)
            ->setLearningModuleId($learningModuleId)
            ->setUserId($userId)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setCustomFields($customFields);

        self::assertEquals($id, $report->getId());
        self::assertEquals($courseName, $report->getCourseName());
        self::assertEquals($surname, $report->getSurname());
        self::assertEquals($givenName, $report->getGivenName());
        self::assertEquals($learningModuleId, $report->getLearningModuleId());
        self::assertEquals($userId, $report->getUserId());
        self::assertNull($report->getUserEmail());
        self::assertNull($report->getAlternateEmail());
        self::assertNull($report->getEmployeeId());
        self::assertNull($report->getDivision());
        self::assertNull($report->getTitle());
        self::assertNull($report->getGroupId());
        self::assertNull($report->getGroupName());
        self::assertNull($report->getCourseDuration());
        self::assertNull($report->getCourseSessionId());
        self::assertEquals($createdDate, $report->getCreatedDate());
        self::assertEquals($modifiedDate, $report->getModifiedDate());
        self::assertNull($report->getEnrolledDate());
        self::assertNull($report->getDueDate());
        self::assertNull($report->getStartedDate());
        self::assertNull($report->getLastAccessedDate());
        self::assertNull($report->getCompletedDate());
        self::assertNull($report->getGrade());
        self::assertNull($report->getGradePercentage());
        self::assertNull($report->getPoints());
        self::assertNull($report->getProgress());
        self::assertNull($report->getSubscriptionName());
        self::assertNull($report->getVariantName());
        self::assertNull($report->getVariantStartDate());
        self::assertNull($report->getVariantEndDate());
        self::assertNull($report->getRoleId());
        self::assertIsArray($report->getCustomFields());
        self::assertCount(2, $report->getCustomFields());
        self::assertContains($field1, $report->getCustomFields());
        self::assertContains($field2, $report->getCustomFields());

        $report
            ->setUserEmail($userEmail)
            ->setAlternateEmail($alternateEmail)
            ->setEmployeeId($employeeId)
            ->setDivision($division)
            ->setTitle($title)
            ->setGroupId($groupId)
            ->setGroupName($groupName)
            ->setCourseDuration($courseDuration)
            ->setCourseSessionId($courseSessionId)
            ->setEnrolledDate($enrolledDate)
            ->setDueDate($dueDate)
            ->setStartedDate($startedDate)
            ->setLastAccessedDate($lastAccessedDate)
            ->setCompletedDate($completedDate)
            ->setGrade($grade)
            ->setGradePercentage($gradePercentage)
            ->setPoints($points)
            ->setProgress($progress)
            ->setSubscriptionName($subscriptionName)
            ->setVariantName($variantName)
            ->setVariantStartDate($variantStartDate)
            ->setVariantEndDate($variantEndDate)
            ->setRoleId($roleId);

        self::assertEquals($userEmail, $report->getUserEmail());
        self::assertEquals($alternateEmail, $report->getAlternateEmail());
        self::assertEquals($employeeId, $report->getEmployeeId());
        self::assertEquals($division, $report->getDivision());
        self::assertEquals($title, $report->getTitle());
        self::assertEquals($groupId, $report->getGroupId());
        self::assertEquals($groupName, $report->getGroupName());
        self::assertEquals($courseDuration, $report->getCourseDuration());
        self::assertEquals($courseSessionId, $report->getCourseSessionId());
        self::assertEquals($enrolledDate, $report->getEnrolledDate());
        self::assertEquals($dueDate, $report->getDueDate());
        self::assertEquals($startedDate, $report->getStartedDate());
        self::assertEquals($lastAccessedDate, $report->getLastAccessedDate());
        self::assertEquals($completedDate, $report->getCompletedDate());
        self::assertEquals($grade, $report->getGrade());
        self::assertEquals($gradePercentage, $report->getGradePercentage());
        self::assertEquals($points, $report->getPoints());
        self::assertEquals($progress, $report->getProgress());
        self::assertEquals($subscriptionName, $report->getSubscriptionName());
        self::assertEquals($variantName, $report->getVariantName());
        self::assertEquals($variantStartDate, $report->getVariantStartDate());
        self::assertEquals($variantEndDate, $report->getVariantEndDate());
        self::assertEquals($roleId, $report->getRoleId());
    }

    /**
     * Test that an exception is thrown when attempting to set CustomFields to
     * a value that is not an array of CustomFields.
     */
    public function testExceptionIsThrownWhenCustomFieldsNotInstanceOfCustomField() {
        $customFields = [1, 2, 3];
        $report = new LearnerReport();

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$customFields" must be an array of CBS\SmarterU\DataTypes\CustomField instances.'
        );
        $report->setCustomFields($customFields);
    }
}
