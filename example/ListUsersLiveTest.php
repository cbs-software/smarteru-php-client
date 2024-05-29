<?php

/**
 * Contains CBS\SmarterU\Tests\Usability\ListUsersLiveTest.
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/19
 */

declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\Queries\ListUsersQuery;

/**
 * This script contains a live test for Client::listUsers.
 */
$accountKey = ''; //insert key here before running
$userKey = ''; //insert key here before running

// Some query attributes can be customized using command line arguments.
$args = [];
for ($i = 0; $i < $argc; $i++) {
    if ($argv[$i] === '-homeGroup' && $i < $argc - 1) {
        $args['homeGroup'] = $argv[$i + 1];
    }
    if ($argv[$i] === '-groupName' && $i < $argc - 1) {
        $args['groupName'] = $argv[$i + 1];
    }
    if ($argv[$i] === '-userStatus' && $i < $argc - 1) {
        $args['userStatus'] = $argv[$i + 1];
    }
    if ($argv[$i] === '-teams' && $i < $argc - 1) {
        $args['teams'] = explode(',', $argv[$i + 1]);
    }
}

$query = new ListUsersQuery();

if (array_key_exists('homeGroup', $args)) {
    $query->setHomeGroup($args['homeGroup']);
}

if (array_key_exists('groupName', $args)) {
    $query->setGroupName($args['groupName']);
}

if (array_key_exists('userStatus', $args)) {
    $query->setUserStatus($args['userStatus']);
}

if (array_key_exists('teams', $args)) {
    $query->setTeams($args['teams']);
}

$client = new Client($accountKey, $userKey);

print_r($client->listUsers($query));

/*
During testing, I discovered that the API will only return Users whose user
type is 'User'. Anyone who has admin permissions will not be read by the API
when making a ListUsers query. Any attributes that are left blank in the
following User object are not returned by the API when making a ListUsers
query.

Output:

Array
(
    [0] => CBS\SmarterU\DataTypes\User Object
    (
        [oldEmail:protected] =>
        [oldEmployeeId:protected] =>
        [id] => user 0's ID
        [email:protected] => user 0's email
        [employeeId:protected] => user 0's employee ID
        [givenName:protected] => user 0's first name
        [surname:protected] => user 0's last name
        [timezone:protected] =>
        [learnerNotifications:protected] =>
        [supervisorNotifications:protected] =>
        [sendEmailTo:protected] =>
        [alternateEmail:protected] =>
        [authenticationType:protected] =>
        [supervisors:protected] => Array
            (
            )

        [organization:protected] =>
        [teams:protected] => Array
            (
                [0] => user 0's first team
                ...
            )

        [customFields:protected] => Array
            (
            )

        [language:protected] =>
        [status:protected] => user 0's status
        [title:protected] => user 0's title
        [division:protected] => user 0's division
        [allowFeedback:protected] =>
        [phonePrimary:protected] =>
        [phoneAlternate:protected] =>
        [phoneMobile:protected] =>
        [fax:protected] =>
        [website:protected] =>
        [address1:protected] =>
        [address2:protected] =>
        [city:protected] =>
        [province:protected] =>
        [country:protected] =>
        [postalCode:protected] =>
        [sendMailTo:protected] =>
        [roles:protected] =>
        [receiveNotifications:protected] =>
        [homeGroup:protected] => user 0's home group
        [venues:protected] =>
        [wages:protected] =>
        [createdDate:protected] => user 0's created date
        [modifiedDate:protected] => user 0's modified date
    )
    [1] => CBS\SmarterU\DataTypes\User Object
    (
        ...
    }
)
 */
