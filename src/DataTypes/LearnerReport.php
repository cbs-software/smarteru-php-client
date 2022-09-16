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

use DateTimeInterface;

/**
 * Represents a LearnerReport within SmarterU.
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
     * The user's system-generated identifier.
     */
    protected string $userId;

    protected ?string $userEmail;

    protected ?string $alternateEmail;

    protected ?string $employeeId;

    protected ?string $division;

    protected ?string $title;

    protected ?string $groupId;
    
    protected ?string $groupName;

    protected ?string $courseDuration;

    protected ?string $courseSessionId;

    /**
     * The UTC date the enrollment was created.
     */
    protected DateTimeInterface $createdDate;

    /**
     * The UTC date the enrollment was last updated.
     */
    protected DateTimeInterface $modifiedDate;

    protected ?DateTimeInterface $enrolledDate;

    protected ?DateTimeInterface $dueDate;

    protected ?DateTimeInterface $startedDate;

    protected ?DateTimeInterface $lastAccessedDate;

    protected ?DateTimeInterface $completedDate;

    protected ?string $grade;

    protected ?double $gradePercentage;

    protected ?string $points;

    protected ?string $progress;

    protected ?string $subscriptionName;

    protected ?string $variantName;

    protected ?DateTimeInterface $variantStartDate;

    protected ?DateTimeInterface $variantEndDate;

    protected ?string $roleId;

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
        return $this->Surname;
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
        $this->customFields = $customFields;
        return $this;
    }
}
