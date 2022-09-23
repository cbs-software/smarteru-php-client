<?php

/**
 * Contains Tests\SmarterU\DataTypes\UserTest.php
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/22
 */

declare(strict_types=1);

namespace Tests\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\DataTypes\User;
 */
class UserTest extends TestCase {
    /**
     * Test agreement between getters and setters.
     */
    public function testAgreement() {
        $id = '17';
        $createdDate = new DateTime();
        $email = 'phpunit@test.com';
        $employeeId = '16';
        $givenName = 'test';
        $surname = 'user';
        $password = 'password';
        $timezone = 'EST';
        $learnerNotifications = true;
        $supervisorNotifications = false;
        $sendEmailTo = 'Self';
        $alternateEmail = 'test@phpunit.com';
        $authenticationType = 'SmarterU';
        // TODO implement supervisors. For iteration 1, we can assume it's empty.
        $organization = 'Organization';
        $teams = ['team1', 'team2'];
        // TODO implement custom fields. For iteration 1, we can assume it's empty.
        $language = 'English';
        $status = User::STATUS_ACTIVE;
        $title = 'title';
        $division = 'division';
        $allowFeedback = true;
        $phonePrimary = '555-555-1234';
        $phoneAlternate = '555-555-4321';
        $phoneMobile = '555-555-5555';
        $fax = '555-555-2345';
        $website = 'https://localhost';
        $address1 = '123 Main St.';
        $address2 = 'Apt. 1';
        $city = 'Lewisburg';
        $province = 'Pennsylvania';
        $country = 'United States';
        $postalCode = '17837';
        $sendMailTo = 'Personal';
        // TODO implement roles. For iteration 1, we can assume it's empty.
        $homeGroup = 'Group B';
        $group1 = (new Group())
            ->setName('Group A');
        $group2 = (new Group())
            ->setName('Group B');
        $groups = [$group1, $group2];
        // TODO implement venues. For iteration 1, we can assume it's empty.
        // TODO implement wages. For iteration 1, we can assume it's empty.
        $modifiedDate = new DateTime();

        $user = (new User())
            ->setId($id)
            ->setEmail($email)
            ->setEmployeeId($employeeId)
            ->setGivenName($givenName)
            ->setSurname($surname)
            ->setPassword($password)
            ->setTimezone($timezone)
            ->setLearnerNotifications($learnerNotifications)
            ->setSupervisorNotifications($supervisorNotifications)
            ->setSendEmailTo($sendEmailTo)
            ->setAlternateEmail($alternateEmail)
            ->setAuthenticationType($authenticationType)
            ->setOrganization($organization)
            ->setTeams($teams)
            ->setLanguage($language)
            ->setStatus($status)
            ->setTitle($title)
            ->setDivision($division)
            ->setAllowFeedback($allowFeedback)
            ->setPhonePrimary($phonePrimary)
            ->setPhoneAlternate($phoneAlternate)
            ->setPhoneMobile($phoneMobile)
            ->setFax($fax)
            ->setWebsite($website)
            ->setAddress1($address1)
            ->setAddress2($address2)
            ->setCity($city)
            ->setProvince($province)
            ->setCountry($country)
            ->setPostalCode($postalCode)
            ->setSendMailTo($sendMailTo)
            ->setHomeGroup($homeGroup)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setGroups($groups);

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($employeeId, $user->getEmployeeId());
        self::assertEquals($givenName, $user->getGivenName());
        self::assertEquals($surname, $user->getSurname());
        self::assertEquals($password, $user->getPassword());
        self::assertEquals($timezone, $user->getTimezone());
        self::assertEquals($learnerNotifications, $user->getLearnerNotifications());
        self::assertEquals($supervisorNotifications, $user->getSupervisorNotifications());
        self::assertEquals($sendEmailTo, $user->getSendEmailTo());
        self::assertEquals($alternateEmail, $user->getAlternateEmail());
        self::assertEquals($authenticationType, $user->getAuthenticationType());
        self::assertEquals($organization, $user->getOrganization());
        self::assertCount(2, $user->getTeams());
        self::assertContains('team1', $user->getTeams());
        self::assertContains('team2', $user->getTeams());
        self::assertEquals($language, $user->getLanguage());
        self::assertEquals($status, $user->getStatus());
        self::assertEquals($title, $user->getTitle());
        self::assertEquals($division, $user->getDivision());
        self::assertEquals($allowFeedback, $user->getAllowFeedback());
        self::assertEquals($phonePrimary, $user->getPhonePrimary());
        self::assertEquals($phoneAlternate, $user->getPhoneAlternate());
        self::assertEquals($phoneMobile, $user->getPhoneMobile());
        self::assertEquals($fax, $user->getFax());
        self::assertEquals($website, $user->getWebsite());
        self::assertEquals($address1, $user->getAddress1());
        self::assertEquals($address2, $user->getAddress2());
        self::assertEquals($city, $user->getCity());
        self::assertEquals($province, $user->getProvince());
        self::assertEquals($country, $user->getCountry());
        self::assertEquals($postalCode, $user->getPostalCode());
        self::assertEquals($sendMailTo, $user->getSendMailTo());
        self::assertEquals($homeGroup, $user->getHomeGroup());
        self::assertEquals($createdDate, $user->getCreatedDate());
        self::assertEquals($modifiedDate, $user->getModifiedDate());
        self::assertCount(2, $user->getGroups());
        self::assertContains($group1, $user->getGroups());
        self::assertContains($group2, $user->getGroups());
    }
}
