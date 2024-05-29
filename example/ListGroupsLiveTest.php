<?php

/**
 * Contains CBS\SmarterU\Tests\Usability\ListGroupsLiveTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\DataTypes\User;
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
The ListGroups method only returns the Group's name and ID and does not include
any other attributes.
Output:

Array
(
    [0] => CBS\SmarterU\DataTypes\Group Object
    (
        [oldName:protected] =>
        [oldGroupId:protected] =>
        [name:protected] => Group 0's name
        [groupId:protected] => Group 0's ID
        [description:protected] =>
        [homeGroupMessage:protected] =>
        [notificationEmails:protected] => Array
            (
            )

        [userHelpOverrideDefault:protected] =>
        [userHelpEnabled:protected] =>
        [userHelpEmail:protected] =>
        [userHelpText:protected] =>
        [tags:protected] =>
        [userLimitEnabled:protected] =>
        [userLimitAmount:protected] =>
        [status:protected] =>
        [users:protected] => Array
            (
            )

        [learningModules:protected] => Array
            (
            )

        [subscriptionVariants:protected] => Array
            (
            )

        [dashboardSetId:protected] =>
        [permissions:protected] => Array
            (
            )

    )
    ...
)
 */
