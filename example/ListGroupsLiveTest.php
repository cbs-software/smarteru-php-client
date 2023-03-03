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
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Queries\ListGroupsQuery;

/**
 * This script contains a live test for Client::listGroups.
 */

$accountKey = '87CD3F946F81B62242AC4B5E4DC8F59F'; //insert key here before running
$userKey = '$*376$1w4fk*o!l9cye!9t*3l4!ti4h5at*rb1k1'; //insert key here before running

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
