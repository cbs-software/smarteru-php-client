<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\CreateUserXMLTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\Timezone;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::createUser().
 */
class CreateUserXMLTest extends TestCase {
    /**
     * Tests that the XML generation process for a CreateUser request throws
     * an exception if the User being created does not have a home group.
     */
    public function testCreateUserThrowsExceptionWhenNoHomeGroup() {
        $user = (new User())
            ->setEmail('example1@email.com')
            ->setGivenName('Test')
            ->setSurname('User2')
            ->setPassword('myPassword1')
            ->setLearnerNotifications(false)
            ->setSupervisorNotifications(false)
            ->setSendEmailTo('Self')
            ->setAuthenticationType('SmarterU');

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
            $user
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
     * optional attributes are left blank, and the user is identified by their
     * email address.
     */
    public function testCreateUserProducesExpectedOutputWithoutRequiredInfoUsingEmail() {
        $user = (new User())
            ->setEmail('example@email.com')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setPassword('myPassword1')
            ->setLearnerNotifications(false)
            ->setSupervisorNotifications(false)
            ->setSendEmailTo('Self')
            ->setAuthenticationType('SmarterU')
            ->setHomeGroup('HomeGroup');

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $user
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
        foreach ($xml->Parameters->User->children() as $userTag) {
            $userInfo[] = $userTag->getName();
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
        self::assertCount(9, $infoTag);
        self::assertContains('Email', $infoTag);
        self::assertEquals(
            $user->getEmail(),
            $xml->Parameters->User->Info->Email
        );
        self::assertContains('EmployeeID', $infoTag);
        self::assertEquals('', $xml->Parameters->User->Info->EmployeeID);
        self::assertContains('GivenName', $infoTag);
        self::assertEquals(
            $user->getGivenName(),
            $xml->Parameters->User->Info->GivenName
        );
        self::assertContains('Surname', $infoTag);
        self::assertEquals(
            $user->getSurname(),
            $xml->Parameters->User->Info->Surname
        );
        self::assertContains('Password', $infoTag);
        self::assertEquals(
            $user->getPassword(),
            $xml->Parameters->User->Info->Password
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            $user->getLearnerNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            $user->getSupervisorNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->SupervisorNotifications
        );
        self::assertContains('SendEmailTo', $infoTag);
        self::assertEquals(
            $user->getSendEmailTo(),
            $xml->Parameters->User->Info->SendEmailTo
        );
        self::assertContains('AuthenticationType', $infoTag);
        self::assertEquals(
            $user->getAuthenticationType(),
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
            $user->getStatus(),
            $xml->Parameters->User->Profile->Status
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            $user->getReceiveNotifications() ? '1' : '0',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $user->getHomeGroup(),
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
            $user->getHomeGroup(),
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
     * the expected output when all required information is present but all
     * optional attributes are left blank, and the user is identified by their
     * employee ID.
     */
    public function testCreateUserProducesExpectedOutputWithoutRequiredInfoUsingEmployeeID() {
        $user = (new User())
            ->setEmployeeId('12')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setPassword('myPassword1')
            ->setLearnerNotifications(false)
            ->setSupervisorNotifications(false)
            ->setSendEmailTo('Self')
            ->setAuthenticationType('SmarterU')
            ->setHomeGroup('HomeGroup');

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $user
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
        foreach ($xml->Parameters->User->children() as $userTag) {
            $userInfo[] = $userTag->getName();
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
        self::assertCount(9, $infoTag);
        self::assertContains('Email', $infoTag);
        self::assertEquals(
            '',
            $xml->Parameters->User->Info->Email
        );
        self::assertContains('EmployeeID', $infoTag);
        self::assertEquals(
            $user->getEmployeeId(),
            $xml->Parameters->User->Info->EmployeeID
        );
        self::assertContains('GivenName', $infoTag);
        self::assertEquals(
            $user->getGivenName(),
            $xml->Parameters->User->Info->GivenName
        );
        self::assertContains('Surname', $infoTag);
        self::assertEquals(
            $user->getSurname(),
            $xml->Parameters->User->Info->Surname
        );
        self::assertContains('Password', $infoTag);
        self::assertEquals(
            $user->getPassword(),
            $xml->Parameters->User->Info->Password
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            $user->getLearnerNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            $user->getSupervisorNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->SupervisorNotifications
        );
        self::assertContains('SendEmailTo', $infoTag);
        self::assertEquals(
            $user->getSendEmailTo(),
            $xml->Parameters->User->Info->SendEmailTo
        );
        self::assertContains('AuthenticationType', $infoTag);
        self::assertEquals(
            $user->getAuthenticationType(),
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
            $user->getStatus(),
            $xml->Parameters->User->Profile->Status
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            $user->getReceiveNotifications() ? '1' : '0',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $user->getHomeGroup(),
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
            $user->getHomeGroup(),
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
     *
     * @dataProvider validUserDataProvider
     */
    public function testCreateUserProducesExpectedOutputWithAllInfo() {
        $timezone = Timezone::fromProvidedName('EST');

        $user = (new User())
            ->setId('1')
            ->setEmail('phpunit@test.com')
            ->setEmployeeId('1')
            ->setGivenName('PHP')
            ->setSurname('Unit')
            ->setPassword('password')
            ->setTimezone($timezone)
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

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createUser(
            $accountApi,
            $userApi,
            $user
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
        foreach ($xml->Parameters->User->children() as $userTag) {
            $userInfo[] = $userTag->getName();
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
            $user->getEmail(),
            $xml->Parameters->User->Info->Email
        );
        self::assertContains('EmployeeID', $infoTag);
        self::assertEquals(
            $user->getEmployeeId(),
            $xml->Parameters->User->Info->EmployeeID
        );
        self::assertContains('GivenName', $infoTag);
        self::assertEquals(
            $user->getGivenName(),
            $xml->Parameters->User->Info->GivenName
        );
        self::assertContains('Surname', $infoTag);
        self::assertEquals(
            $user->getSurname(),
            $xml->Parameters->User->Info->Surname
        );
        self::assertContains('Password', $infoTag);
        self::assertEquals(
            $user->getPassword(),
            $xml->Parameters->User->Info->Password
        );
        self::assertContains('Timezone', $infoTag);
        self::assertEquals(
            $user->getTimezone()->getProvidedName(),
            (string) $xml->Parameters->User->Info->Timezone
        );
        self::assertContains('LearnerNotifications', $infoTag);
        self::assertEquals(
            $user->getLearnerNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->LearnerNotifications
        );
        self::assertContains('SupervisorNotifications', $infoTag);
        self::assertEquals(
            $user->getSupervisorNotifications() ? '1' : '0',
            $xml->Parameters->User->Info->SupervisorNotifications
        );
        self::assertContains('SendEmailTo', $infoTag);
        self::assertEquals(
            $user->getSendEmailTo(),
            $xml->Parameters->User->Info->SendEmailTo
        );
        self::assertContains('AlternateEmail', $infoTag);
        self::assertEquals(
            $user->getAlternateEmail(),
            $xml->Parameters->User->Info->AlternateEmail
        );
        self::assertContains('AuthenticationType', $infoTag);
        self::assertEquals(
            $user->getAuthenticationType(),
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
            . $user->getSupervisors()[0]
            . '</Supervisor>';
        $supervisor2 =
            '<Supervisor>'
            . $user->getSupervisors()[1]
            . '</Supervisor></Supervisors>';
        self::assertStringContainsString($supervisor1, $supervisors);
        self::assertStringContainsString($supervisor2, $supervisors);
        self::assertContains('Organization', $profileTag);
        self::assertEquals(
            $user->getOrganization(),
            $xml->Parameters->User->Profile->Organization
        );
        self::assertContains('Teams', $profileTag);
        $teams = $xml->Parameters->User->Profile->Teams->asXML();
        $team1 = '<Teams><Team>' . $user->getTeams()[0] . '</Team>';
        $team2 = '<Team>' . $user->getTeams()[1] . '</Team></Teams>';
        self::assertStringContainsString($team1, $teams);
        self::assertStringContainsString($team2, $teams);
        self::assertContains('Language', $profileTag);
        self::assertEquals(
            $user->getLanguage(),
            $xml->Parameters->User->Profile->Language
        );
        self::assertContains('Status', $profileTag);
        self::assertEquals(
            $user->getStatus(),
            $xml->Parameters->User->Profile->Status
        );
        self::assertContains('Title', $profileTag);
        self::assertEquals(
            $user->getTitle(),
            $xml->Parameters->User->Profile->Title
        );
        self::assertContains('Division', $profileTag);
        self::assertEquals(
            $user->getDivision(),
            $xml->Parameters->User->Profile->Division
        );
        self::assertContains('AllowFeedback', $profileTag);
        self::assertEquals(
            $user->getAllowFeedback() ? '1' : '0',
            $xml->Parameters->User->Profile->AllowFeedback
        );
        self::assertContains('PhonePrimary', $profileTag);
        self::assertEquals(
            $user->getPhonePrimary(),
            $xml->Parameters->User->Profile->PhonePrimary
        );
        self::assertContains('PhoneAlternate', $profileTag);
        self::assertEquals(
            $user->getPhoneAlternate(),
            $xml->Parameters->User->Profile->PhoneAlternate
        );
        self::assertContains('PhoneMobile', $profileTag);
        self::assertEquals(
            $user->getPhoneMobile(),
            $xml->Parameters->User->Profile->PhoneMobile
        );
        self::assertContains('Fax', $profileTag);
        self::assertEquals(
            $user->getFax(),
            $xml->Parameters->User->Profile->Fax
        );
        self::assertContains('Website', $profileTag);
        self::assertEquals(
            $user->getWebsite(),
            $xml->Parameters->User->Profile->Website
        );
        self::assertContains('Address1', $profileTag);
        self::assertEquals(
            $user->getAddress1(),
            $xml->Parameters->User->Profile->Address1
        );
        self::assertContains('Address2', $profileTag);
        self::assertEquals(
            $user->getAddress2(),
            $xml->Parameters->User->Profile->Address2
        );
        self::assertContains('City', $profileTag);
        self::assertEquals(
            $user->getCity(),
            $xml->Parameters->User->Profile->City
        );
        self::assertContains('Province', $profileTag);
        self::assertEquals(
            $user->getProvince(),
            $xml->Parameters->User->Profile->Province
        );
        self::assertContains('Country', $profileTag);
        self::assertEquals(
            $user->getCountry(),
            $xml->Parameters->User->Profile->Country
        );
        self::assertContains('PostalCode', $profileTag);
        self::assertEquals(
            $user->getPostalCode(),
            $xml->Parameters->User->Profile->PostalCode
        );
        self::assertContains('SendMailTo', $profileTag);
        self::assertEquals(
            $user->getSendMailTo(),
            $xml->Parameters->User->Profile->SendMailTo
        );
        self::assertContains('ReceiveNotifications', $profileTag);
        self::assertEquals(
            $user->getReceiveNotifications() ? '1' : '0',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $user->getHomeGroup(),
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
            $user->getHomeGroup(),
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

    public function validUserDataProvider(): array {
        $timezone = Timezone::fromProvidedName('EST');

        return [
            // Original Home group used for testing
            [(new User())
                ->setId('1')
                ->setEmail('phpunit@test.com')
                ->setEmployeeId('1')
                ->setGivenName('PHP')
                ->setSurname('Unit')
                ->setPassword('password')
                ->setTimezone($timezone)
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
                ->setHomeGroup('My Home Group')
            ],
            [
                (new User())
                ->setId('1')
                ->setEmail('phpunit@test.com')
                ->setEmployeeId('1')
                ->setGivenName('PHP')
                ->setSurname('Unit')
                ->setPassword('password')
                ->setTimezone($timezone)
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
                ->setHomeGroup('A, B & C Industries')
            ]
        ];
    }
}
