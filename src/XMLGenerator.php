<?php

/**
 * Contains CBS\SmarterU\XMLGenerator.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\GetGroupQuery;
use CBS\SmarterU\Queries\GetLearnerReportQuery;
use CBS\SmarterU\Queries\GetUserQuery;
use CBS\SmarterU\Queries\ListGroupsQuery;
use CBS\SmarterU\Queries\ListUsersQuery;

/**
 * This class contains helper methods for Client that generate the XML body of
 * the request to be made to the SmarterU API.
 */
class XMLGenerator {
    /**
     * The root node for the XML submitted via the 'Package' form field in most
     * requests sent to the SmarterU API
     */
    private const ROOT_NODE_XML_STRING = '<SmarterU></SmarterU>';

    /** Error when updateUser called with SendEmailTo Self and no email address. */
    public const ERROR_EMAIL_REQUIRED_FOR_SEND_EMAIL_TO_SELF = 'Email is required when SendEmailTo is set to Self';

    /**
     * Generate the XML body for a createUser query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param User $user The User to translate to XML
     * @return string an XML representation of the User
     * @throws MissingValueException If the User to be created does not have
     *      an email address or an employee ID, or does not have a home Group.
     */
    public function createUser(
        string $accountApi,
        string $userApi,
        User $user
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'createUser');
        $parameters = $xml->addChild('Parameters');
        $userTag = $parameters->addChild('User');
        $info = $userTag->addChild('Info');
        if (empty($user->getEmail()) && empty($user->getEmployeeId())) {
            throw new MissingValueException(
                'Cannot create a User without either an email or employee ID.'
            );
        }
        if (!empty($user->getEmail())) {
            $info->addChild('Email', $user->getEmail());
        } else {
            $info->addChild('Email');
        }
        if (!empty($user->getEmployeeId())) {
            $info->addChild('EmployeeID', $user->getEmployeeId());
        } else {
            $info->addChild('EmployeeID');
        }
        $info->addChild('GivenName', $user->getGivenName());
        $info->addChild('Surname', $user->getSurname());
        $info->addChild('Password', $user->getPassword());
        if (!empty($user->getTimezone())) {
            // 2023-03-27: As per Chris Wood at Neovation, createUser expects Provided Name
            $info->addChild('Timezone', $user->getTimezone()->getProvidedName());
        }
        $info->addChild(
            'LearnerNotifications',
            $user->getLearnerNotifications() ? '1' : '0'
        );
        $info->addChild(
            'SupervisorNotifications',
            $user->getSupervisorNotifications() ? '1' : '0'
        );
        $info->addChild('SendEmailTo', $user->getSendEmailTo());
        if (!empty($user->getAlternateEmail())) {
            $info->addChild('AlternateEmail', $user->getAlternateEmail());
        }
        $info->addChild('AuthenticationType', $user->getAuthenticationType());

        $profile = $userTag->addChild('Profile');
        if (!empty($user->getSupervisors())) {
            $supervisors = $profile->addChild('Supervisors');
            foreach ($user->getSupervisors() as $supervisor) {
                $supervisors->addChild('Supervisor', $supervisor);
            }
        }
        if (!empty($user->getOrganization())) {
            $profile->addChild('Organization', $user->getOrganization());
        }
        if (!empty($user->getTeams())) {
            $teams = $profile->addChild('Teams');
            foreach ($user->getTeams() as $team) {
                $teams->addChild('Team', $team);
            }
        }
        if (!empty($user->getCustomFields())) {
            // TODO implement this. For iteration 1, we can assume this will be empty.
        }
        if (!empty($user->getLanguage())) {
            $profile->addChild('Language', $user->getLanguage());
        }
        if (!empty($user->getStatus())) {
            $profile->addChild('Status', $user->getStatus());
        }
        if (!empty($user->getTitle())) {
            $profile->addChild('Title', $user->getTitle());
        }
        if (!empty($user->getDivision())) {
            $profile->addChild('Division', $user->getDivision());
        }
        if (!empty($user->getAllowFeedback())) {
            $profile->addChild(
                'AllowFeedback',
                $user->getAllowFeedback() ? '1' : '0'
            );
        }
        if (!empty($user->getPhonePrimary())) {
            $profile->addChild('PhonePrimary', $user->getPhonePrimary());
        }
        if (!empty($user->getPhoneAlternate())) {
            $profile->addChild('PhoneAlternate', $user->getPhoneAlternate());
        }
        if (!empty($user->getPhoneMobile())) {
            $profile->addChild('PhoneMobile', $user->getPhoneMobile());
        }
        if (!empty($user->getFax())) {
            $profile->addChild('Fax', $user->getFax());
        }
        if (!empty($user->getWebsite())) {
            $profile->addChild('Website', $user->getWebsite());
        }
        if (!empty($user->getAddress1())) {
            $profile->addChild('Address1', $user->getAddress1());
        }
        if (!empty($user->getAddress2())) {
            $profile->addChild('Address2', $user->getAddress2());
        }
        if (!empty($user->getCity())) {
            $profile->addChild('City', $user->getCity());
        }
        if (!empty($user->getProvince())) {
            $profile->addChild('Province', $user->getProvince());
        }
        if (!empty($user->getCountry())) {
            $profile->addChild('Country', $user->getCountry());
        }
        if (!empty($user->getPostalCode())) {
            $profile->addChild('PostalCode', $user->getPostalCode());
        }
        if (!empty($user->getSendMailTo())) {
            $profile->addChild('SendMailTo', $user->getSendMailTo());
        }
        if (!empty($user->getRoles())) {
            // TODO implement this. For iteration 1, we can assume this is empty.
        }
        if (!empty($user->getReceiveNotifications())) {
            $profile->addChild(
                'ReceiveNotifications',
                $user->getReceiveNotifications() ? '1' : '0'
            );
        }
        if (empty($user->getHomeGroup())) {
            throw new MissingValueException('Cannot create a User without a Home Group.');
        }

        $profile->addChild('HomeGroup', $this->escapeValue($user->getHomeGroup()));

        $groups = $userTag->addChild('Groups');
        $groupTag = $groups->addChild('Group');
        $groupTag->addChild('GroupName', $this->escapeValue($user->getHomeGroup()));
        $groupPermissions = $groupTag->addChild('GroupPermissions');

        $venues = $userTag->addChild('Venues');
        if (!empty($user->getVenues())) {
            // TODO implement this. For iteration 1, we can assume it's empty.
        }

        $wages = $userTag->addChild('Wages');
        if (!empty($user->getWages())) {
            // TODO implement this. For iteration 1, we can assume it's empty.
        }

        return $xml->asXML();
    }

    /**
     * Generate the XML body for a GetUser query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param GetUserQuery $query The query to translate to XML
     * @return string an XML representation of the query
     */
    public function getUser(
        string $accountApi,
        string $userApi,
        GetUserQuery $query
    ): string {
        $query->setAccountApi($accountApi);
        $query->setUserApi($userApi);
        $xml = $query->createBaseXml();
        $xml->addChild('Method', $query->getMethod());
        $parameters = $xml->addChild('Parameters');
        $user = $parameters->addChild('User');
        if ($query->getId() !== null) {
            $user->addChild('ID', $query->getId());
        } else if ($query->getEmail() !== null) {
            $user->addChild('Email', $query->getEmail());
        } else if ($query->getEmployeeId() !== null) {
            $user->addChild('EmployeeID', $query->getEmployeeId());
        } else {
            throw new MissingValueException(
                'User identifier must be specified when creating a GetUserQuery.'
            );
        }
        return $xml->asXML();
    }

    /**
     * Generate the XML body for a ListUsers query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param ListUsersQuery $query The query to translate to XML
     * @return string an XML representation of the query
     */
    public function listUsers(
        string $accountApi,
        string $userApi,
        ListUsersQuery $query
    ): string {
        $query->setAccountApi($accountApi);
        $query->setUserApi($userApi);
        $xml = $query->createBaseXml();
        $xml->addChild('Method', 'listUsers');
        $parameters = $xml->addChild('Parameters');
        $user = $parameters->addChild('User');
        $user->addChild('Page', (string) $query->getPage());
        if (!empty($query->getPageSize())) {
            $user->addChild('PageSize', (string) $query->getPageSize());
        }
        if (!empty($query->getSortField())) {
            $user->addChild('SortField', $query->getSortField());
        }
        if (!empty($query->getSortOrder())) {
            $user->addChild('SortOrder', $query->getSortOrder());
        }
        $filters = $user->addChild('Filters');
        if ($this->listUsersIncludeUsersTag($query)) {
            $users = $filters->addChild('Users');
            $userIdentifier = $users->addChild('UserIdentifier');
            if (!empty($query->getEmail())) {
                $email = $userIdentifier->addChild('Email');
                $email->addChild('MatchType', $query->getEmail()->getMatchType());
                $email->addChild('Value', $query->getEmail()->getValue());
            }
            if (!empty($query->getEmployeeId())) {
                $employeeId = $userIdentifier->addChild('EmployeeID');
                $employeeId->addChild('MatchType', $query->getEmployeeId()->getMatchType());
                $employeeId->addChild('Value', $query->getEmployeeId()->getValue());
            }
            if (!empty($query->getName())) {
                $name = $userIdentifier->addChild('Name');
                $name->addChild('MatchType', $query->getName()->getMatchType());
                $name->addChild('Value', $query->getName()->getValue());
            }
        }
        if (!empty($query->getHomeGroup())) {
            $filters->addChild('HomeGroup', $query->getHomeGroup());
        }
        if (!empty($query->getGroupName())) {
            $filters->addChild('GroupName', $this->escapeValue($query->getGroupName()));
        }
        if (!empty($query->getUserStatus())) {
            $filters->addChild('UserStatus', $query->getUserStatus());
        }
        if (!empty($query->getCreatedDate())) {
            $created = $filters->addChild('CreatedDate');
            $created->addChild('CreatedDateFrom', $query->getCreatedDate()->getDateFrom()->format('d/m/Y'));
            $created->addChild('CreatedDateTo', $query->getCreatedDate()->getDateTo()->format('d/m/Y'));
        }
        if (!empty($query->getModifiedDate())) {
            $modified = $filters->addChild('ModifiedDate');
            $modified->addChild('ModifiedDateFrom', $query->getModifiedDate()->getDateFrom()->format('d/m/Y'));
            $modified->addChild('ModifiedDateTo', $query->getModifiedDate()->getDateTo()->format('d/m/Y'));
        }
        if (!empty($query->getTeams())) {
            $teamsFilter = $filters->addChild('Teams');
            foreach ($query->getTeams() as $team) {
                $teamsFilter->addChild('TeamName', $team);
            }
        }
        return $xml->asXML();
    }

    /**
     * Generate the XML body for an updateUser query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param User $user The User to translate to XML
     * @return string an XML representation of the User
     * @throws MissingValueException If the User being updated does not have an
     *      email address or an employee ID.
     */
    public function updateUser(
        string $accountApi,
        string $userApi,
        User $user
    ): string {
        $this->validateUpdateUser($user);
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'updateUser');
        $parameters = $xml->addChild('Parameters');
        $userTag = $parameters->addChild('User');
        $identifier = $userTag->addChild('Identifier');
        if (!empty($user->getOldEmail())) {
            $identifier->addChild('Email', $user->getOldEmail());
        } else if (!empty($user->getOldEmployeeId())) {
            $identifier->addChild('EmployeeID', $user->getOldEmployeeId());
        } else {
            // If neither of the above conditionals are true, then the
            // email address and employee ID are not being updated and
            // the current value can still be used to identify the user.
            if (!empty($user->getEmail())) {
                $identifier->addChild('Email', $user->getEmail());
            } else if (!empty($user->getEmployeeId())) {
                $identifier->addChild('EmployeeID', $user->getEmployeeId());
            } else {
                throw new MissingValueException(
                    'A User cannot be updated without either an email address or an employee ID.'
                );
            }
        }

        $info = $userTag->addChild('Info');

        $info->addChild('Email', (string) $user->getEmail());
        $info->addChild('EmployeeID', (string) $user->getEmployeeId());

        if (!empty($user->getGivenName())) {
            $info->addChild('GivenName', $user->getGivenName());
        }
        if (!empty($user->getSurname())) {
            $info->addChild('Surname', $user->getSurname());
        }
        if (!empty($user->getTimezone())) {
            $info->addChild('Timezone', $user->getTimezone()->getProvidedName());
        }
        if ($user->getLearnerNotifications() !== null) {
            $info->addChild(
                'LearnerNotifications',
                $user->getLearnerNotifications() ? '1' : '0'
            );
        }
        if ($user->getSupervisorNotifications() !== null) {
            $info->addChild(
                'SupervisorNotifications',
                $user->getSupervisorNotifications() ? '1' : '0'
            );
        }
        if (!empty($user->getSendEmailTo())) {
            $info->addChild('SendEmailTo', $user->getSendEmailTo());
        }
        if (!empty($user->getAlternateEmail())) {
            $info->addChild('AlternateEmail', $user->getAlternateEmail());
        }
        if (!empty($user->getAuthenticationType())) {
            $info->addChild(
                'AuthenticationType',
                $user->getAuthenticationType()
            );
        }

        $profile = $userTag->addChild('Profile');
        if (!empty($user->getSupervisors())) {
            $supervisors = $profile->addChild('Supervisors');
            foreach ($user->getSupervisors() as $supervisor) {
                $supervisors->addChild('Supervisor', $supervisor);
            }
        }
        if (!empty($user->getOrganization())) {
            $profile->addChild('Organization', $user->getOrganization());
        }
        if (!empty($user->getTeams())) {
            $teams = $profile->addChild('Teams');
            foreach ($user->getTeams() as $team) {
                $teams->addChild('Team', $team);
            }
        }
        if (!empty($user->getCustomFields())) {
            // TODO implement this. For iteration 1, we can assume this will be empty.
        }
        if (!empty($user->getLanguage())) {
            $profile->addChild('Language', $user->getLanguage());
        }
        if (!empty($user->getStatus())) {
            $profile->addChild('Status', $user->getStatus());
        }
        if (!empty($user->getTitle())) {
            $profile->addChild('Title', $user->getTitle());
        }
        if (!empty($user->getDivision())) {
            $profile->addChild('Division', $user->getDivision());
        }
        if (!empty($user->getAllowFeedback())) {
            $profile->addChild(
                'AllowFeedback',
                $user->getAllowFeedback() ? '1' : '0'
            );
        }
        if (!empty($user->getPhonePrimary())) {
            $profile->addChild('PhonePrimary', $user->getPhonePrimary());
        }
        if (!empty($user->getPhoneAlternate())) {
            $profile->addChild('PhoneAlternate', $user->getPhoneAlternate());
        }
        if (!empty($user->getPhoneMobile())) {
            $profile->addChild('PhoneMobile', $user->getPhoneMobile());
        }
        if (!empty($user->getFax())) {
            $profile->addChild('Fax', $user->getFax());
        }
        if (!empty($user->getWebsite())) {
            $profile->addChild('Website', $user->getWebsite());
        }
        if (!empty($user->getAddress1())) {
            $profile->addChild('Address1', $user->getAddress1());
        }
        if (!empty($user->getAddress2())) {
            $profile->addChild('Address2', $user->getAddress2());
        }
        if (!empty($user->getCity())) {
            $profile->addChild('City', $user->getCity());
        }
        if (!empty($user->getProvince())) {
            $profile->addChild('Province', $user->getProvince());
        }
        if (!empty($user->getCountry())) {
            $profile->addChild('Country', $user->getCountry());
        }
        if (!empty($user->getPostalCode())) {
            $profile->addChild('PostalCode', $user->getPostalCode());
        }
        if (!empty($user->getSendMailTo())) {
            $profile->addChild('SendMailTo', $user->getSendMailTo());
        }
        if (!empty($user->getRoles())) {
            // TODO implement this. For iteration 1, we can assume this is empty.
        }
        if (!empty($user->getReceiveNotifications())) {
            $profile->addChild(
                'ReceiveNotifications',
                $user->getReceiveNotifications() ? '1' : '0'
            );
        }
        if (!empty($user->getHomeGroup())) {
            $profile->addChild('HomeGroup', $this->escapeValue($user->getHomeGroup()));
        }
        $groups = $userTag->addChild('Groups');

        $venues = $userTag->addChild('Venues');
        if (!empty($user->getVenues())) {
            // TODO implement this. For iteration 1, we can assume it's empty.
        }

        $wages = $userTag->addChild('Wages');
        if (!empty($user->getWages())) {
            // TODO implement this. For iteration 1, we can assume it's empty.
        }

        return $xml->asXML();
    }

    /**
     * Sanity-check's a User object before using it to generate XML for an
     * updateUser request.
     *
     * The following checks are implemented:
     *
     * 1. If the User's SendEmailTo is set to Self, then the User's Email must
     *     be set. Otherwise, a MissingValueException is thrown. See
     *     https://support.smarteru.com/docs/api-updateuser#sendemailto-optional
     *     for details.
     *
     * @throws MissingValueException If the User is missing a required value.
     * @return void
     */
    public function validateUpdateUser(User $user): void {
        if ($user->getSendEmailTo() === 'Self' && empty($user->getEmail())) {
            throw new MissingValueException(self::ERROR_EMAIL_REQUIRED_FOR_SEND_EMAIL_TO_SELF);
        }
    }

    /**
     * Generate the XML body for a createGroup query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param Group $group The Group to translate to XML
     * @return string an XML representation of the Group
     * @throws MissingValueException If one of the Tags is missing both its
     *      name and its ID.
     */
    public function createGroup(
        string $accountApi,
        string $userApi,
        Group $group
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'createGroup');
        $parameters = $xml->addChild('Parameters');
        $groupTag = $parameters->addChild('Group');
        $groupTag->addChild('Name', $this->escapeValue($group->getName()));
        if (!empty($group->getGroupId())) {
            $groupTag->addChild('GroupID', $group->getGroupId());
        }
        $groupTag->addChild('Status', $group->getStatus());
        $groupTag->addChild('Description', $group->getDescription());
        $groupTag->addChild('HomeGroupMessage', $group->getHomeGroupMessage());
        $notificationEmails = $groupTag->addChild('NotificationEmails');
        foreach ($group->getNotificationEmails() as $email) {
            $notificationEmails->addChild('NotificationEmail', $email);
        }

        if ($group->getUserHelpOverrideDefault() !== null) {
            $groupTag->addChild(
                'UserHelpOverrideDefault',
                $group->getUserHelpOverrideDefault() ? '1' : '0'
            );
        }
        if ($group->getUserHelpEnabled() !== null) {
            $groupTag->addChild(
                'UserHelpEnabled',
                $group->getUserHelpEnabled() ? '1' : '0'
            );
        }
        if (!empty($group->getUserHelpEmail())) {
            $groupTag->addChild(
                'UserHelpEmail',
                implode(',', $group->getUserHelpEmail())
            );
        }
        if (!empty($group->getUserHelpText())) {
            $groupTag->addChild('UserHelpText', $group->getUserHelpText());
        }
        if (!empty($group->getTags())) {
            $tags2 = $groupTag->addChild('Tags2');
            foreach ($group->getTags() as $tag) {
                $tag2 = $tags2->addChild('Tag2');
                if (!empty($tag->getTagId())) {
                    $tag2->addChild('TagID', $tag->getTagId());
                } else if (!empty($tag->getTagName())) {
                    $tag2->addChild('TagName', $tag->getTagName());
                } else {
                    throw new MissingValueException(
                        'Every tag must have either a name or an ID.'
                    );
                }
                $tag2->addChild('TagValues', $tag->getTagValues());
            }
        }
        if (
            !empty($group->getUserLimitEnabled())
            && !empty($group->getUserLimitAmount())
        ) {
            $userLimit = $groupTag->addChild('UserLimit');
            $userLimit->addChild(
                'Enabled',
                $group->getUserLimitEnabled() ? '1' : '0'
            );
            $userLimit->addChild(
                'Amount',
                (string) $group->getUserLimitAmount()
            );
        }
        $users = $groupTag->addChild('Users');
        $learningModules = $groupTag->addChild('LearningModules');
        foreach ($group->getLearningModules() as $module) {
            $learningModule = $learningModules->addChild('LearningModule');
            $learningModule->addChild('ID', $module->getId());
            $learningModule->addChild(
                'AllowSelfEnroll',
                $module->getAllowSelfEnroll() ? '1' : '0'
            );
            $learningModule->addChild(
                'AutoEnroll',
                $module->getAutoEnroll() ? '1' : '0'
            );
        }
        if (!empty($group->getSubscriptionVariants())) {
            $subscriptionVariants = $groupTag->addChild(
                'SubscriptionVariants'
            );
            foreach ($group->getSubscriptionVariants() as $variant) {
                $subscriptionVariant = $subscriptionVariants->addChild(
                    'SubscriptionVariant'
                );
                $subscriptionVariant->addChild('ID', $variant->getId());
                $subscriptionVariant->addChild(
                    'RequiresCredits',
                    $variant->getRequiresCredits() ? '1' : '0'
                );
            }
        }
        if (!empty($group->getDashboardSetId())) {
            $groupTag->addChild('DashboardSetID', $group->getDashboardSetId());
        }

        return $xml->asXML();
    }

    /**
     * Generate the XML body for a getGroup query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param GetGroupQuery $query The query to translate to XML
     * @return string an XML representation of the query
     * @throws MissingValueException if the group identifier is not set.
     */
    public function getGroup(
        string $accountApi,
        string $userApi,
        GetGroupQuery $query
    ): string {
        $query->setAccountApi($accountApi);
        $query->setUserApi($userApi);
        $xml = $query->createBaseXml();
        $xml->addChild('Method', 'getGroup');
        $parameters = $xml->addChild('Parameters');
        $group = $parameters->addChild('Group');
        if ($query->getName() !== null) {
            $group->addChild('Name', $query->getName());
        } else if ($query->getGroupId() !== null) {
            $group->addChild('GroupID', $query->getGroupId());
        } else {
            throw new MissingValueException(
                'Group identifier must be specified when creating a GetGroupQuery.'
            );
        }
        return $xml->asXML();
    }

    /**
     * Generate the XML body for a listGroups query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param ListGroupsQuery $query The query to translate to XML
     * @return string an XML representation of the query
     * @throws MissingValueException If one of the Tags by which to filter the
     *      Groups is missing both its name and its ID.
     */
    public function listGroups(
        string $accountApi,
        string $userApi,
        ListGroupsQuery $query
    ): string {
        $query->setAccountApi($accountApi);
        $query->setUserApi($userApi);
        $xml = $query->createBaseXml();
        $xml->addChild('Method', 'listGroups');
        $parameters = $xml->addChild('Parameters');
        $group = $parameters->addChild('Group');
        $filters = $group->addChild('Filters');
        if (!empty($query->getGroupName())) {
            $groupName = $filters->addChild('GroupName');
            $groupName->addChild(
                'MatchType',
                $query->getGroupName()->getMatchType()
            );
            $groupName->addChild(
                'Value',
                $query->getGroupName()->getValue()
            );
        }
        if (!empty($query->getGroupStatus())) {
            $filters->addChild('GroupStatus', $query->getGroupStatus());
        }
        if (!empty($query->getTags())) {
            $tags = $filters->addChild('Tags2');
            foreach ($query->getTags() as $tag) {
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

    /**
     * Generate the XML body for an updateGroup query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param Group $group The Group to translate to XML
     * @return string an XML representation of the User
     * @throws MissingValueException If one of the Tags is missing both its
     *      name and its ID.
     */
    public function updateGroup(
        string $accountApi,
        string $userApi,
        Group $group
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'updateGroup');
        $parameters = $xml->addChild('Parameters');
        $groupTag = $parameters->addChild('Group');
        $identifier = $groupTag->addChild('Identifier');
        if (!empty($group->getOldName())) {
            $identifier->addChild('Name', $group->getOldName());
        } else if (!empty($group->getOldGroupId())) {
            $identifier->addChild('GroupID', $group->getOldGroupId());
        } else {
            // If neither of the above conditionals are true, then the
            // group name and ID are not being updated and the current
            // value can still be used to identify the group.
            $identifier->addChild('Name', $group->getName());
        }
        if (!empty($group->getOldName())) {
            $groupTag->addChild('Name', $group->getName());
        }
        if (!empty($group->getOldGroupId())) {
            $groupTag->addChild('GroupID', $group->getGroupId());
        }
        if (!empty($group->getStatus())) {
            $groupTag->addChild('Status', $group->getStatus());
        }
        if (!empty($group->getDescription())) {
            $groupTag->addChild('Description', $group->getDescription());
        }
        if (!empty($group->getHomeGroupMessage())) {
            $groupTag->addChild(
                'HomeGroupMessage',
                $group->getHomeGroupMessage()
            );
        }
        if (!empty($group->getNotificationEmails())) {
            $notificationEmails = $groupTag->addChild('NotificationEmails');
            foreach ($group->getNotificationEmails() as $email) {
                $notificationEmails->addChild('NotificationEmail', $email);
            }
        }
        if ($group->getUserHelpOverrideDefault() !== null) {
            $groupTag->addChild(
                'UserHelpOverrideDefault',
                $group->getUserHelpOverrideDefault() ? '1' : '0'
            );
        }
        if ($group->getUserHelpEnabled() !== null) {
            $groupTag->addChild(
                'UserHelpEnabled',
                $group->getUserHelpEnabled() ? '1' : '0'
            );
        }
        if (!empty($group->getUserHelpEmail())) {
            $groupTag->addChild(
                'UserHelpEmail',
                implode(',', $group->getUserHelpEmail())
            );
        }
        if (!empty($group->getUserHelpText())) {
            $groupTag->addChild('UserHelpText', $group->getUserHelpText());
        }
        if (!empty($group->getTags())) {
            $tags2 = $groupTag->addChild('Tags2');
            foreach ($group->getTags() as $tag) {
                $tag2 = $tags2->addChild('Tag2');
                if (!empty($tag->getTagId())) {
                    $tag2->addChild('TagID', $tag->getTagId());
                } else if (!empty($tag->getTagName())) {
                    $tag2->addChild('TagName', $tag->getTagName());
                } else {
                    throw new MissingValueException(
                        'Every tag must have either a name or an ID.'
                    );
                }
                $tag2->addChild('TagValues', $tag->getTagValues());
            }
        }
        if (
            !empty($group->getUserLimitEnabled())
            && !empty($group->getUserLimitAmount())
        ) {
            $userLimit = $groupTag->addChild('UserLimit');
            $userLimit->addChild(
                'Enabled',
                $group->getUserLimitEnabled() ? '1' : '0'
            );
            $userLimit->addChild(
                'Amount',
                (string) $group->getUserLimitAmount()
            );
        }
        $users = $groupTag->addChild('Users');
        $learningModules = $groupTag->addChild('LearningModules');
        foreach ($group->getLearningModules() as $module) {
            $learningModule = $learningModules->addChild('LearningModule');
            $learningModule->addChild('ID', $module->getId());
            $learningModule->addChild(
                'LearningModuleAction',
                $module->getAction()
            );
            $learningModule->addChild(
                'AllowSelfEnroll',
                $module->getAllowSelfEnroll() ? '1' : '0'
            );
            $learningModule->addChild(
                'AutoEnroll',
                $module->getAutoEnroll() ? '1' : '0'
            );
        }
        $subscriptionVariants = $groupTag->addChild('SubscriptionVariants');
        foreach ($group->getSubscriptionVariants() as $variant) {
            $subscriptionVariant = $subscriptionVariants->addChild(
                'SubscriptionVariant'
            );
            $subscriptionVariant->addChild('ID', $variant->getId());
            $subscriptionVariant->addChild(
                'SubscriptionVariantAction',
                $variant->getAction()
            );
            $subscriptionVariant->addChild(
                'RequiresCredits',
                $variant->getRequiresCredits() ? '1' : '0'
            );
        }
        if (!empty($group->getDashboardSetId())) {
            $groupTag->addChild('DashboardSetID', $group->getDashboardSetId());
        }

        return $xml->asXML();
    }

    /**
     * Generate the XML body for an addUsersToGroup or removeUsersFromGroup
     * query. Functionally, this is just an updateGroup query with most values
     * hardcoded to be left blank.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param array $users The Users who are being added to or removed from the
     *      Group.
     * @param Group $group The Group to which the Users are being added or
     *      removed.
     * @param string $action Whether the Users are being added to or removed
     *      from the Group.
     * @return string an XML representation of the query.
     * @throws InvalidArgumentException If the "$users" array contains a value
     *      that is not a User.
     * @throws MissingValueException If the "$users" array contains a User that
     *      does not have an email address or an employee ID, or if the Group
     *      does not have a name or an ID.
     */
    public function changeGroupMembers(
        string $accountApi,
        string $userApi,
        array $users,
        Group $group,
        string $action
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'updateGroup');
        $parameters = $xml->addChild('Parameters');
        $groupTag = $parameters->addChild('Group');
        $identifier = $groupTag->addChild('Identifier');
        if (!empty($group->getName())) {
            $identifier->addChild('Name', $group->getName());
        } else if (!empty($group->getGroupId())) {
            $identifier->addChild('GroupID', $group->getGroupId());
        } else {
            throw new MissingValueException(
                'Cannot add or remove users from a Group without a group name or ID.'
            );
        }
        $usersTag = $groupTag->addChild('Users');
        foreach ($users as $user) {
            if (!($user instanceof User)) {
                throw new InvalidArgumentException(
                    '"$users" must be an array of CBS\SmarterU\DataTypes\User instances'
                );
            }
            $currentUser = $usersTag->addChild('User');
            if (!empty($user->getEmail())) {
                $currentUser->addChild('Email', $user->getEmail());
            } else if (!empty($user->getEmployeeId())) {
                $currentUser->addChild('EmployeeID', $user->getEmployeeId());
            } else {
                throw new MissingValueException(
                    'All Users being added to or removed from a Group must have an email address or employee ID.'
                );
            }
            $currentUser->addChild('UserAction', $action);
            $currentUser->addChild(
                'HomeGroup',
                $user->getHomeGroup() === $group->getName() ? '1' : '0'
            );
            $currentUser->addChild('Permissions');
        }

        // The SmarterU API requires that these tags be present when making an
        // updateGroup request. If they are left empty, no changes will be made
        // to the Group's Learning Modules or Subscription Variants.
        $learningModules = $groupTag->addChild('LearningModules');
        $subscriptionVariants = $groupTag->addChild('SubscriptionVariants');

        return $xml->asXML();
    }

    /**
     * Generate the XML body for a grantPermission or revokePermission query.
     * Functionally, this is just a specific type of updateGroup query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param User $user The User whose permissions within the Group are being
     *      updated.
     * @param Group $group The Group in which the User's permissions are being
     *      updated.
     * @param array $permissions The permissions to be granted or revoked.
     * @param string $action Whether the permissions are being granted or revoked.
     * @return string an XML representation of the query.
     * @throws MissingValueException If the user whose permissions are being
     *      modified doesn't have an email address or an employee ID, or if the
     *      Group in which the permissions are being modified does not have a
     *      name or an ID.
     */
    public function changePermissions(
        string $accountApi,
        string $userApi,
        User $user,
        Group $group,
        array $permissions,
        string $action
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'updateUser');
        $parameters = $xml->addChild('Parameters');
        $userTag = $parameters->addChild('User');
        $identifier = $userTag->addChild('Identifier');
        if (!empty($user->getEmail())) {
            $identifier->addChild('Email', $user->getEmail());
        } else if (!empty($user->getEmployeeId())) {
            $identifier->addChild('EmployeeID', $user->getEmployeeId());
        } else {
            throw new MissingValueException(
                'A User\'s permissions cannot be updated without either an email address or an employee ID.'
            );
        }
        $info = $userTag->addChild('Info');
        $profile = $userTag->addChild('Profile');

        $groups = $userTag->addChild('Groups');
        $groupTag = $groups->addChild('Group');
        if (!empty($group->getName())) {
            $groupTag->addChild('GroupName', $group->getName());
        } else if (!empty($group->getGroupId())) {
            $groupTag->addChild('GroupID', $group->getGroupId());
        } else {
            throw new MissingValueException(
                'Cannot assign permissions in a Group that has no name or ID.'
            );
        }
        $groupTag->addChild('GroupAction', 'Add');
        $groupPermissions = $groupTag->addChild('GroupPermissions');
        foreach ($permissions as $permission) {
            $permissionTag = $groupPermissions->addChild('Permission');
            $permissionTag->addChild('Action', $action);
            $permissionTag->addChild('Code', $permission);
        }

        $venues = $userTag->addChild('Venues');
        $wages = $userTag->addChild('Wages');
        return $xml->asXML();
    }

    /**
     * Generate the XML body for a getLearnerReport query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param GetLearnerReportQuery $query The query containing the parameters
     *      by which to filter the results.
     * @return string an XML representation of the query.
     * @throws MissingValueException If the Group(s) or User(s) to include in
     *      the report are not identified, or if Groups are filtered by tags
     *      that are not identified.
     */
    public function getLearnerReport(
        string $accountApi,
        string $userApi,
        GetLearnerReportQuery $query
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'getLearnerReport');
        $parameters = $xml->addChild('Parameters');
        $report = $parameters->addChild('Report');
        $report->addChild('Page', (string) $query->getPage());
        $report->addChild('PageSize', (string) $query->getPageSize());
        $filters = $report->addChild('Filters');

        // Don't include EnrollmentId tag if it does not have a value.
        if (! is_null($query->getEnrollmentId())) {
            $filters->addChild('EnrollmentID', $query->getEnrollmentId());
        }

        $groups = $filters->addChild('Groups');
        if (!empty($query->getGroupStatus())) {
            $groups->addChild('GroupStatus', $query->getGroupStatus());
        } else if (!empty($query->getGroupNames())) {
            $groupNames = $groups->addChild('GroupNames');
            foreach ($query->getGroupNames() as $name) {
                $groupNames->addChild('GroupName', $this->escapeValue($name));
            }
        } else {
            throw new MissingValueException(
                'GetLearnerReport must contain either a Group status or a list of Group names.'
            );
        }
        if (!empty($query->getGroupTags())) {
            $tags = $groups->addChild('GroupTags2');
            foreach ($query->getGroupTags() as $tag) {
                $currentTag = $tags->addChild('GroupTag2');
                if (!empty($tag->getTagId())) {
                    $currentTag->addChild('TagID', $tag->getTagId());
                } else if (!empty($tag->getTagName())) {
                    $currentTag->addChild('TagName', $tag->getTagName());
                } else {
                    throw new MissingValueException(
                        'Tags must have either an ID or a name.'
                    );
                }
                $currentTag->addChild('TagValues', $tag->getTagValues());
            }
        }

        $learningModules = $filters->addChild('LearningModules');
        if (
            !empty($query->getLearningModuleStatus())
            || !empty($query->getLearningModuleNames())
        ) {
            $learningModule = $learningModules->addChild('LearningModule');
            if (!empty($query->getLearningModuleStatus())) {
                $learningModule->addChild(
                    'LearningModuleStatus',
                    $query->getLearningModuleStatus()
                );
            }
            if (!empty($query->getLearningModuleNames())) {
                $names = $learningModule->addChild('LearningModuleNames');
                foreach ($query->getLearningModuleNames() as $name) {
                    $names->addChild('LearningModuleName', $name);
                }
            }
        }
        if (!empty($query->getEnrollmentStatuses())) {
            $enrollmentStatuses = $learningModules->addChild(
                'EnrollmentStatuses'
            );
            foreach ($query->getEnrollmentStatuses() as $status) {
                $enrollmentStatuses->addChild('EnrollmentStatus', $status);
            }
        }
        if (!empty($query->getCompletedDates())) {
            $completedDates = $learningModules->addChild('CompletedDates');
            foreach ($query->getCompletedDates() as $date) {
                $completedDate = $completedDates->addChild(
                    'CompletedDate'
                );
                $completedDate->addChild(
                    'CompletedDateFrom',
                    $date->getDateFrom()->format('d-M-y')
                );
                $completedDate->addChild(
                    'CompletedDateTo',
                    $date->getDateTo()->format('d-M-y')
                );
            }
        }
        if (!empty($query->getDueDates())) {
            $dueDates = $learningModules->addChild('DueDates');
            foreach ($query->getDueDates() as $date) {
                $dueDate = $dueDates->addChild('DueDate');
                $dueDate->addChild(
                    'DueDateFrom',
                    $date->getDateFrom()->format('d-M-y')
                );
                $dueDate->addChild(
                    'DueDateTo',
                    $date->getDateTo()->format('d-M-y')
                );
            }
        }
        if (!empty($query->getEnrolledDates())) {
            $enrolledDates = $learningModules->addChild('EnrolledDates');
            foreach ($query->getEnrolledDates() as $date) {
                $enrolledDate = $enrolledDates->addChild(
                    'EnrolledDate'
                );
                $enrolledDate->addChild(
                    'EnrolledDateFrom',
                    $date->getDateFrom()->format('d-M-y')
                );
                $enrolledDate->addChild(
                    'EnrolledDateTo',
                    $date->getDateTo()->format('d-M-y')
                );
            }
        }
        if (!empty($query->getGracePeriodDates())) {
            $gracePeriodDates = $learningModules->addChild(
                'GracePeriodDates'
            );
            foreach ($query->getGracePeriodDates() as $date) {
                $gracePeriodDate = $gracePeriodDates->addChild(
                    'GracePeriodDate'
                );
                $gracePeriodDate->addChild(
                    'GracePeriodDateFrom',
                    $date->getDateFrom()->format('d-M-y')
                );
                $gracePeriodDate->addChild(
                    'GracePeriodDateTo',
                    $date->getDateTo()->format('d-M-y')
                );
            }
        }
        if (!empty($query->getLastAccessedDates())) {
            $lastAccessedDates = $learningModules->addChild(
                'LastAccessedDates'
            );
            foreach ($query->getLastAccessedDates() as $date) {
                $lastAccessedDate = $lastAccessedDates->addChild(
                    'LastAccessedDate'
                );
                $lastAccessedDate->addChild(
                    'LastAccessedDateFrom',
                    $date->getDateFrom()->format('d-M-y')
                );
                $lastAccessedDate->addChild(
                    'LastAccessedDateTo',
                    $date->getDateTo()->format('d-M-y')
                );
            }
        }
        if (!empty($query->getStartedDates())) {
            $startedDates = $learningModules->addChild('StartedDates');
            foreach ($query->getStartedDates() as $date) {
                $startedDate = $startedDates->addChild(
                    'StartedDate'
                );
                $startedDate->addChild(
                    'StartedDateFrom',
                    $date->getDateFrom()->format('d-M-y')
                );
                $startedDate->addChild(
                    'StartedDateTo',
                    $date->getDateTo()->format('d-M-y')
                );
            }
        }

        $enrollments = $filters->addChild('Enrollments');
        if (!empty($query->getCreatedDate())) {
            $createdDate = $enrollments->addChild('CreatedDate');
            $createdDate->addChild(
                'CreatedDateFrom',
                $query->getCreatedDate()->getDateFrom()->format('d-M-y')
            );
            $createdDate->addChild(
                'CreatedDateTo',
                $query->getCreatedDate()->getDateTo()->format('d-M-y')
            );
        }
        if (!empty($query->getModifiedDate())) {
            $modifiedDate = $enrollments->addChild('ModifiedDate');
            $modifiedDate->addChild(
                'ModifiedDateFrom',
                $query->getModifiedDate()->getDateFrom()->format('d-M-y')
            );
            $modifiedDate->addChild(
                'ModifiedDateTo',
                $query->getModifiedDate()->getDateTo()->format('d-M-y')
            );
        }
        $users = $filters->addChild('Users');
        if (!empty($query->getUserStatus())) {
            $users->addChild('UserStatus', $query->getUserStatus());
        } else if (
            !empty($query->getUserEmailAddresses()
            || !empty($query->getUserEmployeeIds()))
        ) {
            $userIdentifier = $users->addChild('UserIdentifier');
            foreach ($query->getUserEmailAddresses() as $email) {
                $userIdentifier->addChild('EmailAddress', $email);
            }
            foreach ($query->getUserEmployeeIds() as $id) {
                $userIdentifier->addChild('EmployeeID', $id);
            }
        } else {
            throw new MissingValueException(
                'GetLearnerReport requires either a User Status or User Identifiers.'
            );
        }


        $columns = $report->addChild('Columns');
        foreach ($query->getColumns() as $column) {
            $columns->addChild('ColumnName', $column);
        }

        $customFields = $report->addChild('CustomFields');
        foreach ($query->getCustomFields() as $field) {
            $customFields->addChild('FieldName', $field->getName());
        }

        return $xml->asXML();
    }

    /**
     * Generate the XML body for a RequestExternalAuthorization query to the
     * SmarterU API.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param array $identifier An array containing a single key=>value pair in
     *      which the key identifies whether the value is an email address or
     *      an employee ID, and the value identifies the User who is requesting
     *      external authorization.
     * @return string an XML representation of the query.
     * @throws MissingValueException If the "identifier" array does not contain
     *      an email address or an employee ID.
     */
    public function requestExternalAuthorization(
        string $accountApi,
        string $userApi,
        array $identifier
    ): string {
        $xml = simplexml_load_string(self::ROOT_NODE_XML_STRING);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'requestExternalAuthorization');
        $parameters = $xml->addChild('Parameters');
        $security = $parameters->addChild('Security');
        if (array_key_exists('Email', $identifier)) {
            $security->addChild('Email', $identifier['Email']);
        } else if (array_key_exists('EmployeeID', $identifier)) {
            $security->addChild('EmployeeID', $identifier['EmployeeID']);
        } else {
            throw new MissingValueException(
                'Cannot request external authorization without an email address or employee ID'
            );
        }
        return $xml->asXML();
    }

    /**
     * Determine whether or not to filter listUsers results based on the user's
     * identifying information.
     *
     * @param ListUsersQuery $query The query that may or may not need a
     *      <Users> tag.
     * @return bool True if and only if the query should contain a <Users> tag
     */
    private function listUsersIncludeUsersTag(ListUsersQuery $query): bool {
        return (
            !empty($query->getEmail())
            || !empty($query->getEmployeeId())
            || !empty($query->getName())
        );
    }

    /**
     * Determine whether or not to include the LearningModules Tag Group when
     * translating a GetLearnerReportQuery to XML.
     *
     * @param GetLearnerReportQuery $query The query being translated to XML.
     * @return bool True if and only if the query should contain the
     *      LearningModules tag group.
     */
    private function includeLearningModulesTag(GetLearnerReportQuery $query): bool {
        return (
            !empty($query->getLearningModuleStatus())
            || !empty($query->getLearningModuleNames())
            || !empty($query->getEnrollmentStatuses())
            || !empty($query->getCompletedDates())
            || !empty($query->getDueDates())
            || !empty($query->getEnrolledDates())
            || !empty($query->getGracePeriodDates())
            || !empty($query->getLastAccessedDates())
            || !empty($query->getStartedDates())
        );
    }

    /**
     * Escapes a value for legal passthrough to addChild().
     *
     * The documentation stinks on this point but if you dig far enough you
     * will discover that addChild() will escape < and > when you pass a value
     * in but it will NOT escape &.  This method will escape & making a value
     * legal to pass-through to addChild().
     *
     * @see https://www.php.net/manual/en/simplexmlelement.addchild.php
     * @param string $data The string to wrap in a CDATA tag.
     * @return string The string wrapped in a CDATA tag.
     */
    private function escapeValue(string $data): string {
        return str_replace('&', '&amp;', $data);
    }
}
