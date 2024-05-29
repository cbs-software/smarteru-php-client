<?php

/**
 * Contains CBS\SmarterU\Queries\GetUserQuery
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries;

use SimpleXMLElement;
use CBS\SmarterU\Exceptions\MissingValueException;

/**
 * Represents a getUser query or a getUserGroups query made to the SmarterU API.
 */
class GetUserQuery extends BaseQuery {
    /**
     * The system-generated identifier for the user. This tag is mutually exclusive
     * with the Email and EmployeeID tags. This is the ID returned by the listUsers
     * method.
     */
    protected ?string $id = null;

    /**
     * The email address of the user. This tag is mutually exclusive with the ID
     * and EmployeeID tags. This is the Email returned by the listUsers method.
     */
    protected ?string $email = null;

    /**
     * The employee ID of the user. This tag is mutually exclusive with the ID and
     * Email tags. This is the EmployeeID returned by the listUsers method.
     */
    protected ?string $employeeId = null;

    /**
     * The SmarterU API method the query is to be used for.
     */
    protected string $method;

    /**
     * Return the system-generated identifier for the user.
     *
     * @return ?string The system-generated identifier for the user if it exists
     */
    public function getId(): ?string {
        return $this->id;
    }

    /**
     * Set the system-generated identifier for the user.
     *
     * @param string $id The system-generated identifier for the user
     * @return self
     */
    public function setId(string $id): self {
        $this->id = $id;
        $this->email = null;
        $this->employeeId = null;
        return $this;
    }

    /**
     * Return the email address of the user.
     *
     * @return ?string $email The user's email address
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Set the email address for the user.
     *
     * @param string $email The user's email address
     * @return self
     */
    public function setEmail(string $email): self {
        $this->id = null;
        $this->email = $email;
        $this->employeeId = null;
        return $this;
    }

    /**
     * Return the user's employee ID.
     *
     * @return ?string The user's employee ID
     */
    public function getEmployeeId(): ?string {
        return $this->employeeId;
    }

    /**
     * Set the employee ID for the user.
     *
     * @param string $employeeId The user's employee ID
     * @return self
     */
    public function setEmployeeId(string $employeeId): self {
        $this->id = null;
        $this->email = null;
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * Get the SmarterU API method the query is to be used for.
     *
     * @return string the name of the API method
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * Set the SmarterU API method the query is to be used for.
     *
     * @param string $method the name of the method
     * @return self
     */
    public function setMethod(string $method): self {
        $this->method = $method;
        return $this;
    }
}
