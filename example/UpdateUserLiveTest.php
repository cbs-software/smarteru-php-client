<?php

/**
 * Contains CBS\SmarterU\Tests\Usability\UpdateUserLiveTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/25
 */

declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\DataTypes\User;

/**
 * This script contains a live test for Client::updateUser. It was used to
 * intentionally trigger errors and see how the API responds.
 */
$accountKey = ''; //insert key here before running
$userKey = ''; //insert key here before running

$user = (new User())
    ->setEmail('') // insert email here
    ->setLearnerNotifications(true)
    ->setSupervisorNotifications(true)
    ->setGroups([])
    ->setPhonePrimary('Invalid');

$client = new Client($accountKey, $userKey);

print_r($client->updateUser($user));

/*
Output:
CBS\SmarterU\DataTypes\User Object
(
    [oldEmail:protected] =>
    [oldEmployeeId:protected] =>
    [id] =>
    [email:protected] => user 0's email
    [employeeId:protected] => user 0's employee ID
    [givenName:protected] =>
    [surname:protected] =>
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
        )

    [customFields:protected] => Array
        (
        )

    [language:protected] =>
    [status:protected] =>
    [title:protected] =>
    [division:protected] =>
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
    [homeGroup:protected] =>
    [venues:protected] =>
    [wages:protected] =>
    [createdDate:protected] =>
    [modifiedDate:protected] =>
)
*/
