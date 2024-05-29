<?php

/**
 * Contains CBS\SmarterU\Queries\MatchTag
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries\Tags;

use SmarterU\Exceptions\InvalidArgumentException;

/**
 * This class represents the value passed into several different query parameters
 * determining whether to retrieve results that exactly match the input or that
 * just contain the input.
 */
class MatchTag {
    /**
     * Which type of match to retrieve. Can only be 'EXACT' or 'CONTAINS'.
     */
    protected string $matchType;

    /**
     * The value the query results must match.
     */
    protected string $value;

    /**
     * Return the type of match to retrieve.
     *
     * @return string the type of match to retrieve.
     */
    public function getMatchType(): string {
        return $this->matchType;
    }

    /**
     * Set the type of match to retrieve.
     *
     * @param string $matchType The type of match to retrieve.
     * @return self
     */
    public function setMatchType(string $matchType): self {
        $this->matchType = $matchType;
        return $this;
    }

    /**
     * Return the value the query results must match.
     *
     * @return string the value the query results must match
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * Set the value the query results must match.
     *
     * @param string $value The value the query results must match.
     * @return self
     */
    public function setValue(string $value): self {
        $this->value = $value;
        return $this;
    }
}
