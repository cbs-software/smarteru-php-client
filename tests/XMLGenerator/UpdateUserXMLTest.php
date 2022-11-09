<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\UpdateUserXMLTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/02
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::updateUser().
 */
class UpdateUserXMLTest extends TestCase {
    /**
     * Tests that the XML generation process for an UpdateUser request throws
     * an exception if the User being created does not have an email address
     * or an employee ID.
     */
    public function testUpdateUserThrowsExceptionWhenNoUserIdentifier() {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $user = new User();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'A User cannot be updated without either an email address or an employee ID.'
        );
        $xml = $xmlGenerator->updateUser(
            $accountApi,
            $userApi,
            $user
        );
    }

    /**
     * Tests that the XML generation process for an UpdateUser request produces
     * the expected output when all required information is present but all
     * optional attributes are left blank.
     */
    public function testUpdateUserProducesExpectedOutputWithoutRequiredInfo() {
        $user = (new User())
            ->setEmail('example@email.com')
            ->setOldEmail('old@email.com')
            ->setGivenName('Test')
            ->setSurname('User')
            ->setSendEmailTo('Self')
            ->setAuthenticationType('SmarterU')
            ->setHomeGroup('HomeGroup');

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->updateUser(
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
        self::assertEquals('updateUser', $xml->Method);
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
        self::assertCount(6, $userInfo);
        self::assertContains('Identifier', $userInfo);
        self::assertContains('Info', $userInfo);
        self::assertContains('Profile', $userInfo);
        self::assertContains('Groups', $userInfo);
        self::assertContains('Venues', $userInfo);
        self::assertContains('Wages', $userInfo);

        $identifierTag = [];
        foreach ($xml->Parameters->User->Identifier->children() as $identifier) {
            $identifierTag[] = $identifier->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Email', $identifierTag);
        self::assertEquals(
            'old@email.com',
            $xml->Parameters->User->Identifier->Email
        );

        // Ensure that the <Info> tag has the correct children.
        $infoTag = [];
        foreach ($xml->Parameters->User->Info->children() as $info) {
            $infoTag[] = $info->getName();
        }
        self::assertCount(5, $infoTag);
        self::assertContains('Email', $infoTag);
        self::assertEquals(
            $user->getEmail(),
            $xml->Parameters->User->Info->Email
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
            $user->getReceiveNotifications() ? 'True' : 'False',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $user->getHomeGroup(),
            $xml->Parameters->User->Profile->HomeGroup
        );
        self::assertCount(
            0,
            $xml->Parameters->User->Groups->children()
        );
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
     * Tests that the XML generation process for an updateUser request produces
     * the expected output when all required and optional information is present.
     */
    public function testUpdateUserProducesExpectedOutputWithAllInfo() {
        $user = (new User())
            ->setId('1')
            ->setEmail('phpunit@test.com')
            ->setEmployeeId('1')
            ->setGivenName('PHP')
            ->setSurname('Unit')
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

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->updateUser(
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
        self::assertEquals('updateUser', $xml->Method);
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
        self::assertCount(6, $userInfo);
        self::assertContains('Identifier', $userInfo);
        self::assertContains('Info', $userInfo);
        self::assertContains('Profile', $userInfo);
        self::assertContains('Groups', $userInfo);
        self::assertContains('Venues', $userInfo);
        self::assertContains('Wages', $userInfo);

        $identifierTag = [];
        foreach ($xml->Parameters->User->Identifier->children() as $identifier) {
            $identifierTag[] = $identifier->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Email', $identifierTag);
        self::assertEquals(
            $user->getEmail(),
            $xml->Parameters->User->Identifier->Email
        );

        // Ensure that the <Info> tag has the correct children.
        $infoTag = [];
        foreach ($xml->Parameters->User->Info->children() as $info) {
            $infoTag[] = $info->getName();
        }
        self::assertCount(8, $infoTag);
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
        self::assertContains('TimeZone', $infoTag);
        self::assertEquals(
            $user->getTimezone(),
            $xml->Parameters->User->Info->TimeZone
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
            $user->getAllowFeedback() ? 'True' : 'False',
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
            $user->getReceiveNotifications() ? 'True' : 'False',
            $xml->Parameters->User->Profile->ReceiveNotifications
        );
        self::assertContains('HomeGroup', $profileTag);
        self::assertEquals(
            $user->getHomeGroup(),
            $xml->Parameters->User->Profile->HomeGroup
        );

        self::assertCount(
            0,
            $xml->Parameters->User->Groups->children()
        );
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
