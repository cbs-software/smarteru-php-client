<?php

/**
 * Contains CBS\SmarterU\Tests\Usability\ListUsersLiveTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
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
when making a ListUsers query.

Output:

Array
(
    [Response] => Array
        (
            [0] => Array
                (
                    [ID] => (user 1's ID)
                    [Email] => user1@example.com
                    [EmployeeID] => (user 1's employee ID)
                    [GivenName] => John
                    [Surname] => Doe
                    [Name] => John Doe
                    [Status] => Active
                    [Title] => (user 1's title)
                    [Division] => (user 1's division)
                    [HomeGroup] => (user 1's home group)
                    [CreatedDate] => (user 1's created date)
                    [ModifiedDate] => (user 2's modified date)
                    [Teams] => Array
                        (
                        )

                )

            [1] => Array
                (
                    [ID] => (user 2's ID)
                    [Email] => user2@example.com
                    [EmployeeID] => (user 2's employee ID)
                    [GivenName] => Jane
                    [Surname] => Doe
                    [Name] => Jane Doe
                    [Status] => Active
                    [Title] => (user 2's title)
                    [Division] => (user 2's division)
                    [HomeGroup] => (user 2's home group)
                    [CreatedDate] =>  (user 2's created date)
                    [ModifiedDate] =>  (user 2's modified date)
                    [Teams] => Array
                        (
                        )

                )
            ...
        )

    [Errors] => Array
        (
        )

)
 */
