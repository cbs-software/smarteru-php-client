<?php

/**
 * Contains Tests\CBS\SmarterU\Client\GetLearnerReportClientTest.php.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/27
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Client;

use CBS\SmarterU\DataTypes\CustomField;
use CBS\SmarterU\DataTypes\ErrorCode;
use CBS\SmarterU\DataTypes\LearnerReport;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Queries\GetLearnerReportQuery;
use CBS\SmarterU\Queries\Tags\DateRangeTag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Exceptions\SmarterUException;
use CBS\SmarterU\Client;
use CBS\SmarterU\XMLGenerator;
use DateTime;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use SimpleXMLElement;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Client::getLearnerReport().
 */
class GetLearnerReportClientTest extends TestCase {
    /**
     * Test that Client::getLearnerReport() throws an exception when the
     * query doesn't have either a group status or a list of group names.
     */
    public function testGetLearnerReportThrowsExceptionWhenNoGroupIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1');

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'GetLearnerReport must contain either a Group status or a list of Group names.'
        );
        $client->getLearnerReport($query);
    }

    /**
     * Test that Client::getLearnerReport() throws an exception when the
     * query contains a Tag that doesn't have a name or an ID.
     */
    public function testGetLearnerReportThrowsExceptionWhenNoTagIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $tag = new Tag();
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setGroupTags([$tag]);

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Tags must have either an ID or a name.'
        );
        $client->getLearnerReport($query);
    }

    /**
     * Test that Client::getLearnerReport() throws an exception when the
     * query doesn't have either a user status or a list of user identifiers.
     */
    public function testGetLearnerReportThrowsExceptionWhenNoUserIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active');

        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'GetLearnerReport requires either a User Status or User Identifiers.'
        );
        $client->getLearnerReport($query);
    }

    /**
     * Test that Client::getLearnerReport() throws an exception when the HTTP
     * response includes a status code indicating that an HTTP error prevented
     * the request from being made.
     */
    public function testGetLearnerReportThrowsExceptionWhenHTTPErrorOccurs() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setUserStatus('Active');

        $response = new Response(404);

        $container = [];
        $history = Middleware::history($container);

        $mock = (new MockHandler([$response]));

        $handlerStack = HandlerStack::create($mock);

        $handlerStack->push($history);

        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client->setHttpClient($httpClient);

        self::expectException(ClientException::class);
        self::expectExceptionMessage('Client error: ');
        $client->getLearnerReport($query);
    }

    /**
     * Test that Client::getLearnerReport() throws the expected exception
     * when the SmarterU API returns a fatal error.
     */
    public function testAddUsersToGroupThrowsExceptionWhenFatalErrorReturned() {
        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setUserStatus('Active');

        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $code = 'UT:99';
        $message = 'An error mocked for unit testing';
        $body = <<<XML
        <SmarterU>
            <Result>Failed</Result>
            <Info>
            </Info>
            <Errors>
                <Error>
                    <ErrorID>$code</ErrorID>
                    <ErrorMessage>$message</ErrorMessage>
                </Error>
            </Errors>
        </SmarterU>
        XML;

        // Set up the container to capture the request.
        $response = new Response(200, [], $body);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logger->expects($this->once())->method('error')->with(
            $this->identicalTo('Failed to make request to SmarterU API. See context for request/response details.'),
            $this->identicalTo([
                'request' => "<?xml version=\"1.0\"?>\n<SmarterU><AccountAPI>********</AccountAPI><UserAPI>********</UserAPI><Method>getLearnerReport</Method><Parameters><Report><Page>1</Page><PageSize>50</PageSize><Filters><EnrollmentID>1</EnrollmentID><Groups><GroupStatus>Active</GroupStatus></Groups><Enrollments/><Users><UserStatus>Active</UserStatus></Users></Filters></Report><Columns/><CustomFields/></Parameters></SmarterU>\n",
                'response' => $body
            ])
        );

        $client
            ->setHttpClient($httpClient)
            ->setLogger($logger);

        // Make the request. Because we want to inspect custom exception
        // properties we'll handle the try/catch/cache of the exception
        $exception = null;
        try {
            $client->getLearnerReport($query);
        } catch (SmarterUException $error) {
            $exception = $error;
        }

        self::assertInstanceOf(SmarterUException::class, $exception);
        self::assertEquals(Client::SMARTERU_EXCEPTION_MESSAGE, $exception->getMessage());

        $errorCodes = $error->getErrorCodes();
        self::assertIsArray($errorCodes);
        self::assertCount(1, $errorCodes);

        $errorCode = reset($errorCodes);
        self::assertInstanceOf(ErrorCode::class, $errorCode);
        self::assertEquals($code, $errorCode->getErrorCode());
        self::assertEquals($message, $errorCode->getErrorMessage());
    }

    /**
     * Test that Client::getLearnerReport() produces the correct output when
     * the query does not contain any optional information and the SmarterU API
     * returns a single LearnerReport.
     */
    public function testGetLearnerReportProducesCorrectOutputWithoutOptionalInfoSingleReport() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setUserStatus('Active');

        $id = '1';
        $courseName = 'My Course';
        $lastName = 'User';
        $firstName = 'Test';
        $learningModuleId = '2';
        $userId = '3';
        $createdDate = (new DateTime('2022-09-27'))->format('d-M-y');
        $modifiedDate = (new DateTime())->format('d-M-y');

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <LearnerReport rows="0">
                    <Learner>
                        <ID>$id</ID>
                        <CourseName>$courseName</CourseName>
                        <LastName>$lastName</LastName>
                        <FirstName>$firstName</FirstName>
                        <LearningModuleID>$learningModuleId</LearningModuleID>
                        <UserID>$userId</UserID>
                        <CreatedDate>$createdDate</CreatedDate>
                        <ModifiedDate>$modifiedDate</ModifiedDate>
                    </Learner>
                </LearnerReport>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;

        // Set up the container to capture the request.
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $result = $client->getLearnerReport($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->getLearnerReport(
            $accountApi,
            $userApi,
            $query
        );
        self::assertEquals($decodedBody, $expectedBody);

        // Make sure the expected value is returned.
        self::assertIsArray($result);
        self::assertCount(1, $result);
        $report = $result[0];
        self::assertInstanceOf(LearnerReport::class, $report);
        self::assertEquals($id, $report->getId());
        self::assertEquals($courseName, $report->getCourseName());
        self::assertEquals($lastName, $report->getSurname());
        self::assertEquals($firstName, $report->getGivenName());
        self::assertEquals($learningModuleId, $report->getLearningModuleId());
        self::assertEquals($userId, $report->getUserId());
        self::assertEquals(
            $createdDate,
            $report->getCreatedDate()->format('d-M-y')
        );
        self::assertEquals(
            $modifiedDate,
            $report->getModifiedDate()->format('d-M-y')
        );
        self::assertNull($report->getUserEmail());
        self::assertNull($report->getAlternateEmail());
        self::assertNull($report->getEmployeeId());
        self::assertNull($report->getDivision());
        self::assertNull($report->getTitle());
        self::assertNull($report->getGroupId());
        self::assertNull($report->getGroupName());
        self::assertNull($report->getCourseDuration());
        self::assertNull($report->getCourseSessionId());
        self::assertNull($report->getEnrolledDate());
        self::assertNull($report->getDueDate());
        self::assertNull($report->getStartedDate());
        self::assertNull($report->getLastAccessedDate());
        self::assertNull($report->getCompletedDate());
        self::assertNull($report->getGrade());
        self::assertNull($report->getGradePercentage());
        self::assertNull($report->getPoints());
        self::assertNull($report->getProgress());
        self::assertNull($report->getSubscriptionName());
        self::assertNull($report->getVariantName());
        self::assertNull($report->getVariantStartDate());
        self::assertNull($report->getVariantEndDate());
        self::assertNull($report->getRoleId());
    }

    /**
     * Test that Client::getLearnerReport() produces the correct output when
     * the query contains all optional information and the SmarterU API
     * returns a single LearnerReport.
     */
    public function testGetLearnerReportProducesCorrectOutputWithOptionalInfoSingleReport() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $enrollmentId = '1';
        $groupNames = ['My Group', 'My Other Group'];
        $tag1 = (new Tag())
            ->setTagName('My Tag')
            ->setTagValues('Tag 1\'s Values');
        $tag2 = (new Tag())
            ->setTagId('3')
            ->setTagValues('Tag 2\'s Values');
        $groupTags = [$tag1, $tag2];
        $learningModuleStatus = 'Active';
        $learningModuleNames = ['Course 1', 'Course 2'];
        $enrollmentStatuses = ['Enrolled', 'In Progress', 'Completed'];
        $completedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-07-31'));
        $completedDate2 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-01'))
            ->setDateTo(new DateTime('2022-09-30'));
        $completedDates = [$completedDate1, $completedDate2];
        $dueDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-08-01'))
            ->setDateTo(new DateTime('2022-09-30'));
        $dueDates = [$dueDate1];
        $enrolledDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-01-01'))
            ->setDateTo(new DateTime('2022-06-30'));
        $enrolledDates = [$enrolledDate1];
        $gracePeriodDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-10-01'))
            ->setDateTo(new DateTime('2022-10-07'));
        $gracePeriodDates = [$gracePeriodDate1];
        $lastAccessedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-14'))
            ->setDateTo(new DateTime('2022-09-21'));
        $lastAccessedDates = [$lastAccessedDate1];
        $startedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-01'))
            ->setDateTo(new DateTime('2022-09-22'));
        $startedDates = [$startedDate1];
        $createdDate = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-07-02'));
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-22'))
            ->setDateTo(new DateTime());
        $userEmailAddresses = ['test@test.com', 'test2@test.com'];
        $userEmployeeIds = ['4', '5', '6'];
        $columns = ['ALTERNATE_EMAIL', 'COMPLETED_DATE'];
        $customField1 = (new CustomField())
            ->setName('My Custom Field');
        $customField2 = (new CustomField())
            ->setName('Other Custom Field');
        $customFields = [$customField1, $customField2];

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId($enrollmentId)
            ->setGroupNames($groupNames)
            ->setGroupTags($groupTags)
            ->setLearningModuleStatus($learningModuleStatus)
            ->setLearningModuleNames($learningModuleNames)
            ->setEnrollmentStatuses($enrollmentStatuses)
            ->setCompletedDates($completedDates)
            ->setDueDates($dueDates)
            ->setEnrolledDates($enrolledDates)
            ->setGracePeriodDates($gracePeriodDates)
            ->setLastAccessedDates($lastAccessedDates)
            ->setStartedDates($startedDates)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setUserEmailAddresses($userEmailAddresses)
            ->setUserEmployeeIds($userEmployeeIds)
            ->setColumns($columns)
            ->setCustomFields($customFields);

        $id = '1';
        $courseName = 'My Course';
        $lastName = 'User';
        $firstName = 'Test';
        $learningModuleId = '2';
        $userId = '3';
        $createdDate = (new DateTime('2022-09-24'))->format('d-M-y');
        $modifiedDate = (new DateTime())->format('d-M-y');
        $alternateEmail = 'test2@test.com';
        $completedDate = (new DateTime('2022-09-27'))->format('d-M-y');
        $courseDuration = '4d';
        $courseSessionId = '4';
        $division = 'division';
        $dueDate = (new DateTime('2022-09-30'))->format('d-M-y');
        $employeeId = '5';
        $enrolledDate = (new DateTime('2022-09-25'))->format('d-M-y');
        $grade = 'A';
        $gradePercentage = 98.0;
        $groupId = '5';
        $groupName = 'My Group';
        $lastAccessedDate = (new DateTime('2022-09-27'))->format('d-M-y');
        $points = 49;
        $progress = '100%';
        $roleId = '6';
        $startedDate = (new DateTime('2022-09-26'))->format('d-M-y');
        $subscriptionName = 'My Subscription';
        $title = 'title';
        $userEmail = 'test@test.com';
        $variantEndDate = (new DateTime('2022-10-01'))->format('d-M-y');
        $variantName = 'variant';
        $variantStartDate = (new DateTime('2022-09-01'))->format('d-M-y');

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <LearnerReport rows="0">
                    <Learner>
                        <ID>$id</ID>
                        <CourseName>$courseName</CourseName>
                        <LastName>$lastName</LastName>
                        <FirstName>$firstName</FirstName>
                        <LearningModuleID>$learningModuleId</LearningModuleID>
                        <UserID>$userId</UserID>
                        <CreatedDate>$createdDate</CreatedDate>
                        <ModifiedDate>$modifiedDate</ModifiedDate>
                        <AlternateEmail>$alternateEmail</AlternateEmail>
                        <CompletedDate>$completedDate</CompletedDate>
                        <CourseDuration>$courseDuration</CourseDuration>
                        <CourseSessionID>$courseSessionId</CourseSessionID>
                        <Division>$division</Division>
                        <DueDate>$dueDate</DueDate>
                        <EmployeeID>$employeeId</EmployeeID>
                        <EnrolledDate>$enrolledDate</EnrolledDate>
                        <Grade>$grade</Grade>
                        <GradePercentage>$gradePercentage</GradePercentage>
                        <GroupID>$groupId</GroupID>
                        <GroupName>$groupName</GroupName>
                        <LastAccessedDate>$lastAccessedDate</LastAccessedDate>
                        <Points>$points</Points>
                        <Progress>$progress</Progress>
                        <RoleID>$roleId</RoleID>
                        <StartedDate>$startedDate</StartedDate>
                        <SubscriptionName>$subscriptionName</SubscriptionName>
                        <Title>$title</Title>
                        <UserEmail>$userEmail</UserEmail>
                        <VariantEndDate>$variantEndDate</VariantEndDate>
                        <VariantName>$variantName</VariantName>
                        <VariantStartDate>$variantStartDate</VariantStartDate>
                    </Learner>
                </LearnerReport>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;

        // Set up the container to capture the request.
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $result = $client->getLearnerReport($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->getLearnerReport(
            $accountApi,
            $userApi,
            $query
        );
        self::assertEquals($decodedBody, $expectedBody);

        // Make sure the expected value is returned.
        self::assertIsArray($result);
        self::assertCount(1, $result);
        $report = $result[0];
        self::assertInstanceOf(LearnerReport::class, $report);
        self::assertEquals($id, $report->getId());
        self::assertEquals($courseName, $report->getCourseName());
        self::assertEquals($lastName, $report->getSurname());
        self::assertEquals($firstName, $report->getGivenName());
        self::assertEquals($learningModuleId, $report->getLearningModuleId());
        self::assertEquals($userId, $report->getUserId());
        self::assertEquals(
            $createdDate,
            $report->getCreatedDate()->format('d-M-y')
        );
        self::assertEquals(
            $modifiedDate,
            $report->getModifiedDate()->format('d-M-y')
        );
        self::assertEquals($userEmail, $report->getUserEmail());
        self::assertEquals($alternateEmail, $report->getAlternateEmail());
        self::assertEquals($employeeId, $report->getEmployeeId());
        self::assertEquals($division, $report->getDivision());
        self::assertEquals($title, $report->getTitle());
        self::assertEquals($groupId, $report->getGroupId());
        self::assertEquals($groupName, $report->getGroupName());
        self::assertEquals($courseDuration, $report->getCourseDuration());
        self::assertEquals($courseSessionId, $report->getCourseSessionId());
        self::assertEquals(
            $enrolledDate,
            $report->getEnrolledDate()->format('d-M-y')
        );
        self::assertEquals($dueDate, $report->getDueDate()->format('d-M-y'));
        self::assertEquals(
            $startedDate,
            $report->getStartedDate()->format('d-M-y')
        );
        self::assertEquals(
            $lastAccessedDate,
            $report->getLastAccessedDate()->format('d-M-y')
        );
        self::assertEquals(
            $completedDate,
            $report->getCompletedDate()->format('d-M-y')
        );
        self::assertEquals($grade, $report->getGrade());
        self::assertEquals($gradePercentage, $report->getGradePercentage());
        self::assertEquals($points, $report->getPoints());
        self::assertEquals($progress, $report->getProgress());
        self::assertEquals($subscriptionName, $report->getSubscriptionName());
        self::assertEquals($variantName, $report->getVariantName());
        self::assertEquals(
            $variantStartDate,
            $report->getVariantStartDate()->format('d-M-y')
        );
        self::assertEquals(
            $variantEndDate,
            $report->getVariantEndDate()->format('d-M-y')
        );
        self::assertEquals($roleId, $report->getRoleId());
    }

    /**
     * Test that Client::getLearnerReport() produces the correct output when
     * the query does not contain any optional information and the SmarterU API
     * returns multiple LearnerReports.
     */
    public function testGetLearnerReportProducesCorrectOutputWithoutOptionalInfoMultipleReports() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId('1')
            ->setGroupStatus('Active')
            ->setUserStatus('Active');

        $id = '1';
        $courseName = 'My Course';
        $lastName = 'User';
        $firstName = 'Test';
        $learningModuleId = '2';
        $userId = '3';
        $createdDate = (new DateTime('2022-09-27'))->format('d-M-y');
        $modifiedDate = (new DateTime())->format('d-M-y');
        $id2 = '4';
        $courseName2 = 'My 2nd Course';
        $id3 = '5';
        $courseName3 = 'My 3rd Course';

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <LearnerReport rows="0">
                    <Learner>
                        <ID>$id</ID>
                        <CourseName>$courseName</CourseName>
                        <LastName>$lastName</LastName>
                        <FirstName>$firstName</FirstName>
                        <LearningModuleID>$learningModuleId</LearningModuleID>
                        <UserID>$userId</UserID>
                        <CreatedDate>$createdDate</CreatedDate>
                        <ModifiedDate>$modifiedDate</ModifiedDate>
                    </Learner>
                    <Learner>
                        <ID>$id2</ID>
                        <CourseName>$courseName2</CourseName>
                        <LastName>$lastName</LastName>
                        <FirstName>$firstName</FirstName>
                        <LearningModuleID>$learningModuleId</LearningModuleID>
                        <UserID>$userId</UserID>
                        <CreatedDate>$createdDate</CreatedDate>
                        <ModifiedDate>$modifiedDate</ModifiedDate>
                    </Learner>
                    <Learner>
                        <ID>$id3</ID>
                        <CourseName>$courseName3</CourseName>
                        <LastName>$lastName</LastName>
                        <FirstName>$firstName</FirstName>
                        <LearningModuleID>$learningModuleId</LearningModuleID>
                        <UserID>$userId</UserID>
                        <CreatedDate>$createdDate</CreatedDate>
                        <ModifiedDate>$modifiedDate</ModifiedDate>
                    </Learner>
                </LearnerReport>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;

        // Set up the container to capture the request.
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $result = $client->getLearnerReport($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->getLearnerReport(
            $accountApi,
            $userApi,
            $query
        );
        self::assertEquals($decodedBody, $expectedBody);

        // Make sure the expected value is returned.
        self::assertIsArray($result);
        self::assertCount(3, $result);
        foreach ($result as $report) {
            self::assertInstanceOf(LearnerReport::class, $report);
            self::assertEquals($lastName, $report->getSurname());
            self::assertEquals($firstName, $report->getGivenName());
            self::assertEquals($learningModuleId, $report->getLearningModuleId());
            self::assertEquals($userId, $report->getUserId());
            self::assertEquals(
                $createdDate,
                $report->getCreatedDate()->format('d-M-y')
            );
            self::assertEquals(
                $modifiedDate,
                $report->getModifiedDate()->format('d-M-y')
            );
            self::assertNull($report->getUserEmail());
            self::assertNull($report->getAlternateEmail());
            self::assertNull($report->getEmployeeId());
            self::assertNull($report->getDivision());
            self::assertNull($report->getTitle());
            self::assertNull($report->getGroupId());
            self::assertNull($report->getGroupName());
            self::assertNull($report->getCourseDuration());
            self::assertNull($report->getCourseSessionId());
            self::assertNull($report->getEnrolledDate());
            self::assertNull($report->getDueDate());
            self::assertNull($report->getStartedDate());
            self::assertNull($report->getLastAccessedDate());
            self::assertNull($report->getCompletedDate());
            self::assertNull($report->getGrade());
            self::assertNull($report->getGradePercentage());
            self::assertNull($report->getPoints());
            self::assertNull($report->getProgress());
            self::assertNull($report->getSubscriptionName());
            self::assertNull($report->getVariantName());
            self::assertNull($report->getVariantStartDate());
            self::assertNull($report->getVariantEndDate());
            self::assertNull($report->getRoleId());
        }
        $report1 = $result[0];
        self::assertEquals($id, $report1->getId());
        self::assertEquals($courseName, $report1->getCourseName());
        $report2 = $result[1];
        self::assertEquals($id2, $report2->getId());
        self::assertEquals($courseName2, $report2->getCourseName());
        $report3 = $result[2];
        self::assertEquals($id3, $report3->getId());
        self::assertEquals($courseName3, $report3->getCourseName());
    }

    /**
     * Test that Client::getLearnerReport() produces the correct output when
     * the query contains all optional information and the SmarterU API
     * returns multiple LearnerReports.
     */
    public function testGetLearnerReportProducesCorrectOutputWithOptionalInfoMultipleReports() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);

        $enrollmentId = '1';
        $groupNames = ['My Group', 'My Other Group'];
        $tag1 = (new Tag())
            ->setTagName('My Tag')
            ->setTagValues('Tag 1\'s Values');
        $tag2 = (new Tag())
            ->setTagId('3')
            ->setTagValues('Tag 2\'s Values');
        $groupTags = [$tag1, $tag2];
        $learningModuleStatus = 'Active';
        $learningModuleNames = ['Course 1', 'Course 2'];
        $enrollmentStatuses = ['Enrolled', 'In Progress', 'Completed'];
        $completedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-07-31'));
        $completedDate2 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-01'))
            ->setDateTo(new DateTime('2022-09-30'));
        $completedDates = [$completedDate1, $completedDate2];
        $dueDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-08-01'))
            ->setDateTo(new DateTime('2022-09-30'));
        $dueDates = [$dueDate1];
        $enrolledDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-01-01'))
            ->setDateTo(new DateTime('2022-06-30'));
        $enrolledDates = [$enrolledDate1];
        $gracePeriodDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-10-01'))
            ->setDateTo(new DateTime('2022-10-07'));
        $gracePeriodDates = [$gracePeriodDate1];
        $lastAccessedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-14'))
            ->setDateTo(new DateTime('2022-09-21'));
        $lastAccessedDates = [$lastAccessedDate1];
        $startedDate1 = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-01'))
            ->setDateTo(new DateTime('2022-09-22'));
        $startedDates = [$startedDate1];
        $createdDate = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-07-01'))
            ->setDateTo(new DateTime('2022-07-02'));
        $modifiedDate = (new DateRangeTag())
            ->setDateFrom(new DateTime('2022-09-22'))
            ->setDateTo(new DateTime());
        $userEmailAddresses = ['test@test.com', 'test2@test.com'];
        $userEmployeeIds = ['4', '5', '6'];
        $columns = ['ALTERNATE_EMAIL', 'COMPLETED_DATE'];
        $customField1 = (new CustomField())
            ->setName('My Custom Field');
        $customField2 = (new CustomField())
            ->setName('Other Custom Field');
        $customFields = [$customField1, $customField2];

        $query = (new GetLearnerReportQuery())
            ->setEnrollmentId($enrollmentId)
            ->setGroupNames($groupNames)
            ->setGroupTags($groupTags)
            ->setLearningModuleStatus($learningModuleStatus)
            ->setLearningModuleNames($learningModuleNames)
            ->setEnrollmentStatuses($enrollmentStatuses)
            ->setCompletedDates($completedDates)
            ->setDueDates($dueDates)
            ->setEnrolledDates($enrolledDates)
            ->setGracePeriodDates($gracePeriodDates)
            ->setLastAccessedDates($lastAccessedDates)
            ->setStartedDates($startedDates)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setUserEmailAddresses($userEmailAddresses)
            ->setUserEmployeeIds($userEmployeeIds)
            ->setColumns($columns)
            ->setCustomFields($customFields);

        $id = '1';
        $courseName = 'My Course';
        $lastName = 'User';
        $firstName = 'Test';
        $learningModuleId = '2';
        $userId = '3';
        $createdDate = (new DateTime('2022-09-24'))->format('d-M-y');
        $modifiedDate = (new DateTime())->format('d-M-y');
        $alternateEmail = 'test2@test.com';
        $completedDate = (new DateTime('2022-09-27'))->format('d-M-y');
        $courseDuration = '4d';
        $courseSessionId = '4';
        $division = 'division';
        $dueDate = (new DateTime('2022-09-30'))->format('d-M-y');
        $employeeId = '5';
        $enrolledDate = (new DateTime('2022-09-25'))->format('d-M-y');
        $grade = 'A';
        $gradePercentage = 98.0;
        $groupId = '5';
        $groupName = 'My Group';
        $lastAccessedDate = (new DateTime('2022-09-27'))->format('d-M-y');
        $points = 49;
        $progress = '100%';
        $roleId = '6';
        $startedDate = (new DateTime('2022-09-26'))->format('d-M-y');
        $subscriptionName = 'My Subscription';
        $title = 'title';
        $userEmail = 'test@test.com';
        $variantEndDate = (new DateTime('2022-10-01'))->format('d-M-y');
        $variantName = 'variant';
        $variantStartDate = (new DateTime('2022-09-01'))->format('d-M-y');
        $id2 = '7';
        $courseName2 = 'My 2nd Course';
        $lastName2 = 'User2';
        $firstName2 = 'Test2';
        $learningModuleId2 = '8';
        $userId2 = '9';
        $createdDate2 = (new DateTime('2022-08-24'))->format('d-M-y');
        $modifiedDate2 = (new DateTime('2022-08-28'))->format('d-M-y');
        $alternateEmail2 = 'test2@test2.com';
        $completedDate2 = (new DateTime('2022-08-27'))->format('d-M-y');
        $courseDuration2 = '4d';
        $courseSessionId2 = '10';
        $division2 = 'division2';
        $dueDate2 = (new DateTime('2022-08-30'))->format('d-M-y');
        $employeeId2 = '11';
        $enrolledDate2 = (new DateTime('2022-08-25'))->format('d-M-y');
        $grade2 = 'B';
        $gradePercentage2 = 89.0;
        $groupId2 = '12';
        $groupName2 = 'My 2nd Group';
        $lastAccessedDate2 = (new DateTime('2022-08-27'))->format('d-M-y');
        $points2 = 89;
        $progress2 = '100%';
        $roleId2 = '13';
        $startedDate2 = (new DateTime('2022-08-26'))->format('d-M-y');
        $subscriptionName2 = 'My 2nd Subscription';
        $title2 = 'title2';
        $userEmail2 = 'test@test2.com';
        $variantEndDate2 = (new DateTime('2022-09-01'))->format('d-M-y');
        $variantName2 = 'variant2';
        $variantStartDate2 = (new DateTime('2022-08-01'))->format('d-M-y');
        $id3 = '14';
        $courseName3 = 'My 3rd Course';
        $lastName3 = 'User3';
        $firstName3 = 'Test3';
        $learningModuleId3 = '15';
        $userId3 = '16';
        $createdDate3 = (new DateTime('2022-07-24'))->format('d-M-y');
        $modifiedDate3 = (new DateTime('2022-07-28'))->format('d-M-y');
        $alternateEmail3 = 'test2@test3.com';
        $completedDate3 = (new DateTime('2022-07-27'))->format('d-M-y');
        $courseDuration3 = '4d';
        $courseSessionId3 = '17';
        $division3 = 'division3';
        $dueDate3 = (new DateTime('2022-07-30'))->format('d-M-y');
        $employeeId3 = '18';
        $enrolledDate3 = (new DateTime('2022-07-25'))->format('d-M-y');
        $grade3 = 'C';
        $gradePercentage3 = 75.0;
        $groupId3 = '19';
        $groupName3 = 'My 3rd Group';
        $lastAccessedDate3 = (new DateTime('2022-07-27'))->format('d-M-y');
        $points3 = 75;
        $progress3 = '100%';
        $roleId3 = '20';
        $startedDate3 = (new DateTime('2022-07-26'))->format('d-M-y');
        $subscriptionName3 = 'My 3rd Subscription';
        $title3 = 'title3';
        $userEmail3 = 'test@test3.com';
        $variantEndDate3 = (new DateTime('2022-08-01'))->format('d-M-y');
        $variantName3 = 'variant3';
        $variantStartDate3 = (new DateTime('2022-07-01'))->format('d-M-y');

        $xmlString = <<<XML
        <SmarterU>
            <Result>Success</Result>
            <Info>
                <LearnerReport rows="0">
                    <Learner>
                        <ID>$id</ID>
                        <CourseName>$courseName</CourseName>
                        <LastName>$lastName</LastName>
                        <FirstName>$firstName</FirstName>
                        <LearningModuleID>$learningModuleId</LearningModuleID>
                        <UserID>$userId</UserID>
                        <CreatedDate>$createdDate</CreatedDate>
                        <ModifiedDate>$modifiedDate</ModifiedDate>
                        <AlternateEmail>$alternateEmail</AlternateEmail>
                        <CompletedDate>$completedDate</CompletedDate>
                        <CourseDuration>$courseDuration</CourseDuration>
                        <CourseSessionID>$courseSessionId</CourseSessionID>
                        <Division>$division</Division>
                        <DueDate>$dueDate</DueDate>
                        <EmployeeID>$employeeId</EmployeeID>
                        <EnrolledDate>$enrolledDate</EnrolledDate>
                        <Grade>$grade</Grade>
                        <GradePercentage>$gradePercentage</GradePercentage>
                        <GroupID>$groupId</GroupID>
                        <GroupName>$groupName</GroupName>
                        <LastAccessedDate>$lastAccessedDate</LastAccessedDate>
                        <Points>$points</Points>
                        <Progress>$progress</Progress>
                        <RoleID>$roleId</RoleID>
                        <StartedDate>$startedDate</StartedDate>
                        <SubscriptionName>$subscriptionName</SubscriptionName>
                        <Title>$title</Title>
                        <UserEmail>$userEmail</UserEmail>
                        <VariantEndDate>$variantEndDate</VariantEndDate>
                        <VariantName>$variantName</VariantName>
                        <VariantStartDate>$variantStartDate</VariantStartDate>
                    </Learner>
                    <Learner>
                        <ID>$id2</ID>
                        <CourseName>$courseName2</CourseName>
                        <LastName>$lastName2</LastName>
                        <FirstName>$firstName2</FirstName>
                        <LearningModuleID>$learningModuleId2</LearningModuleID>
                        <UserID>$userId2</UserID>
                        <CreatedDate>$createdDate2</CreatedDate>
                        <ModifiedDate>$modifiedDate2</ModifiedDate>
                        <AlternateEmail>$alternateEmail2</AlternateEmail>
                        <CompletedDate>$completedDate2</CompletedDate>
                        <CourseDuration>$courseDuration2</CourseDuration>
                        <CourseSessionID>$courseSessionId2</CourseSessionID>
                        <Division>$division2</Division>
                        <DueDate>$dueDate2</DueDate>
                        <EmployeeID>$employeeId2</EmployeeID>
                        <EnrolledDate>$enrolledDate2</EnrolledDate>
                        <Grade>$grade2</Grade>
                        <GradePercentage>$gradePercentage2</GradePercentage>
                        <GroupID>$groupId2</GroupID>
                        <GroupName>$groupName2</GroupName>
                        <LastAccessedDate>$lastAccessedDate2</LastAccessedDate>
                        <Points>$points2</Points>
                        <Progress>$progress2</Progress>
                        <RoleID>$roleId2</RoleID>
                        <StartedDate>$startedDate2</StartedDate>
                        <SubscriptionName>$subscriptionName2</SubscriptionName>
                        <Title>$title2</Title>
                        <UserEmail>$userEmail2</UserEmail>
                        <VariantEndDate>$variantEndDate2</VariantEndDate>
                        <VariantName>$variantName2</VariantName>
                        <VariantStartDate>$variantStartDate2</VariantStartDate>
                    </Learner>
                    <Learner>
                        <ID>$id3</ID>
                        <CourseName>$courseName3</CourseName>
                        <LastName>$lastName3</LastName>
                        <FirstName>$firstName3</FirstName>
                        <LearningModuleID>$learningModuleId3</LearningModuleID>
                        <UserID>$userId3</UserID>
                        <CreatedDate>$createdDate3</CreatedDate>
                        <ModifiedDate>$modifiedDate3</ModifiedDate>
                        <AlternateEmail>$alternateEmail3</AlternateEmail>
                        <CompletedDate>$completedDate3</CompletedDate>
                        <CourseDuration>$courseDuration3</CourseDuration>
                        <CourseSessionID>$courseSessionId3</CourseSessionID>
                        <Division>$division3</Division>
                        <DueDate>$dueDate3</DueDate>
                        <EmployeeID>$employeeId3</EmployeeID>
                        <EnrolledDate>$enrolledDate3</EnrolledDate>
                        <Grade>$grade3</Grade>
                        <GradePercentage>$gradePercentage3</GradePercentage>
                        <GroupID>$groupId3</GroupID>
                        <GroupName>$groupName3</GroupName>
                        <LastAccessedDate>$lastAccessedDate3</LastAccessedDate>
                        <Points>$points3</Points>
                        <Progress>$progress3</Progress>
                        <RoleID>$roleId3</RoleID>
                        <StartedDate>$startedDate3</StartedDate>
                        <SubscriptionName>$subscriptionName3</SubscriptionName>
                        <Title>$title3</Title>
                        <UserEmail>$userEmail3</UserEmail>
                        <VariantEndDate>$variantEndDate3</VariantEndDate>
                        <VariantName>$variantName3</VariantName>
                        <VariantStartDate>$variantStartDate3</VariantStartDate>
                    </Learner>
                </LearnerReport>
            </Info>
            <Errors>
            </Errors>
        </SmarterU>
        XML;

        // Set up the container to capture the request.
        $response = new Response(200, [], $xmlString);
        $container = [];
        $history = Middleware::history($container);
        $mock = (new MockHandler([$response]));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $client->setHttpClient($httpClient);

        // Make the request.
        $result = $client->getLearnerReport($query);

        // Make sure there is only 1 request, then translate it to XML.
        self::assertCount(1, $container);
        $request = $container[0]['request'];
        $decodedBody = urldecode((string) $request->getBody());
        $expectedBody = 'Package=' . $client->getXMLGenerator()->getLearnerReport(
            $accountApi,
            $userApi,
            $query
        );
        self::assertEquals($decodedBody, $expectedBody);

        // Make sure the expected value is returned.
        self::assertIsArray($result);
        self::assertCount(3, $result);
        $report1 = $result[0];
        $report2 = $result[1];
        $report3 = $result[2];
        self::assertInstanceOf(LearnerReport::class, $report1);
        self::assertEquals($id, $report1->getId());
        self::assertEquals($courseName, $report1->getCourseName());
        self::assertEquals($lastName, $report1->getSurname());
        self::assertEquals($firstName, $report1->getGivenName());
        self::assertEquals($learningModuleId, $report1->getLearningModuleId());
        self::assertEquals($userId, $report1->getUserId());
        self::assertEquals(
            $createdDate,
            $report1->getCreatedDate()->format('d-M-y')
        );
        self::assertEquals(
            $modifiedDate,
            $report1->getModifiedDate()->format('d-M-y')
        );
        self::assertEquals($userEmail, $report1->getUserEmail());
        self::assertEquals($alternateEmail, $report1->getAlternateEmail());
        self::assertEquals($employeeId, $report1->getEmployeeId());
        self::assertEquals($division, $report1->getDivision());
        self::assertEquals($title, $report1->getTitle());
        self::assertEquals($groupId, $report1->getGroupId());
        self::assertEquals($groupName, $report1->getGroupName());
        self::assertEquals($courseDuration, $report1->getCourseDuration());
        self::assertEquals($courseSessionId, $report1->getCourseSessionId());
        self::assertEquals(
            $enrolledDate,
            $report1->getEnrolledDate()->format('d-M-y')
        );
        self::assertEquals($dueDate, $report1->getDueDate()->format('d-M-y'));
        self::assertEquals(
            $startedDate,
            $report1->getStartedDate()->format('d-M-y')
        );
        self::assertEquals(
            $lastAccessedDate,
            $report1->getLastAccessedDate()->format('d-M-y')
        );
        self::assertEquals(
            $completedDate,
            $report1->getCompletedDate()->format('d-M-y')
        );
        self::assertEquals($grade, $report1->getGrade());
        self::assertEquals($gradePercentage, $report1->getGradePercentage());
        self::assertEquals($points, $report1->getPoints());
        self::assertEquals($progress, $report1->getProgress());
        self::assertEquals($subscriptionName, $report1->getSubscriptionName());
        self::assertEquals($variantName, $report1->getVariantName());
        self::assertEquals(
            $variantStartDate,
            $report1->getVariantStartDate()->format('d-M-y')
        );
        self::assertEquals(
            $variantEndDate,
            $report1->getVariantEndDate()->format('d-M-y')
        );
        self::assertEquals($roleId, $report1->getRoleId());
        self::assertInstanceOf(LearnerReport::class, $report2);
        self::assertEquals($id2, $report2->getId());
        self::assertEquals($courseName2, $report2->getCourseName());
        self::assertEquals($lastName2, $report2->getSurname());
        self::assertEquals($firstName2, $report2->getGivenName());
        self::assertEquals($learningModuleId2, $report2->getLearningModuleId());
        self::assertEquals($userId2, $report2->getUserId());
        self::assertEquals(
            $createdDate2,
            $report2->getCreatedDate()->format('d-M-y')
        );
        self::assertEquals(
            $modifiedDate2,
            $report2->getModifiedDate()->format('d-M-y')
        );
        self::assertEquals($userEmail2, $report2->getUserEmail());
        self::assertEquals($alternateEmail2, $report2->getAlternateEmail());
        self::assertEquals($employeeId2, $report2->getEmployeeId());
        self::assertEquals($division2, $report2->getDivision());
        self::assertEquals($title2, $report2->getTitle());
        self::assertEquals($groupId2, $report2->getGroupId());
        self::assertEquals($groupName2, $report2->getGroupName());
        self::assertEquals($courseDuration2, $report2->getCourseDuration());
        self::assertEquals($courseSessionId2, $report2->getCourseSessionId());
        self::assertEquals(
            $enrolledDate2,
            $report2->getEnrolledDate()->format('d-M-y')
        );
        self::assertEquals($dueDate2, $report2->getDueDate()->format('d-M-y'));
        self::assertEquals(
            $startedDate2,
            $report2->getStartedDate()->format('d-M-y')
        );
        self::assertEquals(
            $lastAccessedDate2,
            $report2->getLastAccessedDate()->format('d-M-y')
        );
        self::assertEquals(
            $completedDate2,
            $report2->getCompletedDate()->format('d-M-y')
        );
        self::assertEquals($grade2, $report2->getGrade());
        self::assertEquals($gradePercentage2, $report2->getGradePercentage());
        self::assertEquals($points2, $report2->getPoints());
        self::assertEquals($progress2, $report2->getProgress());
        self::assertEquals($subscriptionName2, $report2->getSubscriptionName());
        self::assertEquals($variantName2, $report2->getVariantName());
        self::assertEquals(
            $variantStartDate2,
            $report2->getVariantStartDate()->format('d-M-y')
        );
        self::assertEquals(
            $variantEndDate2,
            $report2->getVariantEndDate()->format('d-M-y')
        );
        self::assertEquals($roleId2, $report2->getRoleId());
        self::assertInstanceOf(LearnerReport::class, $report3);
        self::assertEquals($id3, $report3->getId());
        self::assertEquals($courseName3, $report3->getCourseName());
        self::assertEquals($lastName3, $report3->getSurname());
        self::assertEquals($firstName3, $report3->getGivenName());
        self::assertEquals($learningModuleId3, $report3->getLearningModuleId());
        self::assertEquals($userId3, $report3->getUserId());
        self::assertEquals(
            $createdDate3,
            $report3->getCreatedDate()->format('d-M-y')
        );
        self::assertEquals(
            $modifiedDate3,
            $report3->getModifiedDate()->format('d-M-y')
        );
        self::assertEquals($userEmail3, $report3->getUserEmail());
        self::assertEquals($alternateEmail3, $report3->getAlternateEmail());
        self::assertEquals($employeeId3, $report3->getEmployeeId());
        self::assertEquals($division3, $report3->getDivision());
        self::assertEquals($title3, $report3->getTitle());
        self::assertEquals($groupId3, $report3->getGroupId());
        self::assertEquals($groupName3, $report3->getGroupName());
        self::assertEquals($courseDuration3, $report3->getCourseDuration());
        self::assertEquals($courseSessionId3, $report3->getCourseSessionId());
        self::assertEquals(
            $enrolledDate3,
            $report3->getEnrolledDate()->format('d-M-y')
        );
        self::assertEquals($dueDate3, $report3->getDueDate()->format('d-M-y'));
        self::assertEquals(
            $startedDate3,
            $report3->getStartedDate()->format('d-M-y')
        );
        self::assertEquals(
            $lastAccessedDate3,
            $report3->getLastAccessedDate()->format('d-M-y')
        );
        self::assertEquals(
            $completedDate3,
            $report3->getCompletedDate()->format('d-M-y')
        );
        self::assertEquals($grade3, $report3->getGrade());
        self::assertEquals($gradePercentage3, $report3->getGradePercentage());
        self::assertEquals($points3, $report3->getPoints());
        self::assertEquals($progress3, $report3->getProgress());
        self::assertEquals($subscriptionName3, $report3->getSubscriptionName());
        self::assertEquals($variantName3, $report3->getVariantName());
        self::assertEquals(
            $variantStartDate3,
            $report3->getVariantStartDate()->format('d-M-y')
        );
        self::assertEquals(
            $variantEndDate3,
            $report3->getVariantEndDate()->format('d-M-y')
        );
        self::assertEquals($roleId3, $report3->getRoleId());
    }
}
