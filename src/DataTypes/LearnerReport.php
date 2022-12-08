<?php

/**
 * Contains CBS\SmarterU\DataTypes\LearnerReport.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/14
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

use CBS\SmarterU\Exceptions\InvalidArgumentException;
use DateTimeInterface;

/**
 * Represents a LearnerReport within SmarterU.
 *
 * A Learner Report, also known as an Enrollment Report, enables course
 * managers to view the progress of Users who have been assigned to the
 * course. Please refer to the SmarterU documentation for further information:
 * https://support.smarteru.com/v1/docs/enrollment-report
 */
class LearnerReport {
    /**
     * The system-generated identifier for the user's course enrollment.
     */
    protected string $id;

    /**
     * The course's name.
     */
    protected string $courseName;

    /**
     * The user's surname.
     */
    protected string $surname;

    /**
     * The user's given name.
     */
    protected string $givenName;

    /**
     * The course's system-generated identifier.
     */
    protected string $learningModuleId;

    /**
     * The User's system-generated identifier.
     */
    protected string $userId;

    /**
     * The User's email address.
     */
    protected ?string $userEmail = null;

    /**
     * The User's alternate email address.
     */
    protected ?string $alternateEmail = null;

    /**
     * The User's employee ID.
     */
    protected ?string $employeeId = null;

    /**
     * The User's division.
     */
    protected ?string $division = null;

    /**
     * The User's title.
     */
    protected ?string $title = null;

    /**
     * The ID of the Group containing this training assignment.
     */
    protected ?string $groupId = null;

    /**
     * The name of the Group containing this training assignment.
     */
    protected ?string $groupName = null;

    /**
     * The duration of the course.
     */
    protected ?string $courseDuration = null;

    /**
     * The course's session ID.
     */
    protected ?string $courseSessionId = null;

    /**
     * The UTC date the enrollment was created.
     */
    protected DateTimeInterface $createdDate;

    /**
     * The UTC date the enrollment was last updated.
     */
    protected DateTimeInterface $modifiedDate;

    /**
     * The UTC date the User was enrolled in the Course.
     */
    protected ?DateTimeInterface $enrolledDate = null;

    /**
     * The UTC date the Course is due.
     */
    protected ?DateTimeInterface $dueDate = null;

    /**
     * The UTC date the User started working on the Course.
     */
    protected ?DateTimeInterface $startedDate = null;

    /**
     * The UTC date the User last accessed the Course.
     */
    protected ?DateTimeInterface $lastAccessedDate = null;

    /**
     * The UTC date the User completed the Course.
     */
    protected ?DateTimeInterface $completedDate = null;

    /**
     * The grade the User got in the Course.
     */
    protected ?string $grade = null;

    /**
     * The grade expressed as a percentage.
     */
    protected ?float $gradePercentage = null;

    /**
     * The points scored by the User in the Course.
     */
    protected ?int $points = null;

    /**
     * The User's progress in the Course.
     */
    protected ?string $progress = null;

    /**
     * The name of the subscription the Course is part of.
     */
    protected ?string $subscriptionName = null;

    /**
     * The name of the variant of that subscription.
     */
    protected ?string $variantName = null;

    /**
     * The UTC date the subscription variant started.
     */
    protected ?DateTimeInterface $variantStartDate = null;

    /**
     * The UTC date the subscription variant ends.
     */
    protected ?DateTimeInterface $variantEndDate = null;

    /**
     * The role ID.
     */
    protected ?string $roleId = null;

    /**
     * Any CustomFields specified by the SmarterU API.
     */
    protected array $customFields;

    /**
     * Get the system-generated identifier for the user's course enrollment.
     *
     * @return string the system-generated identifier
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Set the system-generated identifier for the user's course enrollment.
     *
     * @param string $id the system-generated identifier
     * @return self
     */
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the course's name.
     *
     * @return string The course's name.
     */
    public function getCourseName(): string {
        return $this->courseName;
    }

    /**
     * Set the course's name.
     *
     * @param string $courseName The course's name.
     * @return self
     */
    public function setCourseName(string $courseName): self {
        $this->courseName = $courseName;
        return $this;
    }

    /**
     * Get the user's surname.
     *
     * @return string The user's surname.
     */
    public function getSurname(): string {
        return $this->surname;
    }

    /**
     * Set the user's surname.
     *
     * @param string $surname The user's surname.
     * @return self
     */
    public function setSurname(string $surname): self {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Get the user's given name.
     *
     * @return string The user's given name.
     */
    public function getGivenName(): string {
        return $this->givenName;
    }

    /**
     * Set the user's given name.
     *
     * @param string $givenName The user's given name.
     * @return self
     */
    public function setGivenName(string $givenName): self {
        $this->givenName = $givenName;
        return $this;
    }

    /**
     * Get the course's system-generated identifier.
     *
     * @return string The course's system-generated identifier.
     */
    public function getLearningModuleId(): string {
        return $this->learningModuleId;
    }

    /**
     * Set the course's system-generated identifier.
     *
     * @param string $learningModuleId The course's system-generated identifier.
     * @return self
     */
    public function setLearningModuleId(string $learningModuleId): self {
        $this->learningModuleId = $learningModuleId;
        return $this;
    }

    /**
     * Get the user's system-generated identifier.
     *
     * @return string The user's system-generated identifier.
     */
    public function getUserId(): string {
        return $this->userId;
    }

    /**
     * Set the user's system-generated identifier.
     *
     * @param string $userId The user's system-generated identifier.
     * @return self
     */
    public function setUserId(string $userId): self {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get the User's email address.
     *
     * @return ?string The User's email address.
     */
    public function getUserEmail(): ?string {
        return $this->userEmail;
    }

    /**
     * Set the User's email address.
     *
     * @param string $userEmail The User's email address.
     * @return self
     */
    public function setUserEmail(string $userEmail): self {
        $this->userEmail = $userEmail;
        return $this;
    }

    /**
     * Get the User's alternate email address.
     *
     * @return ?string The User's alternate email address.
     */
    public function getAlternateEmail(): ?string {
        return $this->alternateEmail;
    }

    /**
     * Set the User's alternate email address.
     *
     * @param string $alternateEmail The User's alternate email address.
     * @return self
     */
    public function setAlternateEmail(string $alternateEmail): self {
        $this->alternateEmail = $alternateEmail;
        return $this;
    }

    /**
     * Get the User's employee ID.
     *
     * @return ?string The User's employee ID.
     */
    public function getEmployeeId(): ?string {
        return $this->employeeId;
    }

    /**
     * Set the User's employee ID.
     *
     * @param string $employeeId The User's employee ID.
     * @return self
     */
    public function setEmployeeId(string $employeeId): self {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * Get the User's division.
     *
     * @return ?string The User's division.
     */
    public function getDivision(): ?string {
        return $this->division;
    }

    /**
     * Set the User's division.
     *
     * @param string $division The User's division.
     * @return self
     */
    public function setDivision(string $division): self {
        $this->division = $division;
        return $this;
    }

    /**
     * Get the User's title.
     *
     * @return ?string The User's title.
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * Set the User's title.
     *
     * @param string $title The User's title.
     * @return self
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the ID of the Group containing this training assignment.
     *
     * @return ?string The ID of the Group
     */
    public function getGroupId(): ?string {
        return $this->groupId;
    }

    /**
     * Set the ID of the Group containing this training assignment.
     *
     * @param string $groupId The ID of the Group
     * @return self
     */
    public function setGroupId(string $groupId): self {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * Get the name of the Group containing this training assignment.
     *
     * @return ?string The name of the Group.
     */
    public function getGroupName(): ?string {
        return $this->groupName;
    }

    /**
     * Set the name of the Group containing this training assignment.
     *
     * @param string $groupName The name of the Group.
     * @return self
     */
    public function setGroupName(string $groupName): self {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * Get the duration of the course.
     *
     * @return ?string The duration of the course.
     */
    public function getCourseDuration(): ?string {
        return $this->courseDuration;
    }

    /**
     * Set the duration of the course.
     *
     * @param string $courseDuration The duration of the course.
     * @return self
     */
    public function setCourseDuration(string $courseDuration): self {
        $this->courseDuration = $courseDuration;
        return $this;
    }

    /**
     * Get the course's session ID.
     *
     * @return ?string The course's session ID.
     */
    public function getCourseSessionId(): ?string {
        return $this->courseSessionId;
    }

    /**
     * Set the course's session ID.
     *
     * @param string $courseSessionId The course's session ID.
     * @return self
     */
    public function setCourseSessionId(string $courseSessionId): self {
        $this->courseSessionId = $courseSessionId;
        return $this;
    }

    /**
     * Get the UTC date the enrollment was created.
     *
     * @return DateTimeInterface The UTC date the enrollment was created.
     */
    public function getCreatedDate(): DateTimeInterface {
        return $this->createdDate;
    }

    /**
     * Set the UTC date the enrollment was created.
     *
     * @param DateTimeInterface $createdDate The UTC date the enrollment was
     *      created.
     * @return self
     */
    public function setCreatedDate(DateTimeInterface $createdDate): self {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get the UTC date the enrollment was last updated.
     *
     * @return DateTimeInterface The UTC date the enrollment was last updated.
     */
    public function getModifiedDate(): DateTimeInterface {
        return $this->modifiedDate;
    }

    /**
     * Set the UTC date the enrollment was last updated.
     *
     * @param DateTimeInterface $modifiedDate The UTC date the enrollment was
     *      last updated.
     * @return self
     */
    public function setModifiedDate(DateTimeInterface $modifiedDate): self {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * Get the columns specified by the SmarterU API.
     *
     * @return array The columns specified by the SmarterU API.
     */
    public function getColumns(): array {
        return $this->columns;
    }

    /**
     * Set the columns specified by the SmarterU API.
     *
     * @param array $columns The columns specified by the SmarterU API.
     * @return self
     */
    public function setColumns(array $columns): self {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Get the custom fields specified by the SmarterU API.
     *
     * @return array The custom fields specified by the SmarterU API.
     */
    public function getCustomFields(): array {
        return $this->customFields;
    }

    /**
     * Set the custom fields specified by the SmarterU API.
     *
     * @param array $customFields The custom fields specified by the SmarterU API.
     * @return self
     */
    public function setCustomFields(array $customFields): self {
        foreach ($customFields as $field) {
            if (!($field instanceof CustomField)) {
                throw new InvalidArgumentException(
                    '"$customFields" must be an array of CBS\SmarterU\DataTypes\CustomField instances.'
                );
            }
        }
        $this->customFields = $customFields;
        return $this;
    }

    /**
     * Get the UTC date the User was enrolled in the Course.
     *
     * @return ?DateTimeInterface The UTC date the User was enrolled.
     */
    public function getEnrolledDate(): ?DateTimeInterface {
        return $this->enrolledDate;
    }

    /**
     * Set the UTC date the User was enrolled in the Course.
     *
     * @param DateTimeInterface $enrolledDate The UTC date the User was enrolled.
     * @return self
     */
    public function setEnrolledDate(DateTimeInterface $enrolledDate): self {
        $this->enrolledDate = $enrolledDate;
        return $this;
    }

    /**
     * Get the UTC date the Course is due.
     *
     * @return ?DateTimeInterface The UTC date the Course is due.
     */
    public function getDueDate(): ?DateTimeInterface {
        return $this->dueDate;
    }

    /**
     * Set the UTC date the Course is due.
     *
     * @param DateTimeInterface $dueDate The UTC date the Course is due.
     * @return self
     */
    public function setDueDate(DateTimeInterface $dueDate): self {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * Get the UTC date the User started the Course.
     *
     * @return ?DateTimeInterface The UTC date the User started the Course.
     */
    public function getStartedDate(): ?DateTimeInterface {
        return $this->startedDate;
    }

    /**
     * Set the UTC date the User started the Course.
     *
     * @param DateTimeInterface $startedDate The UTC date the User started the Course.
     * @return self
     */
    public function setStartedDate(DateTimeInterface $startedDate): self {
        $this->startedDate = $startedDate;
        return $this;
    }

    /**
     * Get the UTC date the User last accessed the Course.
     *
     * @return ?DateTimeInterface The UTC date the User last accessed the Course.
     */
    public function getLastAccessedDate(): ?DateTimeInterface {
        return $this->lastAccessedDate;
    }

    /**
     * Set the UTC date the User last accessed the Course.
     *
     * @param DateTimeInterface $lastAccessedDate The UTC date the User last
     *      accessed the Course.
     * @return self
     */
    public function setLastAccessedDate(DateTimeInterface $lastAccessedDate): self {
        $this->lastAccessedDate = $lastAccessedDate;
        return $this;
    }

    /**
     * Get the UTC date the User completed the Course.
     *
     * @return ?DateTimeInterface The UTC date the User completed the Course.
     */
    public function getCompletedDate(): ?DateTimeInterface {
        return $this->completedDate;
    }

    /**
     * Set the UTC date the User completed the Course.
     *
     * @param DateTimeInterface $completedDate The UTC date the User completed
     *      the Course.
     * @return self
     */
    public function setCompletedDate(DateTimeInterface $completedDate): self {
        $this->completedDate = $completedDate;
        return $this;
    }

    /**
     * Get the grade the User got in the Course.
     *
     * @return ?string The grade the User got in the Course
     */
    public function getGrade(): ?string {
        return $this->grade;
    }

    /**
     * Set the grade the User got in the Course.
     *
     * @param string $grade The grade the User got in the Course
     * @return self
     */
    public function setGrade(string $grade): self {
        $this->grade = $grade;
        return $this;
    }

    /**
     * Get the User's grade expressed as a percentage.
     *
     * @return ?float the User's grade expressed as a percentage.
     */
    public function getGradePercentage(): ?float {
        return $this->gradePercentage;
    }

    /**
     * Set the User's grade expressed as a percentage.
     *
     * @param float $gradePercentage The User's grade expressed as a percentage.
     * @return self
     */
    public function setGradePercentage(float $gradePercentage): self {
        $this->gradePercentage = $gradePercentage;
        return $this;
    }

    /**
     * Get the points scored by the User in the Course.
     *
     * @return ?int The points scored by the User in the Course.
     */
    public function getPoints(): ?int {
        return $this->points;
    }

    /**
     * Set the points scored by the User in the Course.
     *
     * @param int $points The points scored by the User in the Course.
     * @return self
     */
    public function setPoints(int $points): self {
        $this->points = $points;
        return $this;
    }

    /**
     * Get the User's progress in the Course.
     *
     * @return ?string The User's progress in the Course.
     */
    public function getProgress(): ?string {
        return $this->progress;
    }

    /**
     * Set the User's progress in the Course.
     *
     * @param string $progress The User's progress in the Course.
     * @return self
     */
    public function setProgress(string $progress): self {
        $this->progress = $progress;
        return $this;
    }

    /**
     * Get the name of the subscription the Course is part of.
     *
     * @return ?string The name of the subscription.
     */
    public function getSubscriptionName(): ?string {
        return $this->subscriptionName;
    }

    /**
     * Set the name of the subscription the Course is part of.
     *
     * @param string $subscriptionName The name of the subscription.
     * @return self
     */
    public function setSubscriptionName(string $subscriptionName): self {
        $this->subscriptionName = $subscriptionName;
        return $this;
    }

    /**
     * Get the name of the subscription variant.
     *
     * @return ?string The name of the subscription variant.
     */
    public function getVariantName(): ?string {
        return $this->variantName;
    }

    /**
     * Set the name of the subscription variant.
     *
     * @param string $variantName The name of the subscription variant.
     * @return self
     */
    public function setVariantName(string $variantName): self {
        $this->variantName = $variantName;
        return $this;
    }

    /**
     * Get the UTC date the subscription variant started.
     *
     * @return ?DateTimeInterface The UTC date the subscription variant started.
     */
    public function getVariantStartDate(): ?DateTimeInterface {
        return $this->variantStartDate;
    }

    /**
     * Set the UTC date the subscription variant started.
     *
     * @param DateTimeInterface $variantStartDate THe UTC date the subscription
     *      variant started.
     * @return self
     */
    public function setVariantStartDate(DateTimeInterface $variantStartDate): self {
        $this->variantStartDate = $variantStartDate;
        return $this;
    }

    /**
     * Get the UTC date the subscription variant ends.
     *
     * @return ?DateTimeInterface The UTC date the subscription variant ends.
     */
    public function getVariantEndDate(): ?DateTimeInterface {
        return $this->variantEndDate;
    }

    /**
     * Set the UTC date the subscription variant ends.
     *
     * @param DateTimeInterface $variantEndDate THe UTC date the subscription
     *      variant ends.
     * @return self
     */
    public function setVariantEndDate(DateTimeInterface $variantEndDate): self {
        $this->variantEndDate = $variantEndDate;
        return $this;
    }

    /**
     * Get the role ID.
     *
     * @return ?string The role ID.
     */
    public function getRoleId(): ?string {
        return $this->roleId;
    }

    /**
     * Set the role ID.
     *
     * @param string $roleId The role ID.
     * @return self
     */
    public function setRoleId(string $roleId): self {
        $this->roleId = $roleId;
        return $this;
    }
}
