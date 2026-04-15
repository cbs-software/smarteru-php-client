<?php

/**
 * Contains CBS\SmarterU\DataTypes\IUserIdentifier.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * Represents a user identifier. This is used to specify the identify of a user
 * when making a user update request. SmarterU allows API callers to specify the
 * user as either an email address (EmailAddressIdentifier) or as an employee
 * ID (EmployeeIdIdentifier). By abstracting identity to an interface and
 * using polymorphism, we can allow API callers to specify the user in either
 * way without forcing them to use a specific method.
 */
interface IUserIdentifier{
    /**
     * Gets the user identifier as a string.
     */
    public function getUserIdentifier(): string;

    /**
     * Returns the tag name to  use when specifying this identifier in the XML.
     */
    public function getApiType(): string;
}
