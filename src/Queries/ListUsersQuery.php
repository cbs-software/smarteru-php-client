<?php

/**
 * Contains SmarterU\Queries\ListUsersQuery.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/13
 */

declare(strict_types=1);

namespace CBS\SmarterU\Queries;

use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Queries\Tags\MatchTag;
use SimpleXMLElement;

/**
 * Represents a listUsers query made to the SmarterU API.
 */
class ListUsersQuery extends BaseQuery {
    /**
     * The default user status for a query.
     */
    protected const STATUS_ALL = 'All';

    /**
     * The maximum number of users to return.
     */
    protected const MAX_PAGE_SIZE = 1000;

    /**
     * The page to get. Default is 1.
     */
    protected ?int $page = 1;

    /**
     * The maximum number of users to return. If the PageSize tag is not provided,
     * up to 50 results are returned by default. The maximum allowed value is 1000.
     */
    protected ?int $pageSize = 50;

    /**
     * The field used to sort the results. Can only be 'NAME' or 'EMPLOYEE_ID'.
     */
    protected ?string $sortField = null;

    /**
     * The direction that the results will be sorted. Can be either 'ASC' or 'DESC'.
     */
    protected ?string $sortOrder = null;

    /**
     * The tag representing the email to query for.
     */
    protected ?MatchTag $email = null;

    /**
     * The tag representing the employee ID to query for.
     */
    protected ?MatchTag $employeeId = null;

    /**
     * The tag representing the name of the user to query for.
     */
    protected ?MatchTag $name = null;

    /**
     * The name of the home group. Only users that have this group as their
     * home group will be returned.
     */
    protected ?string $homeGroup = null;

    /**
     * This is the name of a group. Only users that have been assigned to the
     * provided group will be returned.
     */
    protected ?string $groupName = null;

    /**
     * This is the status of the users to list. Values can be 'Active', 'Inactive',
     * or 'All'. Default is 'All'.
     */
    protected string $userStatus = self::STATUS_ALL;

    /**
     * The date range when the user's account was created. The dates should be
     * in the format dd-mmm-yyyy.
     */
    protected ?DateRangeTag $createdDate = null;

    /**
     * The date range when the user's account was last updated. The dates should
     * be in the format dd-mmm-yyyy.
     */
    protected ?DateRangeTag $modifiedDate = null;

    /**
     * A container for the teams that a user is assigned to.
     */
    protected ?array $teams = null;

    /**
     * Return the page to get.
     *
     * @return ?int the page to get
     */
    public function getPage(): ?int {
        return $this->page;
    }

    /**
     * Set the page to get.
     *
     * @param ?int $page the page to get
     * @return self
     */
    public function setPage(?int $page): self {
        $this->page = $page;
        return $this;
    }

    /**
     * Return the maximum number of users to return.
     *
     * @return ?int The maximum number of users to return.
     */
    public function getPageSize(): ?int {
        return $this->pageSize;
    }

    /**
     * Set the maximum number of users to return. Cannot be greater than 1000.
     *
     * @param ?int $pageSize the maximum number of users to return
     * @return self
     */
    public function setPageSize(?int $pageSize): self {
        $this->pageSize = min($pageSize, self::MAX_PAGE_SIZE);
        return $this;
    }

    /**
     * Return the field used to sort results.
     *
     * @return ?string the field used to sort results
     */
    public function getSortField(): ?string {
        return $this->sortField;
    }

    /**
     * Set the field used to sort results.
     *
     * @param ?string $sortField the field used to sort results
     * @return self
     */
    public function setSortField(?string $sortField): self {
        $this->sortField = $sortField;
        return $this;
    }

    /**
     * Return the direction the results are sorted in.
     *
     * @return ?string the field used to sort results
     */
    public function getSortOrder(): ?string {
        return $this->sortOrder;
    }

    /**
     * Set the direction the results are sorted in.
     *
     * @param ?string $sortOrder the direction the results are sorted in
     * @return self
     */
    public function setSortOrder(?string $sortOrder): ?self {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * Return the tag representing the email to query for.
     *
     * @return ?MatchTag the tag representing the email to query for
     */
    public function getEmail(): ?MatchTag {
        return $this->email;
    }

    /**
     * Set the tag representing the email to query for.
     *
     * @param ?MatchTag $email the email to query for
     * @return self
     */
    public function setEmail(?MatchTag $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * Return the tag representing the employee ID to query for.
     *
     * @return ?MatchTag the tag representing the employee ID to query for
     */
    public function getEmployeeId(): ?MatchTag {
        return $this->employeeId;
    }

    /**
     * Set the tag representing the employee ID to query for.
     *
     * @param ?MatchTag $employeeId the employee ID to query for
     * @return self
     */
    public function setEmployeeId(?MatchTag $employeeId): self {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * Return the tag representing the name to query for.
     *
     * @return ?MatchTag the tag representing the name to query for
     */
    public function getName(): ?MatchTag {
        return $this->name;
    }

    /**
     * Set the tag representing the name to query for.
     *
     * @param ?MatchTag $name the name to query for
     * @return self
     */
    public function setName(?MatchTag $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the name of the home group of users to query for.
     *
     * @return ?string the name of the home group
     */
    public function getHomeGroup(): ?string {
        return $this->homeGroup;
    }

    /**
     * Set the name of the home group of users to query for.
     *
     * @param string $homeGroup the name of the home group
     * @return self
     */
    public function setHomeGroup(string $homeGroup): self {
        $this->homeGroup = $homeGroup;
        return $this;
    }

    /**
     * Return the name of the group containing the users to query for.
     *
     * @return ?string the name of the group containing the users to query for
     */
    public function getGroupName(): ?string {
        return $this->groupName;
    }

    /**
     * Set the name of the group containing the users to query for.
     *
     * @param ?string $groupName the name of the group containing the users to query for
     * @return self
     */
    public function setGroupName(?string $groupName): self {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * Return the status of the users to query for.
     *
     * @return string the status of the users to query for
     */
    public function getUserStatus(): string {
        return $this->userStatus;
    }

    /**
     * Set the status of the users to query for.
     *
     * @param string $userStatus the status of the users to query for
     * @return self
     */
    public function setUserStatus(string $userStatus): self {
        $this->userStatus = $userStatus;
        return $this;
    }

    /**
     * Return the date range when the user's account was created.
     *
     * @return ?DateRangeTag the date range when the user's account was created
     */
    public function getCreatedDate(): ?DateRangeTag {
        return $this->createdDate;
    }

    /**
     * Set the date range when the user's account was created.
     *
     * @param ?DateRangeTag $createdDate the date range when the user's account
     *      was created
     * @return self
     */
    public function setCreatedDate(?DateRangeTag $createdDate): self {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Return the date range when the user's account was last updated.
     *
     * @return ?DateRangeTag the date range when the user's account was last updated
     */
    public function getModifiedDate(): ?DateRangeTag {
        return $this->modifiedDate;
    }

    /**
     * Set the date range when the user's account was last updated.
     *
     * @param ?DateRangeTag $modifiedDate the date range when the user's account
     *      was last modified
     * @return self
     */
    public function setModifiedDate(?DateRangeTag $modifiedDate): self {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * Return the container for the teams the user is assigned to.
     *
     * @return ?array the container for the teams the user is assigned to
     */
    public function getTeams(): ?array {
        return $this->teams;
    }

    /**
     * Set the container for the teams the user is assigned to.
     *
     * @param ?array the teams the user is assigned to
     * @return self
     */
    public function setTeams(?array $teams): self {
        $this->teams = $teams;
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
     */
    public function toXml(
        string $accountApi,
        string $userApi
    ): string {
        $this->setAccountApi($accountApi);
        $this->setUserApi($userApi);
        $xml = $this->createBaseXml();
        $xml->addChild('Method', 'listUsers');
        $parameters = $xml->addChild('Parameters');
        $user = $parameters->addChild('User');
        $user->addChild('Page', (string) $this->getPage());
        if (!empty($this->getPageSize())) {
            $user->addChild('PageSize', (string) $this->getPageSize());
        }
        if (!empty($this->getSortField())) {
            $user->addChild('SortField', $this->getSortField());
        }
        if (!empty($this->getSortOrder())) {
            $user->addChild('SortOrder', $this->getSortOrder());
        }
        $filters = $user->addChild('Filters');
        if ($this->includeUsersTag()) {
            $users = $filters->addChild('Users');
            $userIdentifier = $users->addChild('UserIdentifier');
            if (!empty($this->getEmail())) {
                $email = $userIdentifier->addChild('Email');
                $email->addChild('MatchType', $this->getEmail()->getMatchType());
                $email->addChild('Value', $this->getEmail()->getValue());
            }
            if (!empty($this->employeeId)) {
                $employeeId = $userIdentifier->addChild('EmployeeID');
                $employeeId->addChild('MatchType', $this->getEmployeeId()->getMatchType());
                $employeeId->addChild('Value', $this->getEmployeeId()->getValue());
            }
            if (!empty($this->name)) {
                $name = $userIdentifier->addChild('Name');
                $name->addChild('MatchType', $this->getName()->getMatchType());
                $name->addChild('Value', $this->getName()->getValue());
            }
        }
        if (!empty($this->getHomeGroup())) {
            $filters->addChild('HomeGroup', $this->getHomeGroup());
        }
        if (!empty($this->getGroupName())) {
            $filters->addChild('GroupName', $this->getGroupName());
        }
        if (!empty($this->getUserStatus())) {
            $filters->addChild('UserStatus', $this->getUserStatus());
        }
        if (!empty($this->getCreatedDate())) {
            $created = $filters->addChild('CreatedDate');
            $created->addChild('CreatedDateFrom', $this->getCreatedDate()->getDateFrom()->format('d/m/Y'));
            $created->addChild('CreatedDateTo', $this->getCreatedDate()->getDateTo()->format('d/m/Y'));
        }
        if (!empty($this->getModifiedDate())) {
            $modified = $filters->addChild('ModifiedDate');
            $modified->addChild('ModifiedDateFrom', $this->getModifiedDate()->getDateFrom()->format('d/m/Y'));
            $modified->addChild('ModifiedDateTo', $this->getModifiedDate()->getDateTo()->format('d/m/Y'));
        }
        if (!empty($this->getTeams())) {
            $teamsFilter = $filters->addChild('Teams');
            foreach ($this->getTeams() as $team) {
                $teamsFilter->addChild('TeamName', $team);
            }
        }
        return $xml->asXML();
    }

    /**
     * Determine whether or not to filter query results based on the user's
     * identifying information.
     *
     * @return bool True if and only if the query should contain a <Users> tag
     */
    private function includeUsersTag(): bool {
        return !empty($this->email) || !empty($this->employeeId) || !empty($this->name);
    }
}
