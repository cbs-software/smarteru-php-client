<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\ChangePermissionsXMLTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/09/09
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::changePermissions().
 */
class ChangePermissionsXMLTest extends TestCase {
    /**
     * Test that XMLGenerator::changePermissions() throws an exception when the
     * User whose permissions are being changed does not have an email address
     * or an employee ID.
     */
    public function testChangePermissionsThrowsExceptionWhenNoUserIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = new User();
        $group = new Group();
        $xmlGenerator = new XMLGenerator();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'A User\'s permissions cannot be updated without either an email address or an employee ID.'
        );
        $xmlGenerator->changePermissions(
            $accountApi,
            $userApi,
            $user,
            $group,
            ['MANAGE_USERS'],
            'Add'
        );
    }

    /**
     * Test that XMLGenerator::changePermissions() throws an exception when the
     * Group in which the User's permissions are being changed does not have a
     * name or an ID.
     */
    public function testChangePermissionsThrowsExceptionWhenNoGroupIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = (new User())
            ->setEmail('test@test.com');
        $group = new Group();
        $xmlGenerator = new XMLGenerator();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot assign permissions in a Group that has no name or ID.'
        );
        $xmlGenerator->changePermissions(
            $accountApi,
            $userApi,
            $user,
            $group,
            ['MANAGE_USERS'],
            'Add'
        );
    }
    
    /**
     * Test that XMLGenerator::changePermissions() produces the correct output
     * when all required values are present and only one permission is being
     * changed.
     */
    public function testChangePermissionsProducesExpectedOutputSinglePermission() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = (new User())
            ->setEmail('test@test.com');
        $group = (new Group())
            ->setName('My Group');
        $permission = 'MANAGE_USERS';
        $xmlGenerator = new XMLGenerator();
        $xml = $xmlGenerator->changePermissions(
            $accountApi,
            $userApi,
            $user,
            $group,
            [$permission],
            'Deny'
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
        self::assertContains('User', $parameters);
        $userTag = [];
        foreach ($xml->Parameters->User->children() as $tag) {
            $userTag[] = $tag->getName();
        }
        self::assertCount(6, $userTag);
        self::assertContains('Identifier', $userTag);
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
        self::assertContains('Info', $userTag);
        $infoTag = [];
        foreach ($xml->Parameters->User->Info->children() as $tag) {
            $infoTag[] = $tag->getName();
        }
        self::assertCount(0, $infoTag);
        self::assertContains('Profile', $userTag);
        self::assertCount(0, $xml->Parameters->User->Profile->children());
        self::assertContains('Groups', $userTag);
        $groupsTag = [];
        foreach ($xml->Parameters->User->Groups->children() as $tag) {
            $groupsTag[] = $tag->getName();
        }
        self::assertCount(1, $groupsTag);
        self::assertContains('Group', $groupsTag);
        $groupTag = [];
        foreach ($xml->Parameters->User->Groups->Group->children() as $tag) {
            $groupTag[] = $tag->getName();
        }
        self::assertCount(3, $groupTag);
        self::assertContains('GroupName', $groupTag);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->User->Groups->Group->GroupName
        );
        self::assertContains('GroupAction', $groupTag);
        self::assertEquals(
            'Add',
            $xml->Parameters->User->Groups->Group->GroupAction
        );
        self::assertContains('GroupPermissions', $groupTag);
        $groupPermissionsTag = [];
        foreach ($xml->Parameters->User->Groups->Group->GroupPermissions->children() as $tag) {
            $groupPermissionsTag[] = $tag->getName();
        }
        self::assertCount(1, $groupPermissionsTag);
        self::assertContains('Permission', $groupPermissionsTag);
        $permissionsTag = [];
        foreach ($xml->Parameters->User->Groups->Group->GroupPermissions->Permission->children() as $tag) {
            $permissionsTag[] = $tag->getName();
        }
        self::assertCount(2, $permissionsTag);
        self::assertContains('Action', $permissionsTag);
        self::assertEquals(
            'Deny',
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission->Action
        );
        self::assertContains('Code', $permissionsTag);
        self::assertEquals(
            $permission,
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission->Code
        );
        self::assertContains('Venues', $userTag);
        self::assertCount(0, $xml->Parameters->User->Venues->children());
        self::assertContains('Wages', $userTag);
        self::assertCount(0, $xml->Parameters->User->Wages->children());
    }

    /**
     * Test that XMLGenerator::changePermissions() produces the correct output
     * when all required values are present and multiple permissions are being
     * changed.
     */
    public function testChangePermissionsProducesExpectedOutputMultiplePermissions() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = (new User())
            ->setEmail('test@test.com')
            ->setLearnerNotifications(true)
            ->setSupervisorNotifications(true);
        $group = (new Group())
            ->setName('My Group');
        $permission1 = 'MANAGE_USERS';
        $permission2 = 'CREATE_COURSE';
        $permission3 = 'MANAGE_GROUP_COURSES';
        $xmlGenerator = new XMLGenerator();
        $xml = $xmlGenerator->changePermissions(
            $accountApi,
            $userApi,
            $user,
            $group,
            [$permission1, $permission2, $permission3],
            'Grant'
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
        self::assertContains('User', $parameters);
        $userTag = [];
        foreach ($xml->Parameters->User->children() as $tag) {
            $userTag[] = $tag->getName();
        }
        self::assertCount(6, $userTag);
        self::assertContains('Identifier', $userTag);
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
        self::assertContains('Info', $userTag);
        $infoTag = [];
        foreach ($xml->Parameters->User->Info->children() as $tag) {
            $infoTag[] = $tag->getName();
        }
        self::assertCount(0, $infoTag);
        self::assertContains('Profile', $userTag);
        self::assertCount(0, $xml->Parameters->User->Profile->children());
        self::assertContains('Groups', $userTag);
        $groupsTag = [];
        foreach ($xml->Parameters->User->Groups->children() as $tag) {
            $groupsTag[] = $tag->getName();
        }
        self::assertCount(1, $groupsTag);
        self::assertContains('Group', $groupsTag);
        $groupTag = [];
        foreach ($xml->Parameters->User->Groups->Group->children() as $tag) {
            $groupTag[] = $tag->getName();
        }
        self::assertCount(3, $groupTag);
        self::assertContains('GroupName', $groupTag);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->User->Groups->Group->GroupName
        );
        self::assertContains('GroupAction', $groupTag);
        self::assertEquals(
            'Add',
            $xml->Parameters->User->Groups->Group->GroupAction
        );
        self::assertContains('GroupPermissions', $groupTag);
        $groupPermissionsTag = [];
        foreach ($xml->Parameters->User->Groups->Group->GroupPermissions->children() as $tag) {
            $groupPermissionsTag[] = $tag->getName();
        }
        self::assertCount(3, $groupPermissionsTag);
        foreach ($groupPermissionsTag as $tag) {
            self::assertEquals($tag, 'Permission');
        }
        $permissionsTag = [];
        foreach ($xml->Parameters->User->Groups->Group->GroupPermissions->Permission[0]->children() as $tag) {
            $permissionsTag[] = $tag->getName();
        }
        self::assertCount(2, $permissionsTag);
        self::assertContains('Action', $permissionsTag);
        self::assertEquals(
            'Grant',
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission[0]->Action
        );
        self::assertContains('Code', $permissionsTag);
        self::assertEquals(
            $permission1,
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission[0]->Code
        );
        $permissionsTag = [];
        foreach ($xml->Parameters->User->Groups->Group->GroupPermissions->Permission[1]->children() as $tag) {
            $permissionsTag[] = $tag->getName();
        }
        self::assertCount(2, $permissionsTag);
        self::assertContains('Action', $permissionsTag);
        self::assertEquals(
            'Grant',
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission[1]->Action
        );
        self::assertContains('Code', $permissionsTag);
        self::assertEquals(
            $permission2,
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission[1]->Code
        );
        $permissionsTag = [];
        foreach ($xml->Parameters->User->Groups->Group->GroupPermissions->Permission[2]->children() as $tag) {
            $permissionsTag[] = $tag->getName();
        }
        self::assertCount(2, $permissionsTag);
        self::assertContains('Action', $permissionsTag);
        self::assertEquals(
            'Grant',
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission[2]->Action
        );
        self::assertContains('Code', $permissionsTag);
        self::assertEquals(
            $permission3,
            $xml->Parameters->User->Groups->Group->GroupPermissions->Permission[2]->Code
        );
        self::assertContains('Venues', $userTag);
        self::assertCount(0, $xml->Parameters->User->Venues->children());
        self::assertContains('Wages', $userTag);
        self::assertCount(0, $xml->Parameters->User->Wages->children());
    }
}
