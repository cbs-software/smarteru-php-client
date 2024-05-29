<?php

/**
 * Contains CBS\SmarterU\Tests\Usability\UpdateUserLiveTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\DataTypes\Timezone;
use CBS\SmarterU\Exceptions\SmarterUException;

/**
 * This script contains a live test for Client::updateUser. It was used to
 * intentionally trigger errors and see how the API responds.
 */
$accountKey = getenv('SMARTERU_ACCOUNT_KEY') ?? 'No Account Key Provided';
$userKey = getenv('SMARTERU_USER_KEY') ?? 'No User Key Provided';

$user = (new User())
    ->setEmail('cooluser@email.com') // insert email here
    ->setLearnerNotifications(true)
    ->setSupervisorNotifications(true)
    ->setTimezone(Timezone::fromProvidedName('US/Mountain'))
    ->setGroups([])
    ->setPhonePrimary('555-555-5555');

try {
    // Create the Client for speaking to the API
    $client = new Client($accountKey, $userKey);
    
    // Update the user
    $client->updateUser($user);

    // Read the user back
    $user = $client->readUserByEmail($user->getEmail());

    // Make sure the user is in the right time zone.
    printf("User is in %s Time zone\n", $user->getTimezone()->getDisplayValue());

} catch (SmarterUException $error) {
    var_dump($error);
}

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
