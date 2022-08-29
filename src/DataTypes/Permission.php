<?php

/**
 * Contains SmarterU\DataTypes\Permission.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/20
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * The Permission class represents a permission to be granted or denied to a
 * User within a Group.
 */
class Permission {
    /**
     * The action to be taken. Acceptable values are either 'Grant' or 'Deny'.
     */
    protected ?string $action;

    /**
     * The specific code for the permission to be granted. Acceptable values
     * are as follows:
     * 'MANAGE_GROUP' => Group Manager
     * 'CREATE_COURSE' => Create Course
     * 'MANAGE_GROUP_COURSES' => Manage Group Courses
     * 'MANAGE_USERS' => Manage Users
     * 'MANAGE_GROUP_USERS' => Manage Group Users
     * 'VIEW_LEARNER_RESULTS' => View Learner Results
     * 'PROCTOR' => Quiz Proctor
     * 'MARKER' => Long Answer Quiz Marker
     * 'INSTRUCTOR' => Instructor-Led Training Instructor
     */
    protected string $code;

    /**
     * Get the action to be taken.
     *
     * @return ?string the action to be taken.
     */
    public function getAction(): ?string {
        return $this->action;
    }

    /**
     * Set the action to be taken.
     *
     * @param string $action the action to be taken
     * @return self
     */
    public function setAction(string $action): self {
        $this->action = $action;
        return $this;
    }

    /**
     * Get the code for the permission to be granted.
     *
     * @return string the code for the permission to be granted
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * Set the code for the permission to be granted.
     *
     * @param string $code the code for the permission to be granted
     * @return self
     */
    public function setCode(string $code): self {
        $this->code = $code;
        return $this;
    }
}
