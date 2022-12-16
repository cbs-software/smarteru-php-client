<?php

/**
 * Contains CBS\SmarterU\DataTypes\Tag
 *
 * @author    Brian Reich <brian.reich@thecoresolution.com>
 * @copyright $year$ Core Business Solutions
 * @license   MIT
 * @since     2022/08/01
 * @version   $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * Tag is a classification that can be applied to things like Groups in
 * SmarterU.
 *
 * @see https://help.smarteru.com/api-creategroup$tags2_tag_group
 */
class Tag {
    #region Properties

    /**
     * The ID of the tag. Either the ID or the name must be set when adding
     * a tag to a Group.
     */
    protected ?string $tagId = null;

    /**
     * The name of the tag. Either the ID or the name must be set when adding
     * a tag to a Group.
     */
    protected ?string $tagName = null;

    /**
     * A comma-separated list of the tag's values.
     */
    protected string $tagValues;

    #endregion Properties

    #region Getters and Setters

    /**
     * Sets the tag id.
     *
     * @param string $tagId The tag id.
     * @return self
     */
    public function setTagId(string $tagId): self {
        $this->tagId = $tagId;
        return $this;
    }

    /**
     * Returns the tag id.
     *
     * @return ?string Returns the tag id.
     */
    public function getTagId(): ?string {
        return $this->tagId;
    }

    /**
     * Sets the tag name.
     *
     * @param string $tagName The tag name
     * @return self
     */
    public function setTagName(string $tagName): self {
        $this->tagName = $tagName;
        return $this;
    }

    /**
     * Returns the tag name.
     *
     * @return ?string Returns the tag name.
     */
    public function getTagName(): ?string {
        return $this->tagName;
    }

    /**
     * Sets a comma separated list of tag values.
     *
     * @param string $tagValues A comma separated list of tag values.
     * @return self
     */
    public function setTagValues(string $tagValues): self {
        $this->tagValues = $tagValues;
        return $this;
    }

    /**
     * Returns a comma separated list of tag values.
     *
     * @return string Returns a comma separated list of tag values.
     */
    public function getTagValues(): string {
        return $this->tagValues;
    }

    #endregion Getters and Setters
}
