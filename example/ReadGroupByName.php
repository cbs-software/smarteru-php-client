<?php

/**
 * A script for looking up SmarterU Groups by name.
 *
 * To use the script, run it from the command line with the group name as the
 * first argument. If your group name includes spaces or control characters,
 * wrap it in quotes.
 *
 * Examples:
 * php ListUsersLiveTest.php "Group Name"
 * php ListUsersLiveTest.php GroupName
 *
 * The script will render the print_r() version of your group so you can see
 * the value of it's properties.
 *
 * @author CORE Software Team
 * @copyright $year$ Core Business Solutions
 * @license Proprietary
 * @since 2024/12/18
 * @version $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;

$accountKey = getenv('SMARTERU_ACCOUNT_KEY') ?? 'No Account Key Provided';
$userKey = getenv('SMARTERU_USER_KEY') ?? 'No User Key Provided';

/**
 * The first argument to the script should be the group name.
 *
 * @var string|null
 */
$groupNameToFind = $argv[1] ?? null;

// If no arguments, then show usage.
if (empty($groupNameToFind)) {
    echo "Usage: php ListUsersLiveTest.php [groupName]\n";
    exit(1);
}

try {
    print_r(
        (new Client($accountKey, $userKey))
            ->readGroupByName($groupNameToFind)
    );
} catch (\Exception $error) {
    var_dump($error);
}
