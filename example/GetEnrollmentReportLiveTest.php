<?php

/**
 * A live Enrollment Report test.
 *
 * This test expects the SMARTERU_ACCOUNT_KEY and SMARTERU_USER_KEY environment
 * variables to be set. It will query the SMARTERU API for a list of all users
 * in the SANDBOX group that are active and output their progress in the
 * courses they are enrolled in, in CSV format.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */
 
declare(strict_types=1);

namespace CBS\SmarterU\Tests\Usability;

require_once(__DIR__ . '/../vendor/autoload.php');

use CBS\SmarterU\Client;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Queries\GetLearnerReportQuery;

$accountKey = getenv('SMARTERU_ACCOUNT_KEY') ?? 'No Account Key Provided';
$userKey = getenv('SMARTERU_USER_KEY') ?? 'No User Key Provided';

try {
    // Create the Client for speaking to the API
    $client = new Client($accountKey, $userKey);
    
    // Query for an Enrollment report for all users in our SANDBOX group
    // that are active. Add some optional fields that we care about.
    $results = $client->getLearnerReport(
        (new GetLearnerReportQuery())
            ->setGroupNames(['SANDBOX - Core Business Solutions'])
            ->setUserStatus('Active')
            ->setColumns(['PROGRESS', 'COURSE_DURATION', 'DUE_DATE', ])
    );

    fputcsv(STDOUT, [
        'ID',
        'Given Name',
        'Surname',
        'Course Name',
        'Course Duration',
        'Is Completed',
        'Completed Date',
        'Progress'
    ]);

    foreach ($results as $learnerReport) {
        fputcsv(STDOUT, [
            $learnerReport->getId(),
            $learnerReport->getGivenName(),
            $learnerReport->getSurname(),
            $learnerReport->getCourseName(),
            $learnerReport->getCourseDuration(),
            $learnerReport->getProgress() === '100' ? 'Yes' : 'No',
            $learnerReport->getCompletedDate(),
            $learnerReport->getProgress()
        ]);
    }
} catch (SmarterUException $error) {
    var_dump($error);
}
