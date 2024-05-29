<?php

/**
 * Contains CBS\SmarterU\Queries\GetLearnerReportQuery
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries;

use CBS\SmarterU\DataTypes\CustomField;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Queries\Tags\DateRangeTag;

/**
 * Represents a GetLearnerReport query made to the SmarterU API.
 */
class GetLearnerReportQuery {
    /**
     * The maximum allowed value for $pageSize, as defined by the SmarterU API.
     */
    protected const MAX_PAGE_SIZE = 1000;

    /**
     * The value denoting an active Group, User, or Learning Module to return.
     * If used, the query will only return Groups, Users, or Learning Modules
     * that are currently active.
     */
    protected const STATUS_ACTIVE = 'Active';

    /**
     * The value denoting an inactive Group, User, or Learning Module to return.
     * If used, the query will only return Groups, Users, or Learning Modules
     * that are currently inactive.
     */
    protected const STATUS_INACTIVE = 'Inactive';

    /**
     * The value denoting all Groups or Users to return. If used, the query
     * will return all Groups or Users regardless of status.
     */
    protected const STATUS_ALL = 'All';

    /**
     * The value denoting an archived Learning Module to return. If used, the
     * query will only return Learning Modules that are currently archived.
     */
    protected const STATUS_ARCHIVED = 'Archived';

    /**
     * All valid enrollment statuses recognized by the SmarterU API.
     */
    protected const VALID_ENROLLMENT_STATUSES = [
        'Enrolled',
        'Unconfirmed',
        'In Progress',
        'Warning',
        'Overdue',
        'Attending',
        'Not Attending',
        'Attended',
        'Did Not Attend',
        'Cancelled',
        'Completed'
    ];

    /**
     * All valid additional columns recognized by the SmarterU API.
     */
    protected const VALID_COLUMNS = [
        'ALTERNATE_EMAIL',
        'COMPLETED_DATE',
        'COURSE_DURATION',
        'COURSE_SESSION_ID',
        'DIVISION',
        'DUE_DATE',
        'EMPLOYEE_ID',
        'ENROLLED_DATE',
        'GRADE',
        'GRADE_PERCENTAGE',
        'GROUP_ID',
        'GROUP_NAME',
        'LAST_ACCESSED_DATE',
        'POINTS',
        'PROGRESS',
        'ROLE_ID',
        'STARTED_DATE',
        'SUBSCRIPTION_NAME',
        'TITLE',
        'USER_EMAIL',
        'VARIANT_END_DATE',
        'VARIANT_NAME',
        'VARIANT_START_DATE'
    ];

    /**
     * The page number to return. Defaults to 1.
     */
    protected int $page = 1;

    /**
     * The maximum number of records to return. The default value is 50, and
     * this may not be set higher than 1000.
     */
    protected int $pageSize = 50;

    /**
     * The system-generated identifier for the user's course enrollment.
     */
    protected string $enrollmentId;

    /**
     * The status of groups to return. Acceptable values are "Active",
     * "Inactive", or "All". Mutually exclusive with the group names.
     */
    protected ?string $groupStatus = null;

    /**
     * A list of group names. If not left empty, only courses from the
     * specified group(s) will be returned by the report. Mutually exclusive
     * with the group status.
     */
    protected array $groupNames = [];

    /**
     * A list of group tags. Every element must be an instance of
     * CBS\SmarterU\DataTypes\Tag.
     */
    protected array $groupTags = [];

    /**
     * The status of courses to return. Acceptable values are "Active",
     * "Inactive", or "Archived".
     */
    protected ?string $learningModuleStatus = null;


    /**
     * The names of courses to return.
     */
    protected array $learningModuleNames = [];

    /**
     * The enrollment statuses of courses to return.
     */
    protected array $enrollmentStatuses = [];

    /**
     * The completion date range to include in the response. Each element of
     * the array must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     * If multiple tags are provided, SmarterU will evaluate them using the
     * "OR" operator.
     */
    protected array $completedDates = [];

    /**
     * The due date range to include in the response. Each element of
     * the array must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     * If multiple tags are provided, SmarterU will evaluate them using the
     * "OR" operator.
     */
    protected array $dueDates = [];

    /**
     * The course enrollment date range to include in the response. Each element of
     * the array must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     * If multiple tags are provided, SmarterU will evaluate them using the
     * "OR" operator.
     */
    protected array $enrolledDates = [];

    /**
     * The grace period date range to include in the response. Each element of
     * the array must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     * If multiple tags are provided, SmarterU will evaluate them using the
     * "OR" operator.
     */
    protected array $gracePeriodDates = [];

    /**
     * The last accessed date range to include in the response. Each element of
     * the array must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     * If multiple tags are provided, SmarterU will evaluate them using the
     * "OR" operator.
     */
    protected array $lastAccessedDates = [];

    /**
     * The start date range to include in the response. Each element of
     * the array must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     * If multiple tags are provided, SmarterU will evaluate them using the
     * "OR" operator.
     */
    protected array $startedDates = [];

    /**
     * The date range during which the enrollment was created.
     */
    protected ?DateRangeTag $createdDate = null;

    /**
     * The date range during which the enrollment was last modified.
     */
    protected ?DateRangeTag $modifiedDate = null;

    /**
     * The User's status. Acceptable values are "Active", "Inactive", or "All".
     * Mutually exclusive with the users' email addresses and employee IDs.
     */
    protected ?string $userStatus = null;

    /**
     * An array of email addresses identifying the specific Users to include
     * in the report. Mutually exclusive with the users' status. A User can be
     * identified using either email address or employee ID, so there is no
     * need to include the same User in both of these categories.
     */
    protected array $userEmailAddresses = [];

    /**
     * An array of employee IDs identifying the specific Users to include
     * in the report. Mutually exclusive with the users' status. A User can be
     * identified using either email address or employee ID, so there is no
     * need to include the same User in both of these categories.
     */
    protected array $userEmployeeIds = [];

    /**
     * An array of additional columns to include in the report.
     */
    protected array $columns = [];

    /**
     * An array of custom user fields to be included in the report.
     */
    protected array $customFields = [];

    /**
     * Get the page number to return.
     *
     * @return int The page number to return.
     */
    public function getPage(): int {
        return $this->page;
    }

    /**
     * Set the page number to return.
     *
     * @param int $page The page number to return.
     * @return self
     */
    public function setPage(int $page): self {
        $this->page = $page;
        return $this;
    }

    /**
     * Get the maximum number of records to return.
     *
     * @return int The maximum number of records to return.
     */
    public function getPageSize(): int {
        return $this->pageSize;
    }

    /**
     * Set the maximum number of records to return. May not be set to more than
     * 1000. If the value passed in is greater than 1000, $pageSize will be set
     * to 1000 instead of the provided value.
     *
     * @param int $pageSize The maximum number of records to return.
     * @return self
     */
    public function setPageSize(int $pageSize): self {
        $this->pageSize = min($pageSize, self::MAX_PAGE_SIZE);
        return $this;
    }

    /**
     * Get the system-generated identifier for the user's course enrollment.
     *
     * @return string The system-generated identifier.
     */
    public function getEnrollmentId(): string {
        return $this->enrollmentId;
    }

    /**
     * Set the system-generated identifier for the user's course enrollment.
     *
     * @param string $enrollmentId The system-generated identifier.
     * @return self
     */
    public function setEnrollmentId(string $enrollmentId): self {
        $this->enrollmentId = $enrollmentId;
        return $this;
    }

    /**
     * Get the status of Groups to return.
     *
     * @return ?string The status of Groups to return.
     */
    public function getGroupStatus(): ?string {
        return $this->groupStatus;
    }

    /**
     * Set the status of Groups to return. May only be set to 'Active',
     * 'Inactive', or 'All'. Mutually exclusive with the Group names.
     *
     * @param string $groupStatus The status of Groups to return.
     * @return self
     * @throws InvalidArgumentException If "$groupStatus" is not one of the
     *      values accepted by the SmarterU API.
     */
    public function setGroupStatus(string $groupStatus): self {
        if (
            $groupStatus !== self::STATUS_ACTIVE
            && $groupStatus !== self::STATUS_INACTIVE
            && $groupStatus !== self::STATUS_ALL
        ) {
            throw new InvalidArgumentException(
                '"$groupStatus" may only be "Active", "Inactive", or "All".'
            );
        }
        $this->groupStatus = $groupStatus;
        $this->groupNames = [];
        return $this;
    }

    /**
     * Get the list of Group names. If this array is not empty, the report
     * will only return courses from the specified Group(s).
     *
     * @return string[] The list of Group names.
     */
    public function getGroupNames(): array {
        return $this->groupNames;
    }

    /**
     * Set the list of Group names. If this array is not empty, the report
     * will only return courses from the specified Groups. Mutually exclusive
     * with the Group status.
     *
     * @param string[] $groupNames The list of Group names.
     * @return self
     * @throws InvalidArgumentException If one of the provided Group names is
     *      not a string.
     */
    public function setGroupNames(array $groupNames): self {
        foreach ($groupNames as $name) {
            if (!is_string($name)) {
                throw new InvalidArgumentException(
                    '"$groupNames" must be an array of strings.'
                );
            }
        }
        $this->groupNames = $groupNames;
        $this->groupStatus = null;
        return $this;
    }

    /**
     * Get the list of Group Tags. If this array is not empty, the report will
     * only return courses from Groups that have the specified Tags.
     *
     * @return Tag[] The list of Group Tags.
     */
    public function getGroupTags(): array {
        return $this->groupTags;
    }

    /**
     * Set the list of Group Tags. If this array is not empty, the report will
     * only return courses from Groups that have the specified Tags. Every
     * value passed in must be an instance of CBS\SmarterU\DataTypes\Tag.
     *
     * @param Tag[] $groupTags The list of Group Tags.
     * @return self
     * @throws InvalidArgumentException If one of the provided Tags is not
     *      an instance of CBS\SmarterU\DataTypes\Tag.
     */
    public function setGroupTags(array $groupTags): self {
        foreach ($groupTags as $tag) {
            if (!($tag instanceof Tag)) {
                throw new InvalidArgumentException(
                    '"$groupTags" must be an array of CBS\SmarterU\DataTypes\Tag instances.'
                );
            }
        }
        $this->groupTags = $groupTags;
        return $this;
    }

    /**
     * Get the status of courses to return.
     *
     * @return ?string The status of courses to return.
     */
    public function getLearningModuleStatus(): ?string {
        return $this->learningModuleStatus;
    }

    /**
     * Set the status of courses to return. Acceptable values are "Active",
     * "Inactive", or "Archived". Any other value will result in an exception.
     *
     * @param string $learningModuleStatus The status of courses to return.
     * @return self
     * @throws InvalidArgumentException If the status is not one of the valid
     *      statuses recognized by the SmarterU API.
     */
    public function setLearningModuleStatus(string $learningModuleStatus): self {
        if (
            $learningModuleStatus !== self::STATUS_ACTIVE
            && $learningModuleStatus !== self::STATUS_INACTIVE
            && $learningModuleStatus !== self::STATUS_ARCHIVED
        ) {
            throw new InvalidArgumentException(
                '"$learningModuleStatus" must be either "Active", "Inactive", or "Archived".'
            );
        }
        $this->learningModuleStatus = $learningModuleStatus;
        return $this;
    }

    /**
     * Get the names of courses to return.
     *
     * @return string[] The names of courses to return.
     */
    public function getLearningModuleNames(): array {
        return $this->learningModuleNames;
    }

    /**
     * Set the names of courses to return.
     *
     * @param string[] $learningModuleNames The names of the courses to return.
     * @return self
     * @throws InvalidArgumentException If one of the names is not a string.
     */
    public function setLearningModuleNames(array $learningModuleNames): self {
        foreach ($learningModuleNames as $name) {
            if (!is_string($name)) {
                throw new InvalidArgumentException(
                    '"$learningModuleNames" must be an array of strings.'
                );
            }
        }
        $this->learningModuleNames = $learningModuleNames;
        return $this;
    }

    /**
     * Get the enrollment statuses of courses to return.
     *
     * @return string[] The enrollment statuses of courses to return.
     */
    public function getEnrollmentStatuses(): array {
        return $this->enrollmentStatuses;
    }

    /**
     * Set the enrollment statuses of courses to return. All values in the
     * array must be contained in the "VALID_ENROLLMENT_STATUSES" array
     * defined above.
     *
     * @param string[] $enrollmentStatuses The enrollment statuses of courses
     *      to return.
     * @return self
     * @throws InvalidArgumentException If any of the values passed in are
     *      not strings, or are not one of the valid statuses.
     */
    public function setEnrollmentStatuses(array $enrollmentStatuses): self {
        foreach ($enrollmentStatuses as $status) {
            if (!is_string($status)) {
                throw new InvalidArgumentException(
                    '"$enrollmentStatuses" must be an array of strings.'
                );
            }
            if (!in_array($status, self::VALID_ENROLLMENT_STATUSES)) {
                throw new InvalidArgumentException(
                    '"$enrollmentStatuses" must contain only valid statuses recognized by the SmarterU API.'
                );
            }
        }
        $this->enrollmentStatuses = $enrollmentStatuses;
        return $this;
    }

    /**
     * Get the completion date range to include in the response.
     *
     * @return DateRangeTag[] The completion date range.
     */
    public function getCompletedDates(): array {
        return $this->completedDates;
    }

    /**
     * Set the completion date range to include in the response.
     *
     * @param DateRangeTag[] $completedDates The completion date range.
     * @return self
     * @throws InvalidArgumentException If $completedDates includes a
     *      value that is not an instance of
     *      CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function setCompletedDates(array $completedDates): self {
        foreach ($completedDates as $date) {
            if (!($date instanceof DateRangeTag)) {
                throw new InvalidArgumentException(
                    '"$completedDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
                );
            }
        }
        $this->completedDates = $completedDates;
        return $this;
    }

    /**
     * Get the due date range to include in the response.
     *
     * @return DateRangeTag[] The due date range.
     */
    public function getDueDates(): array {
        return $this->dueDates;
    }

    /**
     * Set the due date range to include in the response.
     *
     * @param DateRangeTag[] $dueDates The due date range.
     * @return self
     * @throws InvalidArgumentException If $dueDates includes a value that
     *      is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function setDueDates(array $dueDates): self {
        foreach ($dueDates as $date) {
            if (!($date instanceof DateRangeTag)) {
                throw new InvalidArgumentException(
                    '"$dueDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
                );
            }
        }
        $this->dueDates = $dueDates;
        return $this;
    }

    /**
     * Get the enrolled date range to include in the response.
     *
     * @return DateRangeTag[] The enrolled date range.
     */
    public function getEnrolledDates(): array {
        return $this->enrolledDates;
    }

    /**
     * Set the enrolled date range to include in the response.
     *
     * @param DateRangeTag[] $enrolledDates The enrolled date range.
     * @return self
     * @throws InvalidArgumentException If $enrolledDates includes a value that
     *      is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function setEnrolledDates(array $enrolledDates): self {
        foreach ($enrolledDates as $date) {
            if (!($date instanceof DateRangeTag)) {
                throw new InvalidArgumentException(
                    '"$enrolledDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
                );
            }
        }
        $this->enrolledDates = $enrolledDates;
        return $this;
    }

    /**
     * Get the grace period date range to include in the response.
     *
     * @return DateRangeTag[] The grace period date range.
     */
    public function getGracePeriodDates(): array {
        return $this->gracePeriodDates;
    }

    /**
     * Set the grace period date range to include in the response.
     *
     * @param DateRangeTag[] $gracePeriodDates The grace period date range.
     * @return self
     * @throws InvalidArgumentException If $gracePeriodDates includes a value that
     *      is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function setGracePeriodDates(array $gracePeriodDates): self {
        foreach ($gracePeriodDates as $date) {
            if (!($date instanceof DateRangeTag)) {
                throw new InvalidArgumentException(
                    '"$gracePeriodDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
                );
            }
        }
        $this->gracePeriodDates = $gracePeriodDates;
        return $this;
    }

    /**
     * Get the last accessed date range to include in the response.
     *
     * @return DateRangeTag[] The last accessed date range.
     */
    public function getLastAccessedDates(): array {
        return $this->lastAccessedDates;
    }

    /**
     * Set the last accessed date range to include in the response.
     *
     * @param DateRangeTag[] $lastAccessedDates The last accessed date range.
     * @return self
     * @throws InvalidArgumentException If $lastAccessedDates includes a value that
     *      is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function setLastAccessedDates(array $lastAccessedDates): self {
        foreach ($lastAccessedDates as $date) {
            if (!($date instanceof DateRangeTag)) {
                throw new InvalidArgumentException(
                    '"$lastAccessedDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
                );
            }
        }
        $this->lastAccessedDates = $lastAccessedDates;
        return $this;
    }

    /**
     * Get the started date range to include in the response.
     *
     * @return DateRangeTag[] The started date range.
     */
    public function getStartedDates(): array {
        return $this->startedDates;
    }

    /**
     * Set the started date range to include in the response.
     *
     * @param DateRangeTag[] $startedDates The due date range.
     * @return self
     * @throws InvalidArgumentException If $startedDates includes a value that
     *      is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
     */
    public function setStartedDates(array $startedDates): self {
        foreach ($startedDates as $date) {
            if (!($date instanceof DateRangeTag)) {
                throw new InvalidArgumentException(
                    '"$startedDates" must be an array of CBS\SmarterU\Queries\Tags\DateRangeTag.'
                );
            }
        }
        $this->startedDates = $startedDates;
        return $this;
    }

    /**
     * Get the date range during which the enrollment was created.
     *
     * @return ?DateRangeTag The date range during which the enrollment was
     *      created.
     */
    public function getCreatedDate(): ?DateRangeTag {
        return $this->createdDate;
    }

    /**
     * Set the date range during which the enrollment was created.
     *
     * @param DateRangeTag $createdDate The date range during which the
     *      enrollment was created.
     * @return self
     */
    public function setCreatedDate(DateRangeTag $createdDate): self {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get the date range during which the enrollment was last modified.
     *
     * @return ?DateRangeTag The date range during which the enrollment was
     *      last modified.
     */
    public function getModifiedDate(): ?DateRangeTag {
        return $this->modifiedDate;
    }

    /**
     * Set the date range during which the enrollment was last modified.
     *
     * @param DateRangeTag $modifiedDate The date range during which the
     *      enrollment was last modified.
     * @return self
     */
    public function setModifiedDate(DateRangeTag $modifiedDate): self {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * Get the User's status.
     *
     * @return ?string The User's status.
     */
    public function getUserStatus(): ?string {
        return $this->userStatus;
    }

    /**
     * Set the User's status.
     *
     * @param string $userStatus The User's status.
     * @return self
     * @throws InvalidArgumentException If the provided value is not one of
     *      the values accepted by the SmarterU API.
     */
    public function setUserStatus(string $userStatus): self {
        if (
            $userStatus !== self::STATUS_ACTIVE
            && $userStatus !== self::STATUS_INACTIVE
            && $userStatus !== self::STATUS_ALL
        ) {
            throw new InvalidArgumentException(
                '"$userStatus" may only be set to "Active", "Inactive", or "All".'
            );
        }
        $this->userStatus = $userStatus;
        $this->userEmailAddresses = [];
        $this->userEmployeeIds = [];
        return $this;
    }

    /**
     * Get the array of email addresses identifying the specific Users to
     * include in the report.
     *
     * @return string[] The array of email addresses.
     */
    public function getUserEmailAddresses(): array {
        return $this->userEmailAddresses;
    }

    /**
     * Set the array of email addresses identifying the specific Users to
     * include in the report.
     *
     * @param string[] $userEmailAddresses The array of email addresses.
     * @return self
     * @throws InvalidArgumentException If $userEmailAddresses contains a value
     *      that is not a string.
     */
    public function setUserEmailAddresses(array $userEmailAddresses): self {
        foreach ($userEmailAddresses as $email) {
            if (!is_string($email)) {
                throw new InvalidArgumentException(
                    '"$userEmailAddresses" must be an array of email addresses as strings.'
                );
            }
        }
        $this->userEmailAddresses = $userEmailAddresses;
        $this->userStatus = null;
        return $this;
    }

    /**
     * Get the array of employee IDs identifying the specific Users to include
     * in the report.
     *
     * @return string[] The array of employee IDs.
     */
    public function getUserEmployeeIds(): array {
        return $this->userEmployeeIds;
    }

    /**
     * Set the array of employee IDs identifying the specific Users to include
     * in the report.
     *
     * @param string[] $userEmployeeIds The array of employee IDs.
     * @return self
     * @throws InvalidArgumentException If "$userEmployeeIds" contains a value
     *      that is not a string.
     */
    public function setUserEmployeeIds(array $userEmployeeIds): self {
        foreach ($userEmployeeIds as $id) {
            if (!is_string($id)) {
                throw new InvalidArgumentException(
                    '"$userEmployeeIds" must be an array of employee IDs as strings.'
                );
            }
        }
        $this->userEmployeeIds = $userEmployeeIds;
        $this->userStatus = null;
        return $this;
    }

    /**
     * Get the array of additional columns to include in the report.
     *
     * @return string[] The array of additional columns.
     */
    public function getColumns(): array {
        return $this->columns;
    }

    /**
     * Set the array of additional columns to include in the report.
     *
     * @param string[] $columns The array of additional columns.
     * @return self
     * @throws InvalidArgumentException If one of the provided columns is not
     *      one of the columns accepted by the SmarterU API.
     */
    public function setColumns(array $columns): self {
        foreach ($columns as $column) {
            if (!in_array($column, self::VALID_COLUMNS)) {
                throw new InvalidArgumentException(
                    '"$columns" may only contain the columns defined by the SmarterU API.'
                );
            }
        }
        $this->columns = $columns;
        return $this;
    }

    /**
     * Get the array of custom user fields to be included in the report.
     *
     * @return CustomField[] The array of custom user fields.
     */
    public function getCustomFields(): array {
        return $this->customFields;
    }

    /**
     * Set the array of custom user fields to be included in the report.
     *
     * @param CustomField[] $customFields The array of custom user fields.
     * @return self
     * @throws InvalidArgumentException If $customFields contains a value that
     *      is not an instance of CBS\SmarterU\DataTypes\CustomField.
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
}
