<?php

/**
 * Contains CBS\SmarterU\DataTypes\EmailAddressIdentifier.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * Represents a user identifier that is an email address.
 */
class EmailAddressIdentifier implements IUserIdentifier {
    public function __construct(protected readonly string $emailAddress){}

    public function getUserIdentifier(): string {
        return $this->emailAddress;
    }

    public function getApiType(): string {
        return 'Email';
    }
}
