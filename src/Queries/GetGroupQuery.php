<?php

/**
 * Contains CBS\SmarterU\Queries\GetGroupQuery
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/05
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries;

use CBS\SmarterU\Exceptions\MissingValueException;
use SimpleXMLElement;

/**
 * Represents a getGroup query or a getUserGroups query made to the SmarterU API.
 */
class GetGroupQuery extends BaseQuery {
    /**
     * The unique name of the group to get. Mutually exclusive with the GroupID
     * tag. This is the Name returned by the listGroups method.
     */
    protected ?string $name = null;

    /**
     * The user-specified identifier assigned to the group. Mutually exclusive
     * with the Name tag. This is the GroupID returned by the listGroups method.
     */
    protected ?string $groupId = null;

    /**
     * Return the unique name of the group to get.
     *
     * @return ?string The unique name of the group to get if it exists
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * Set the unique name of the group to get.
     *
     * @param string $name The unique name of the group to get
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;
        $this->groupId = null;
        return $this;
    }

    /**
     * Get the user-specified identifier assigned to the group.
     *
     * @return ?string The The user-specified identifier assigned to the group
     */
    public function getGroupId(): ?string {
        return $this->groupId;
    }

    /**
     * Set the user-specified identifier assigned to the group
     *
     * @param string $groupId The user-specified identifier assigned to the group
     * @return self
     */
    public function setGroupId(string $groupId): self {
        $this->name = null;
        $this->groupId = $groupId;
        return $this;
    }
}
