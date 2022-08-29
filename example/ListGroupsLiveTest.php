<?php

/**
 * Contains CBS\SmarterU\Tests\Usability\ListGroupsLiveTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/23
 */

declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\Queries\ListGroupsQuery;

/**
 * This script contains a live test for Client::listGroups.
 */

$accountKey = ''; //insert key here before running
$userKey = ''; //insert key here before running

$query = new ListGroupsQuery();

$client = new Client($accountKey, $userKey);

print_r($client->listGroups($query));

/*
Output:

Array
(
    [Response] => Array
        (
            [0] => Array
                (
                    [Name] => (group 1's name)
                    [GroupID] => (group 1's ID)
                )

            [1] => Array
                (
                    [Name] => (group 2's name)
                    [GroupID] => (group 2's ID)
                )
            ...
        )

    [Errors] => Array
        (
        )

)
 */
