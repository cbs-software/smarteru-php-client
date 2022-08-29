<?php

/**
 * Contains CBS\SmarterU\Queries\ListGroupsQuery.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/09
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries;

use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\Tags\MatchTag;
use SimpleXMLElement;

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

    /**
     * Generate an XML representation of the query, to be passed into the
     * SmarterU API.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the individual
     *      user within the account who is making the request.
     * @return string the XML representation of the query
     * @throws MissingValueException if $tags includes a tag that does not have
     *      an identifier.
     */
    public function toXml(
        string $accountApi,
        string $userApi
    ): string {
        $this->setAccountApi($accountApi);
        $this->setUserApi($userApi);
        $xml = $this->createBaseXml();
        $xml->addChild('Method', 'listGroups');
        $parameters = $xml->addChild('Parameters');
        $group = $parameters->addChild('Group');
        $filters = $group->addChild('Filters');
        if (!empty($this->getGroupName())) {
            $groupName = $filters->addChild('GroupName');
            $groupName->addChild('MatchType', $this->getGroupName()->getMatchType());
            $groupName->addChild('Value', $this->getGroupName()->getValue());
        }
        if (!empty($this->getGroupStatus())) {
            $filters->addChild('GroupStatus', $this->getGroupStatus());
        }
        if (!empty($this->getTags())) {
            $tags = $filters->addChild('Tags2');
            foreach ($this->getTags() as $tag) {
                $tag2 = $tags->addChild('Tag2');
                if (!empty($tag->getTagId())) {
                    $tag2->addChild('TagID', $tag->getTagId());
                } else if (!empty($tag->getTagName())) {
                    $tag2->addChild('TagName', $tag->getTagName());
                } else {
                    throw new MissingValueException(
                        'Tags must include a tag identifier when creating a ListGroups query.'
                    );
                }
                $tag2->addChild('TagValues', $tag->getTagValues());
            }
        }
        return $xml->asXML();
    }
}
