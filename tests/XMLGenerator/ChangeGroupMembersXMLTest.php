<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\ChangeGroupMembersXMLTest.
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
 * Tests CBS\SmarterU\XMLGenerator::changeGroupMembers().
 */
class ChangeGroupMembersXMLTest extends TestCase {
    /**
     * Test that XMLGenerator::changeGroupMembers() throws an exception when
     * the Group the Users are being assigned to does not have a name or an ID.
     */
    public function testChangeGroupMembersThrowsExceptionWhenNoGroupIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = new User();
        $group = new Group();
        $xmlGenerator = new XMLGenerator();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Cannot add or remove users from a Group without a group name or ID.'
        );
        $xmlGenerator->changeGroupMembers(
            $accountApi,
            $userApi,
            [$user],
            $group,
            'Add'
        );
    }

    /**
     * Test that XMLGenerator::changeGroupMembers() throws an exception when
     * the array of Users being added to or removed from the Group contains
     * a value that is not an instance of User.
     */
    public function testChangeGroupMembersThrowsExceptionWhenUserNotInstanceOfUser() {
        $accountApi = 'account';
        $userApi = 'user';
        $users = [1, 2, 3];
        $group = (new Group())
            ->setName('My Group');
        $xmlGenerator = new XMLGenerator();
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            '"$users" must be an array of CBS\SmarterU\DataTypes\User instances'
        );
        $xmlGenerator->changeGroupMembers(
            $accountApi,
            $userApi,
            $users,
            $group,
            'Add'
        );
    }

    /**
     * Test that XMLGenerator::changeGroupMembers() throws an exception when
     * one of the Users passed in does not have an email address or an
     * employee ID.
     */
    public function testChangeGroupMembersThrowsExceptionWhenNoUserIdentifier() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = new User();
        $group = (new Group())
            ->setName('My Group');
        $xmlGenerator = new XMLGenerator();
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'All Users being added to or removed from a Group must have an email address or employee ID.'
        );
        $xmlGenerator->changeGroupMembers(
            $accountApi,
            $userApi,
            [$user],
            $group,
            'Add'
        );
    }

    /**
     * Test that XMLGenerator::changeGroupMembers() produces the expected
     * output when all required values are present and only one User is going
     * to be added to or removed from the Group.
     */
    public function testChangeGroupMembersProducesExpectedOutputSingleUser() {
        $accountApi = 'account';
        $userApi = 'user';
        $user = (new User())
            ->setEmail('test@test.com')
            ->setHomeGroup('Other Group');
        $group = (new Group())
            ->setName('My Group');
        $action = 'Remove';
        $xmlGenerator = new XMLGenerator();
        $xml = $xmlGenerator->changeGroupMembers(
            $accountApi,
            $userApi,
            [$user],
            $group,
            $action
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
        self::assertEquals('updateGroup', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);
        $groupTags = [];
        foreach ($xml->Parameters->Group->children() as $tag) {
            $groupTags[] = $tag->getName();
        }
        self::assertCount(4, $groupTags);
        self::assertContains('Identifier', $groupTags);
        $identifierTag = [];
        foreach ($xml->Parameters->Group->Identifier->children() as $identifier) {
            $identifierTag[] = $identifier->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Name', $identifierTag);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->Group->Identifier->Name
        );
        self::assertContains('Users', $groupTags);
        $usersTag = [];
        foreach ($xml->Parameters->Group->Users->children() as $tag) {
            $usersTag[] = $tag->getName();
        }
        self::assertCount(1, $usersTag);
        self::assertContains('User', $usersTag);
        $userTags = [];
        foreach ($xml->Parameters->Group->Users->User->children() as $tag) {
            $userTags[] = $tag->getName();
        }
        self::assertCount(4, $userTags);
        self::assertContains('Email', $userTags);
        self::assertEquals(
            $user->getEmail(),
            $xml->Parameters->Group->Users->User->Email
        );
        self::assertContains('UserAction', $userTags);
        self::assertEquals(
            $action,
            $xml->Parameters->Group->Users->User->UserAction
        );
        self::assertContains('HomeGroup', $userTags);
        self::assertEquals(
            $group->getName() === $user->getHomeGroup() ? '1' : '0',
            $xml->Parameters->Group->Users->User->HomeGroup
        );
        self::assertContains('Permissions', $userTags);
        self::assertCount(
            0,
            $xml->Parameters->Group->Users->User->Permissions->children()
        );
        self::assertContains('LearningModules', $groupTags);
        self::assertCount(
            0,
            $xml->Parameters->Group->LearningModules->children()
        );
        self::assertContains('SubscriptionVariants', $groupTags);
        self::assertCount(
            0,
            $xml->Parameters->Group->SubscriptionVariants->children()
        );
    }

    /**
     * Test that XMLGenerator::changeGroupMembers() produces the expected
     * output when all required values are present and multiple Users are going
     * to be added to or removed from the Group.
     */
    public function testChangeGroupMembersProducesExpectedOutputMultipleUsers() {
        $accountApi = 'account';
        $userApi = 'user';
        $user1 = (new User())
            ->setEmail('test@test.com')
            ->setHomeGroup('Other Group');
        $user2 = (new User())
            ->setEmail('test2@test.com')
            ->setHomeGroup('My Group');
        $user3 = (new User())
            ->setEmployeeId('4')
            ->setHomeGroup('Other Group');
        $group = (new Group())
            ->setName('My Group');
        $action = 'Add';
        $xmlGenerator = new XMLGenerator();
        $xml = $xmlGenerator->changeGroupMembers(
            $accountApi,
            $userApi,
            [$user1, $user2, $user3],
            $group,
            $action
        );
        self::assertIsString($xml);

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
        self::assertEquals('updateGroup', $xml->Method);
        self::assertContains('Parameters', $elements);

        // Ensure that the <Parameters> tag has the correct children.
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);
        $groupTags = [];
        foreach ($xml->Parameters->Group->children() as $tag) {
            $groupTags[] = $tag->getName();
        }
        self::assertCount(4, $groupTags);
        self::assertContains('Identifier', $groupTags);
        $identifierTag = [];
        foreach ($xml->Parameters->Group->Identifier->children() as $identifier) {
            $identifierTag[] = $identifier->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Name', $identifierTag);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->Group->Identifier->Name
        );
        self::assertContains('Users', $groupTags);
        self::assertCount(3, $xml->Parameters->Group->Users->children());
        $users = [];
        foreach ($xml->Parameters->Group->Users->children() as $tag) {
            $users[] = $tag;
        }
        foreach ($users as $user) {
            $tags = [];
            foreach ($user->children() as $tag) {
                $tags[] = $tag->getName();
            }
            self::assertCount(4, $tags);

            // Every <User> tag must contain either an email address or an
            // employee ID to identify the User being added to the Group.
            // Exactly one of these values will be present, and this assertion
            // only requires one of them.
            self::assertThat(implode(',', $tags),
                self::logicalOr(
                    self::stringContains('Email'),
                    self::stringContains('EmployeeID')
                )
            );
            self::assertContains('UserAction', $tags);
            self::assertEquals($action, $user->UserAction);
            self::assertContains('HomeGroup', $tags);
            self::assertContains('Permissions', $tags);
            self::assertCount(0, $user->Permissions->children());
        }
        self::assertCount(3, $users);
        self::assertEquals($users[0]->Email, $user1->getEmail());
        self::assertEquals(
            $users[0]->HomeGroup,
            $group->getName() === $user1->getHomeGroup() ? '1' : '0'
        );
        self::assertEquals($users[1]->Email, $user2->getEmail());
        self::assertEquals(
            $users[1]->HomeGroup,
            $group->getName() === $user2->getHomeGroup() ? '1' : '0'
        );
        self::assertEquals($users[2]->EmployeeID, $user3->getEmployeeId());
        self::assertEquals(
            $users[2]->HomeGroup,
            $group->getName() === $user3->getHomeGroup() ? '1' : '0'
        );
        self::assertContains('LearningModules', $groupTags);
        self::assertCount(
            0,
            $xml->Parameters->Group->LearningModules->children()
        );
        self::assertContains('SubscriptionVariants', $groupTags);
        self::assertCount(
            0,
            $xml->Parameters->Group->SubscriptionVariants->children()
        );
    }
}
