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
