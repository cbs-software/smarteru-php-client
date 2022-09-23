<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\Group.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/03
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\LearningModule;
use CBS\SmarterU\DataTypes\SubscriptionVariant;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\DataTypes\User;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Exceptions\MissingValueException;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\Group.
 */
class GroupTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $name = 'My Group';
        $groupId = '12';
        $createdDate = new DateTime('2022/08/02');
        $modifiedDate = new DateTime();
        $description = 'This is a group created for testing.';
        $homeGroupMessage = 'Home Group';
        $email1 = 'phpunit@test.com';
        $email2 = 'test@phpunit.com';
        $notificationEmails = [$email1, $email2];
        $userHelpOverrideDefault = false;
        $userHelpEnabled = true;
        $helpEmail1 = 'phpunit2@test.com';
        $helpEmail2 = 'test2@phpunit.com';
        $userHelpEmail = [$helpEmail1, $helpEmail2];
        $userHelpText = 'Help Message';
        $tag1 = (new Tag())
            ->setTagId('1')
            ->setTagValues('Tag1 values');
        $tag2 = (new Tag())
            ->setTagId('2')
            ->setTagValues('Tag2 values');
        $tags = [$tag1, $tag2];
        $userLimitEnabled = true;
        $userLimitAmount = 50;
        $status = 'Active';
        $user1 = (new User())
            ->setEmployeeId('2')
            ->setHomeGroup($name);
        $user2 = (new User())
            ->setEmployeeId('3')
            ->setHomeGroup('Other Group');
        $users = [$user1, $user2];
        $module1 = (new LearningModule())
            ->setId('4')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);
        $learningModules = [$module1, $module2];
        $variant1 = (new SubscriptionVariant())
            ->setId('6')
            ->setRequiresCredits(true);
        $variant2 = (new SubscriptionVariant())
            ->setId('7')
            ->setRequiresCredits(false);
        $subscriptionVariants = [$variant1, $variant2];
        $dashboardSetId = '8';
        $userCount = 2;
        $learningModuleCount = 3;

        $group = (new Group())
            ->setName($name)
            ->setGroupId($groupId)
            ->setCreatedDate($createdDate)
            ->setModifiedDate($modifiedDate)
            ->setDescription($description)
            ->setHomeGroupMessage($homeGroupMessage)
            ->setNotificationEmails($notificationEmails)
            ->setUserHelpOverrideDefault($userHelpOverrideDefault)
            ->setUserHelpEnabled($userHelpEnabled)
            ->setUserHelpEmail($userHelpEmail)
            ->setUserHelpText($userHelpText)
            ->setTags($tags)
            ->setUserLimitEnabled($userLimitEnabled)
            ->setUserLimitAmount($userLimitAmount)
            ->setStatus($status)
            ->setUsers($users)
            ->setLearningModules($learningModules)
            ->setSubscriptionVariants($subscriptionVariants)
            ->setDashboardSetId($dashboardSetId)
            ->setUserCount($userCount)
            ->setLearningModuleCount($learningModuleCount);

        self::assertEquals($name, $group->getName());
        self::assertEquals($groupId, $group->getGroupId());
        self::assertEquals($createdDate, $group->getCreatedDate());
        self::assertEquals($modifiedDate, $group->getModifiedDate());
        self::assertEquals($description, $group->getDescription());
        self::assertEquals($homeGroupMessage, $group->getHomeGroupMessage());
        self::assertIsArray($group->getNotificationEmails());
        self::assertEquals(count($notificationEmails), count($group->getNotificationEmails()));
        foreach ($notificationEmails as $email) {
            self::assertContains($email, $group->getNotificationEmails());
        }
        self::assertEquals($userHelpOverrideDefault, $group->getUserHelpOverrideDefault());
        self::assertEquals($userHelpEnabled, $group->getUserHelpEnabled());
        self::assertIsArray($group->getUserHelpEmail());
        self::assertEquals(count($userHelpEmail), count($group->getUserHelpEmail()));
        foreach ($userHelpEmail as $email) {
            self::assertContains($email, $group->getUserHelpEmail());
        }
        self::assertEquals($userHelpText, $group->getUserHelpText());
        self::assertIsArray($group->getTags());
        self::assertEquals(count($tags), count($group->getTags()));
        foreach ($tags as $tag) {
            self::assertContains($tag, $group->getTags());
        }
        self::assertEquals($userLimitEnabled, $group->getUserLimitEnabled());
        self::assertEquals($userLimitAmount, $group->getUserLimitAmount());
        self::assertEquals($status, $group->getStatus());
        self::assertIsArray($group->getUsers());
        self::assertEquals(count($users), count($group->getUsers()));
        foreach ($users as $user) {
            self::assertContains($user, $group->getUsers());
        }
        self::assertIsArray($group->getLearningModules());
        self::assertEquals(count($learningModules), count($group->getLearningModules()));
        foreach ($learningModules as $module) {
            self::assertContains($module, $group->getLearningModules());
        }
        self::assertIsArray($group->getSubscriptionVariants());
        self::assertEquals(count($subscriptionVariants), count($group->getSubscriptionVariants()));
        foreach ($subscriptionVariants as $variant) {
            self::assertContains($variant, $group->getSubscriptionVariants());
        }
        self::assertEquals($dashboardSetId, $group->getDashboardSetId());
        self::assertEquals($userCount, $group->getUserCount());
        self::assertEquals($learningModuleCount, $group->getLearningModuleCount());
    }

    /**
     * Tests that an exception is thrown if the tags array contains an element
     * that is not an instance of CBS\SmarterU\DataTypes\Tag.
     */
    public function testExceptionIsThrownWhenTagNotInstanceOfTag() {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            'Parameter to CBS\SmarterU\DataTypes\Group::setTags must be a list of Tag instances'
        );
        $group = (new Group())
            ->setTags(['This is not a tag']);
    }

    /**
     * Tests that an exception is thrown if the status is invalid.
     */
    public function testExceptionIsThrownWhenStatusIsInvalid() {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid is not one of ');
        $group = (new Group())
            ->setStatus('Invalid');
    }

    /**
     * Tests that an exception is thrown if the email address to which to send
     * notifications is not a string.
     */
    public function testExceptionIsThrownWhenEmailAddressIsNotString() {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            'Parameter to CBS\SmarterU\DataTypes\Group::setNotificationEmails must be a list of email addresses as strings'
        );
        $group = (new Group())
            ->setNotificationEmails([1, 2, 3]);
    }
}
