<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\CreateUserXMLTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/01
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::createUser().
 */
class CreateUserXMLTest extends TestCase {
    /**
     * A User to use for testing purposes. This User has all optional and all
     * required information set.
     */
    protected User $user1;

    /**
     * A User to use for testing purposes. This User only has optional
     * information set.
     */
    protected User $user2;

    /**
     * A User to use for testing purposes. This User has a missing attribute
     * that will trigger an exception.
     */
    protected User $user3;

    /**
     * Set up the test Users.
     */
    public function setUp(): void {
        $this->user1 = (new User())
            ->setId('1')
            ->setEmail('phpunit@test.com')
            ->setEmployeeId('1')
            ->setGivenName('PHP')
            ->setSurname('Unit')
            ->setPassword('password')
            ->setTimezone('EST')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true)
            ->setSendEmailTo('Self')
            ->setAlternateEmail('phpunit@test1.com')
            ->setAuthenticationType('External')
            ->setSupervisors(['supervisor1', 'supervisor2'])
            ->setOrganization('organization')
            ->setTeams(['team1', 'team2'])
            ->setLanguage('English')
            ->setStatus('Active')
            ->setTitle('Title')
            ->setDivision('division')
            ->setAllowFeedback(true)
            ->setPhonePrimary('555-555-5555')
            ->setPhoneAlternate('555-555-1234')
            ->setPhoneMobile('555-555-4321')
            ->setFax('555-555-5432')
            ->setWebsite('https://localhost')
            ->setAddress1('123 Main St')
            ->setAddress2('Apt. 1')
            ->setCity('Anytown')
            ->setProvince('Pennsylvania')
            ->setCountry('United States')
            ->setPostalCode('12345')
            ->setSendMailTo('Personal')
            ->setReceiveNotifications(true)
            ->setHomeGroup('My Home Group');

        $this->user2 = (new User())
            ->setEmail('example@email.com')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setPassword('myPassword1')
            ->setLearnerNotifications(false)
            ->setSupervisorNotifications(false)
            ->setSendEmailTo('Self')
            ->setAuthenticationType('SmarterU')
            ->setHomeGroup('HomeGroup');

        $this->user3 = (new User())
            ->setEmail('example1@email.com')
            ->setGivenName('Test')
            ->setSurname('User2')
            ->setPassword('myPassword1')
            ->setLearnerNotifications(false)
            ->setSupervisorNotifications(false)
            ->setSendEmailTo('Self')
            ->setAuthenticationType('SmarterU');
    }

    /**
     * Tests that the XML generation process for a CreateUser request throws
     * an exception if the User being created does not have a home group.
     */
    public function testCreateUserThrowsExceptionWhenNoHomeGroup() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot create a User without a Home Group.'
        );
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $this->user3
        );
    }

    /**
     * Tests that the XML generation process for a CreateUser request throws
     * an exception if the User being created does not have an email address
     * or an employee ID.
     */
    public function testCreateUserThrowsExceptionWhenNoUserIdentifier() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $user = new User();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot create a User without either an email or employee ID.'
        );
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $user
        );
    }

    /**
     * Tests that the XML generation process for a CreateUser request produces
     * the expected output when all required information is present but all
     * optional attributes are left blank.
     */
    public function testCreateUserProducesExpectedOutputWithoutRequiredInfo() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $this->user2
        );

        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);

        self::assertEquals($xml->getName(), 'SmarterU');
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('createUser', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        $userInfo = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $userInfo[] = $user->getName();
        }
        self::assertCount(5, $userInfo);
        self::assertContains('Info', $userInfo);
        self::assertContains('Profile', $userInfo);
        self::assertContains('Groups', $userInfo);
        self::assertContains('Venues', $userInfo);
        self::assertContains('Wages', $userInfo);

        // Ensure that the <Info> tag has the correct children.
        $infoTag = [];
        foreach ($xml->Parameters->User->Info->children() as $info) {
            $infoTag[] = $info->getName();
        }
        self::assertCount(8, $infoTag);
        self::assertContains('Email', $infoTag);
        self::assertEquals(
            $this->user2->getEmail(),
            $xml->Parameters->User->Info->Email
        );
        self::assertContains('GivenName', $infoTag);
        self::assertEquals(
            $this->user2->getGivenName(),
            $xml->Parameters->User->Info->GivenName
        );
        self::assertContains('Surname', $infoTag);
        self::assertEquals(
            $this->user2->getSurname(),
            $xml->Parameters->User->Info->Surname
        );
        self::assertContains('Password', $infoTag);
        self::assertEquals(
            $this->user2->getPassword(),
            $xml->Parameters->User->Info->Password
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            $this->user2->getLearnerNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            $this->user2->getSupervisorNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->SupervisorNotifications
        );
        self::assertContains('SendEmailTo', $infoTag);
        self::assertEquals(
            $this->user2->getSendEmailTo(),
            $xml->Parameters->User->Info->SendEmailTo
        );
        self::assertContains('AuthenticationType', $infoTag);
        self::assertEquals(
            $this->user2->getAuthenticationType(),
            $xml->Parameters->User->Info->AuthenticationType
        );

        // Ensure that the <Profile> tag has the correct children.
        $profileTag = [];
        foreach ($xml->Parameters->User->Profile->children() as $profile) {
            $profileTag[] = $profile->getName();
        }
        self::assertCount(3, $profileTag);
        self::assertContains('Status', $profileTag);
        self::assertEquals(
            $this->user2->getStatus(),
            $xml->Parameters->User->Profile->Status
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            $this->user2->getReceiveNotifications() ? 'True' : 'False',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $this->user2->getHomeGroup(),
            $xml->Parameters->User->Profile->HomeGroup
        );

        // Ensure that the <Groups> tag has the correct children.
        $group1 = $xml->Parameters->User->Groups->Group[0];
        $group1Elements = [];
        foreach ($group1->children() as $group) {
            $group1Elements[] = $group->getName();
        }
        self::assertCount(2, $group1Elements);
        self::assertContains('GroupName', $group1Elements);
        self::assertEquals(
            $this->user2->getHomeGroup(),
            $group1->GroupName
        );
        self::assertContains('GroupPermissions', $group1Elements);
        $permissionTags = [];
        foreach ($group1->GroupPermissions->children() as $tag) {
            $permissionTags[] = (array) $tag;
        }
        self::assertCount(0, $permissionTags);

        // Ensure that the <Venues> and <Wages> tags are empty.
        self::assertCount(
            0,
            $xml->Parameters->User->Venues->children()
        );
        self::assertCount(
            0,
            $xml->Parameters->User->Wages->Children()
        );
    }

    /**
     * Tests that the XML generation process for a CreateUser request produces
     * the expected output when all required and optional information is present.
     */
    public function testCreateUserProducesExpectedOutputWithAllInfo() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $this->user1
        );

        self::assertIsString($xml);
        $xml = simplexml_load_string($xml);

        self::assertEquals($xml->getName(), 'SmarterU');
        $elements = [];
        foreach ($xml->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('createUser', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        $userInfo = [];
        foreach ($xml->Parameters->User->children() as $user) {
            $userInfo[] = $user->getName();
        }
        self::assertCount(5, $userInfo);
        self::assertContains('Info', $userInfo);
        self::assertContains('Profile', $userInfo);
        self::assertContains('Groups', $userInfo);
        self::assertContains('Venues', $userInfo);
        self::assertContains('Wages', $userInfo);

        // Ensure that the <Info> tag has the correct children.
        $infoTag = [];
        foreach ($xml->Parameters->User->Info->children() as $info) {
            $infoTag[] = $info->getName();
        }
        self::assertCount(11, $infoTag);
        self::assertContains('Email', $infoTag);
        self::assertEquals(
            $this->user1->getEmail(),
            $xml->Parameters->User->Info->Email
        );
        self::assertContains('EmployeeID', $infoTag);
        self::assertEquals(
            $this->user1->getEmployeeId(),
            $xml->Parameters->User->Info->EmployeeID
        );
        self::assertContains('GivenName', $infoTag);
        self::assertEquals(
            $this->user1->getGivenName(),
            $xml->Parameters->User->Info->GivenName
        );
        self::assertContains('Surname', $infoTag);
        self::assertEquals(
            $this->user1->getSurname(),
            $xml->Parameters->User->Info->Surname
        );
        self::assertContains('Password', $infoTag);
        self::assertEquals(
            $this->user1->getPassword(),
            $xml->Parameters->User->Info->Password
        );
        self::assertContains('Timezone', $infoTag);
        self::assertEquals(
            $this->user1->getTimezone(),
            $xml->Parameters->User->Info->Timezone
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            (string) $this->user1->getLearnerNotifications(),
            $xml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            (string) $this->user1->getSupervisorNotifications(),
            $xml->Parameters->User->Info->SupervisorNotifications
        );
        self::assertContains('SendEmailTo', $infoTag);
        self::assertEquals(
            $this->user1->getSendEmailTo(),
            $xml->Parameters->User->Info->SendEmailTo
        );
        self::assertContains('AlternateEmail', $infoTag);
        self::assertEquals(
            $this->user1->getAlternateEmail(),
            $xml->Parameters->User->Info->AlternateEmail
        );
        self::assertContains('AuthenticationType', $infoTag);
        self::assertEquals(
            $this->user1->getAuthenticationType(),
            $xml->Parameters->User->Info->AuthenticationType
        );

        // Ensure that the <Profile> tag has the correct children.
        $profileTag = [];
        foreach ($xml->Parameters->User->Profile->children() as $profile) {
            $profileTag[] = $profile->getName();
        }
        self::assertCount(22, $profileTag);
        self::assertContains('Supervisors', $profileTag);
        $supervisors = $xml->Parameters->User->Profile->Supervisors->asXML();
        $supervisor1 =
            '<Supervisors><Supervisor>'
            . $this->user1->getSupervisors()[0]
            . '</Supervisor>';
        $supervisor2 =
            '<Supervisor>'
            . $this->user1->getSupervisors()[1]
            . '</Supervisor></Supervisors>';
        self::assertStringContainsString($supervisor1, $supervisors);
        self::assertStringContainsString($supervisor2, $supervisors);
        self::assertContains('Organization', $profileTag);
        self::assertEquals(
            $this->user1->getOrganization(),
            $xml->Parameters->User->Profile->Organization
        );
        self::assertContains('Teams', $profileTag);
        $teams = $xml->Parameters->User->Profile->Teams->asXML();
        $team1 = '<Teams><Team>' . $this->user1->getTeams()[0] . '</Team>';
        $team2 = '<Team>' . $this->user1->getTeams()[1] . '</Team></Teams>';
        self::assertStringContainsString($team1, $teams);
        self::assertStringContainsString($team2, $teams);
        self::assertContains('Language', $profileTag);
        self::assertEquals(
            $this->user1->getLanguage(),
            $xml->Parameters->User->Profile->Language
        );
        self::assertContains('Status', $profileTag);
        self::assertEquals(
            $this->user1->getStatus(),
            $xml->Parameters->User->Profile->Status
        );
        self::assertContains('Title', $profileTag);
        self::assertEquals(
            $this->user1->getTitle(),
            $xml->Parameters->User->Profile->Title
        );
        self::assertContains('Division', $profileTag);
        self::assertEquals(
            $this->user1->getDivision(),
            $xml->Parameters->User->Profile->Division
        );
        self::assertContains('AllowFeedback', $profileTag);
        self::assertEquals(
            $this->user1->getAllowFeedback() ? 'True' : 'False',
            $xml->Parameters->User->Profile->AllowFeedback
        );
        self::assertContains('PhonePrimary', $profileTag);
        self::assertEquals(
            $this->user1->getPhonePrimary(),
            $xml->Parameters->User->Profile->PhonePrimary
        );
        self::assertContains('PhoneAlternate', $profileTag);
        self::assertEquals(
            $this->user1->getPhoneAlternate(),
            $xml->Parameters->User->Profile->PhoneAlternate
        );
        self::assertContains('PhoneMobile', $profileTag);
        self::assertEquals(
            $this->user1->getPhoneMobile(),
            $xml->Parameters->User->Profile->PhoneMobile
        );
        self::assertContains('Fax', $profileTag);
        self::assertEquals(
            $this->user1->getFax(),
            $xml->Parameters->User->Profile->Fax
        );
        self::assertContains('Website', $profileTag);
        self::assertEquals(
            $this->user1->getWebsite(),
            $xml->Parameters->User->Profile->Website
        );
        self::assertContains('Address1', $profileTag);
        self::assertEquals(
            $this->user1->getAddress1(),
            $xml->Parameters->User->Profile->Address1
        );
        self::assertContains('Address2', $profileTag);
        self::assertEquals(
            $this->user1->getAddress2(),
            $xml->Parameters->User->Profile->Address2
        );
        self::assertContains('City', $profileTag);
        self::assertEquals(
            $this->user1->getCity(),
            $xml->Parameters->User->Profile->City
        );
        self::assertContains('Province', $profileTag);
        self::assertEquals(
            $this->user1->getProvince(),
            $xml->Parameters->User->Profile->Province
        );
        self::assertContains('Country', $profileTag);
        self::assertEquals(
            $this->user1->getCountry(),
            $xml->Parameters->User->Profile->Country
        );
        self::assertContains('PostalCode', $profileTag);
        self::assertEquals(
            $this->user1->getPostalCode(),
            $xml->Parameters->User->Profile->PostalCode
        );
        self::assertContains('SendMailTo', $profileTag);
        self::assertEquals(
            $this->user1->getSendMailTo(),
            $xml->Parameters->User->Profile->SendMailTo
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            $this->user1->getReceiveNotifications() ? 'True' : 'False',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $this->user1->getHomeGroup(),
            $xml->Parameters->User->Profile->HomeGroup
        );

        // Ensure that the <Groups> tag has the correct children.
        $group1 = $xml->Parameters->User->Groups->Group[0];
        $group1Elements = [];
        foreach ($group1->children() as $group) {
            $group1Elements[] = $group->getName();
        }
        self::assertCount(2, $group1Elements);
        self::assertContains('GroupName', $group1Elements);
        self::assertEquals(
            $this->user1->getHomeGroup(),
            $group1->GroupName
        );
        self::assertContains('GroupPermissions', $group1Elements);
        $permissionTags = [];
        foreach ($group1->GroupPermissions->children() as $tag) {
            $permissionTags[] = (array) $tag;
        }
        self::assertCount(0, $permissionTags);

        // Ensure that the <Venues> and <Wages> tags are empty.
        self::assertCount(
            0,
            $xml->Parameters->User->Venues->children()
        );
        self::assertCount(
            0,
            $xml->Parameters->User->Wages->Children()
        );
    }
}
