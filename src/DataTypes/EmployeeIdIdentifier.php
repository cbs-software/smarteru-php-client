<?php

/**
 * Contains CBS\SmarterU\DataTypes\EmployeeIdIdentifier.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * Represents a user identifier that is an employee id.
 */
class EmployeeIdIdentifier implements IUserIdentifier {
    public function __construct(protected readonly string $employeeId){}

    public function getUserIdentifier(): string {
        return $this->employeeId;
    }

    public function getApiType(): string {
        return 'EmployeeID';
    }
}
