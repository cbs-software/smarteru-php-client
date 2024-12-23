<?php

/**
 * Contains CBS\SmarterU\Queries\ListGroupsQuery.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries;

use CBS\SmarterU\Queries\Tags\MatchTag;

/**
 * Represents a listGroups query made to the SmarterU API.
 */
class ListGroupsQuery extends BaseQuery {
    /**
     * The container for group name filters.
     */
    protected ?MatchTag $groupName = null;

    /**
     * The group status filter. Acceptable values are 'ACTIVE' or 'INACTIVE'.
     */
    protected ?string $groupStatus = null;

    /**
     * A container for group tag filters. Each element must be an instance of
     * CBS\SmarterU\DataTypes\Tag.
     */
    protected ?array $tags = null;

    /**
     * Get the container for group name filters.
     *
     * @return ?MatchTag The group name filters.
     */
    public function getGroupName(): ?MatchTag {
        return $this->groupName;
    }

    /**
     * Set the container for group name filters.
     *
     * @param MatchTag $groupName The group name filters
     * @return self
     */
    public function setGroupName(MatchTag $groupName): self {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * Get the group status.
     *
     * @return ?string The group's status
     */
    public function getGroupStatus(): ?string {
        return $this->groupStatus;
    }

    /**
     * Set the group status.
     *
     * @param string $groupStatus The group's status
     * @return self
     */
    public function setGroupStatus(string $groupStatus): self {
        $this->groupStatus = $groupStatus;
        return $this;
    }

    /**
     * Get the container for group tag filters.
     *
     * @return ?array the group tag filters
     */
    public function getTags(): ?array {
        return $this->tags;
    }

    /**
     * Set the container for group tag filters.
     *
     * @param array $tags the group tag filters
     * @return self
     */
    public function setTags(array $tags): self {
        $this->tags = $tags;
        return $this;
    }
}
