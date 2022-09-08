<?php

/**
 * Contains CBS\SmarterU\XMLGenerator.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/31
 */

declare(strict_types=1);

namespace CBS\SmarterU;

use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\GetUserQuery;
use CBS\SmarterU\Queries\ListUsersQuery;
use SimpleXMLElement;

/**
 * This class contains helper methods for Client that generate the XML body of
 * the request to be made to the SmarterU API.
 */
class XMLGenerator {
    /**
     * Generate the XML body for a createUser query.
     *
     * @param string $accountApi The SmarterU API key identifying the account
     *      making the request.
     * @param string $userApi The SmarterU API key identifying the user within
     *      that account who is making the request.
     * @param User $user The User to translate to XML
     * @return string an XML representation of the User
     */
    public function createUser(
        string $accountApi,
        string $userApi,
        User $user
    ): string {
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;

        $xml = simplexml_load_string($xmlString);
        $xml->addChild('AccountAPI', $accountApi);
        $xml->addChild('UserAPI', $userApi);
        $xml->addChild('Method', 'createUser');
        $parameters = $xml->addChild('Parameters');
        $userTag = $parameters->addChild('User');
        $info = $userTag->addChild('Info');
        if (empty($user->getEmail()) && empty($user->getEmployeeId())) {
            throw new missingValueException(
                'Cannot create a User without either an email or employee ID.'
            );
        }
        if (!empty($user->getEmail())) {
            $info->addChild('Email', $user->getEmail());
        }
        if (!empty($user->getEmployeeId())) {
            $info->addChild('EmployeeID', $user->getEmployeeId());
        }
        $info->addChild('GivenName', $user->getGivenName());
        $info->addChild('Surname', $user->getSurname());
        $info->addChild('Password', $user->getPassword());
        if (!empty($user->getTimezone())) {
            $info->addChild('Timezone', $user->getTimezone());
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
                $user->getAllowFeedback() ? 'True' : 'False'
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
                $user->getReceiveNotifications() ? 'True' : 'False'
            );
        }
        if (empty($user->getHomeGroup())) {
            throw new MissingValueException('Cannot create a User without a Home Group.');
        }

        $profile->addChild('HomeGroup', $user->getHomeGroup());

        $groups = $userTag->addChild('Groups');
        $groupTag = $groups->addChild('Group');
        $groupTag->addChild('GroupName', $user->getHomeGroup());
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
            $filters->addChild('GroupName', $query->getGroupName());
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
     */
    public function updateUser(
        string $accountApi,
        string $userApi,
        User $user
    ): string {
        $xmlString = <<<XML
        <SmarterU>
        </SmarterU>
        XML;

        $xml = simplexml_load_string($xmlString);
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
        if (!empty($user->getOldEmail())) {
            $info->addChild('Email', $user->getEmail());
        }
        if (!empty($user->getOldEmployeeId())) {
            $info->addChild('EmployeeID', $user->getEmployeeId());
        }
        if (!empty($user->getGivenName())) {
            $info->addChild('GivenName', $user->getGivenName());
        }
        if (!empty($user->getSurname())) {
            $info->addChild('Surname', $user->getSurname());
        }
        if (!empty($user->getTimezone())) {
            $info->addChild('TimeZone', $user->getTimezone());
        }
        $info->addChild(
            'LearnerNotifications',
            $user->getLearnerNotifications() ? '1' : '0'
        );
        $info->addChild(
            'SupervisorNotifications',
            $user->getSupervisorNotifications() ? '1' : '0'
        );
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
                $user->getAllowFeedback() ? 'True' : 'False'
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
                $user->getReceiveNotifications() ? 'True' : 'False'
            );
        }
        if (!empty($user->getHomeGroup())) {
            $profile->addChild('HomeGroup', $user->getHomeGroup());
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
     * Determine whether or not to filter listUsers results based on the user's
     * identifying information.
     *
     * @param ListUsersQuery $query The query that may or may not need a
     *      <Users> tag.
     * @return bool True if and only if the query should contain a <Users> tag
     */
    private function listUsersIncludeUsersTag(ListUsersQuery $query): bool {
        return !empty($query->getEmail()) || !empty($query->getEmployeeId()) || !empty($query->getName());
    }
}
