<?php

/**
 * A live user creation test.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */
 
declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\DataTypes\Timezone;
use CBS\SmarterU\Exceptions\SmarterUException;

/**
 * This script contains a live test for Client::createUser. It was used to
 * intentionally trigger errors and see how the API responds.
 */
$accountKey = getenv('SMARTERU_ACCOUNT_KEY') ?? 'No Account Key Provided';
$userKey = getenv('SMARTERU_USER_KEY') ?? 'No User Key Provided';

$user = (new User())
    ->setEmail('cooluser2@email.com') // insert email here
    ->setPassword('Sup3rS3cur3!')
    ->setHomeGroup('SANDBOX - Core Business Solutions')
    ->setGivenName('Bill')
    ->setSurName('Braskey')
    ->setSendEmailTo('Self')
    ->setLearnerNotifications(true)
    ->setSupervisorNotifications(true)
    ->setTimezone(Timezone::fromProvidedName('US/Mountain'))
    ->setAuthenticationType('External')
    ->setPhonePrimary('555-555-5555');

try {
    // Create the Client for speaking to the API
    $client = new Client($accountKey, $userKey);
    
    // Create the user
    $client->createUser($user);

    // Read the user back
    $user = $client->readUserByEmail($user->getEmail());

    // Make sure the user is in the right time zone.
    printf("User is in %s Time zone\n", $user->getTimezone()->getDisplayValue());
} catch (SmarterUException $error) {
    var_dump($error);
}
