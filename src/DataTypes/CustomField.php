<?php

/**
 * Contains CBS\SmarterU\DataTypes\CustomField
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/15
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * Represents a Custom Field within SmarterU.
 */
class CustomField {
    /**
     * The custom field's name.
     */
    protected string $name;

    /**
     * The custom field's value.
     */
    protected string $value;

    /**
     * Get the custom field's name.
     *
     * @return string The custom field's name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the custom field's name.
     *
     * @param string $name The custom field's name.
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the custom field's value.
     *
     * @return string The custom field's value.
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * Set the custom field's value.
     *
     * @param string $value The custom field's value.
     * @return self
     */
    public function setValue(string $value): self {
        $this->value = $value;
        return $this;
    }
}
