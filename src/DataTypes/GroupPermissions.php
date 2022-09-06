<?php

/**
 * Contains CBS\SmarterU\DataTypes\GroupPermissions.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/20
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\Permission;

/**
 * The GroupPermissions class represents a User's affiliation with a Group
 * and the permissions that User has within the Group.
 *
 * Note: All GroupPermissions instances must contain the "permissions" array.
 * The GroupPermisisons instance used in User::$groups must contain either the
 * group name or group ID. The GroupPermissions instance used in Group::$users
 * must contain the home group, and either the email or employee ID.
 * Unnecessary attributes will be ignored.
 */
class GroupPermissions {
    /**
     * The name of the group the user is a member of. Mutually exclusive with
     * the group's ID.
     */
    protected ?string $groupName = null;

    /**
     * The user-specified ID of the group the user is a member of. This is the
     * GroupID returned by the getGroup and listGroups methods. Mutually
     * exclusive with the group's name.
     */
    protected ?string $groupId = null;

    /**
     * The email address of the user you want to assign to the group. The user
     * must already exist within your SmarterU account. This tag is mutually
     * exclusive with the EmployeeID tag. This is the Email returned by the
     * getUser and listUsers methods.
     */
    protected ?string $email = null;

    /**
     * The employee ID of the user you want to assign to the group. The user
     * must already exist within your SmarterU account. This tag is mutually
     * exclusive with the Email tag. This is the EmployeeID returned by the
     * getUser and listUsers methods.
     */
    protected ?string $employeeId = null;

    /**
     * Specifies if this group will be the user's home group.
     */
    protected ?bool $homeGroup;

    /**
     * Specifies if the User is to be added or removed from the group.
     * Acceptable values are 'Add' or 'Remove'. Only necessary when making an
     * updateUser or updateGroup query.
     */
    protected ?string $action = null;

    /**
     * A container for the permissions to be granted to the user. Elements must
     * be an instance of CBS\SmarterU\DataTypes\Permission.
     */
    protected array $permissions = [];

    /**
     * Get the name of the group the user is a member of.
     *
     * @return ?string the name of the group the user is a member of
     */
    public function getGroupName(): ?string {
        return $this->groupName;
    }

    /**
     * Set the name of the group the user is a member of.
     *
     * @param string $groupName the name of the group the user is a member of
     * @return self
     */
    public function setGroupName(string $groupName): self {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * Get the user-specified ID of the group the user is a member of.
     *
     * @return ?string the ID of the group the user is a member of.
     */
    public function getGroupId(): ?string {
        return $this->groupId;
    }

    /**
     * Set the user-specified ID of the group the user is a member of.
     *
     * @param string $groupId the id of the group the user is a member of
     * @return self
     */
    public function setGroupId(string $groupId): self {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * Get the email address of the user you want to assign to the group.
     *
     * @return ?string the email address of the user assigned to the group
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Set the email address of the user you want to assign to the group.
     *
     * @param string $email the email address of the user assigned to the group
     */
    public function setEmail(string $email): self {
        $this->email = $email;
        $this->employeeId = null;
        return $this;
    }

    /**
     * Get the employee ID of the user you want to assign to the group.
     *
     * @return ?string the employee ID of the user assigned to the group
     */
    public function getEmployeeId(): ?string {
        return $this->employeeId;
    }

    /**
     * Set the employee ID of the user you want to assign to the group.
     *
     * @param string $employeeId the employee ID of the user assigned to the group
     * @return self
     */
    public function setEmployeeId(string $employeeId): self {
        $this->employeeId = $employeeId;
        $this->email = null;
        return $this;
    }

    /**
     * Get whether the user is to be added to or removed from the group.
     *
     * @return ?string whether the user is to be added to or removed from the
     *      group
     */
    public function getAction(): ?string {
        return $this->action;
    }

    /**
     * Set whether the user is to be added to or removed from the group.
     *
     * @param string $action whether the user is to be added to or removed
     *      from the group
     * @return self
     */
    public function setAction(string $action): self {
        $this->action = $action;
        return $this;
    }

    /**
     * Get whether or not this group is the user's home group.
     *
     * @return bool true if and only if this group is the user's home group
     */
    public function getHomeGroup(): ?bool {
        return $this->homeGroup;
    }

    /**
     * Set whether or not this group is the user's home group.
     *
     * @param bool $homeGroup true if and only if this group is the user's
     *      home group
     * @return self
     */
    public function setHomeGroup(bool $homeGroup): self {
        $this->homeGroup = $homeGroup;
        return $this;
    }

    /**
     * Get the container for the permissions to be granted or denied to the
     * user within the group.
     *
     * @return array the container for the permissions
     */
    public function getPermissions(): array {
        return $this->permissions;
    }

    /**
     * Set the container for the permissions to be granted or denied to the
     * user within the group.
     *
     * @param array $permissions the container for the permissions
     * @return self
     */
    public function setPermissions(array $permissions): self {
        $this->permissions = $permissions;
        return $this;
    }
}
