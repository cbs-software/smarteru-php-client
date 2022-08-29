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
use CBS\SmarterU\DataTypes\GroupPermissions;
use CBS\SmarterU\DataTypes\LearningModule;
use CBS\SmarterU\DataTypes\Permission;
use CBS\SmarterU\DataTypes\SubscriptionVariant;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\InvalidArgumentException;
use CBS\SmarterU\Exceptions\MissingValueException;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\Group.
 */
class GroupTest extends TestCase {
    /**
     * A Group fixture for testing.
     */
    protected Group $group;
    
    /**
     * Set up test fixtures.
     */
    public function setUp(): void {
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
        $permission1 = (new Permission())
            ->setCode('MANAGE_USERS');
        $permission2 = (new Permission())
            ->setCode('MANAGE_COURSES');
        $user1 = (new GroupPermissions())
            ->setEmployeeId('2')
            ->setHomeGroup(true)
            ->setPermissions([$permission1, $permission2]);
        $user2 = (new GroupPermissions())
            ->setEmployeeId('3')
            ->setHomeGroup(false)
            ->setPermissions([]);
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

        $this->group = (new Group())
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
            ->setDashboardSetId($dashboardSetId);
    }

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
        $permission1 = (new Permission())
            ->setCode('MANAGE_USERS');
        $user1 = (new GroupPermissions())
            ->setEmployeeId('2')
            ->setHomeGroup(true)
            ->setPermissions([$permission1]);
        $user2 = (new GroupPermissions())
            ->setEmployeeId('3')
            ->setHomeGroup(false)
            ->setPermissions([]);
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
            ->setDashboardSetId($dashboardSetId);

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

    /**
     * Tests that an exception is thrown if the group is translated to XML but
     * one of the tags is missing both its name and its ID.
     */
    public function testExceptionIsThrownWhenTagIsIncomplete() {
        $tag = (new Tag())
            ->setTagValues('Tag, Values');

        $group = $this->group->setTags([$tag]);
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage('Every tag must have either a name or an ID');
        $group->toXML('account', 'user', 'method');
    }

    /**
     * Tests that an exception is thrown if the group is translated to XML but
     * one of its users is missing an identifier.
     */
    public function testExceptionIsThrownWhenUserIdentifierNotSet() {
        $user = (new GroupPermissions())
            ->setHomeGroup(true);

        $group = $this->group->setUsers([$user]);
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Each member of the group must be identified by either email or employee ID'
        );
        $group->toXML('account', 'user', 'method');
    }

    /**
     * Tests that XML generation returns the expected XML string when all
     * required and optional information is present for a createGroup query.
     */
    public function testXMLGeneratedIsAsExpected() {
        $accountApi = 'account';
        $userApi = 'user';
        $method = 'createGroup';
        $xml = $this->group->toXML($accountApi, $userApi, $method);
        self::assertIsString($xml);

        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        $tags = [];
        foreach ($xml->children() as $tag) {
            $tags[] = $tag->getName();
        }
        self::assertCount(4, $tags);
        self::assertContains('AccountAPI', $tags);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $tags);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $tags);
        self::assertEquals($method, $xml->Method);
        self::assertContains('Parameters', $tags);
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
        self::assertCount(16, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals(
            $this->group->getName(),
            $xml->Parameters->Group->Name
        );
        self::assertContains('GroupID', $groupTags);
        self::assertEquals(
            $this->group->getGroupId(),
            $xml->Parameters->Group->GroupID
        );
        self::assertContains('Status', $groupTags);
        self::assertEquals(
            $this->group->getStatus(),
            $xml->Parameters->Group->Status
        );
        self::assertContains('Description', $groupTags);
        self::assertEquals(
            $this->group->getDescription(),
            $xml->Parameters->Group->Description
        );
        self::assertContains('HomeGroupMessage', $groupTags);
        self::assertEquals(
            $this->group->getHomeGroupMessage(),
            $xml->Parameters->Group->HomeGroupMessage
        );
        self::assertContains('NotificationEmails', $groupTags);
        $emails = [];
        foreach ((array) $xml->Parameters->Group->NotificationEmails->NotificationEmail as $email) {
            $emails[] = $email;
        }
        self::assertEquals(
            count($emails),
            count($this->group->getNotificationEmails())
        );
        foreach ($emails as $email) {
            self::assertContains(
                $email,
                $this->group->getNotificationEmails()
            );
        }
        self::assertContains('UserHelpOverrideDefault', $groupTags);
        self::assertEquals(
            $this->group->getUserHelpOverrideDefault() ? '1' : '0',
            (string) $xml->Parameters->Group->UserHelpOverrideDefault
        );
        self::assertContains('UserHelpEnabled', $groupTags);
        self::assertEquals(
            $this->group->getUserHelpEnabled() ? '1' : '0',
            (string) $xml->Parameters->Group->UserHelpEnabled
        );
        self::assertContains('UserHelpEmail', $groupTags);
        self::assertEquals(
            implode(',', $this->group->getUserHelpEmail()),
            $xml->Parameters->Group->UserHelpEmail
        );
        self::assertContains('UserHelpText', $groupTags);
        self::assertEquals(
            $this->group->getUserHelpText(),
            $xml->Parameters->Group->UserHelpText
        );
        self::assertContains('Tags2', $groupTags);
        $tags = [];
        foreach ($xml->Parameters->Group->Tags2->children() as $tag) {
            $tags[] = (array) $tag;
        }
        self::assertCount(2, $tags);
        self::assertIsArray($tags[0]);
        self::assertCount(2, $tags[0]);
        self::assertArrayHasKey('TagID', $tags[0]);
        self::assertEquals(
            $tags[0]['TagID'],
            $this->group->getTags()[0]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[0]);
        self::assertEquals(
            $tags[0]['TagValues'],
            $this->group->getTags()[0]->getTagValues()
        );
        self::assertIsArray($tags[1]);
        self::assertCount(2, $tags[1]);
        self::assertArrayHasKey('TagID', $tags[1]);
        self::assertEquals(
            $tags[1]['TagID'],
            $this->group->getTags()[1]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[1]);
        self::assertEquals(
            $tags[1]['TagValues'],
            $this->group->getTags()[1]->getTagValues()
        );
        self::assertContains('UserLimit', $groupTags);
        $limitTags = [];
        foreach ($xml->Parameters->Group->UserLimit->children() as $limitTag) {
            $limitTags[] = $limitTag->getName();
        }
        self::assertCount(2, $limitTags);
        self::assertContains('Enabled', $limitTags);
        self::assertEquals(
            $this->group->getUserLimitEnabled() ? '1' : '0',
            (string) $xml->Parameters->Group->UserLimit->Enabled
        );
        self::assertContains('Amount', $limitTags);
        self::assertEquals(
            $this->group->getUserLimitAmount(),
            (int) $xml->Parameters->Group->UserLimit->Amount
        );
        $users = [];
        foreach ($xml->Parameters->Group->Users->children() as $user) {
            $users[] = (array) $user;
        }
        self::assertCount(2, $users);
        foreach ($users as $user) {
            self::assertIsArray($user);
            self::assertCount(3, $user);
            self::assertArrayHasKey('EmployeeID', $user);
            self::assertArrayHasKey('HomeGroup', $user);
            self::assertArrayHasKey('Permissions', $user);
        }
        self::assertEquals(
            $users[0]['EmployeeID'],
            $this->group->getUsers()[0]->getEmployeeId()
        );
        self::assertEquals(
            $users[0]['HomeGroup'],
            $this->group->getUsers()[0]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[0]['Permissions'];
        $codes = $codeSegment['Code'];
        self::assertEquals(
            count($codes),
            count($this->group->getUsers()[0]->getPermissions())
        );
        foreach ($this->group->getUsers()[0]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertEquals(
            $users[1]['EmployeeID'],
            $this->group->getUsers()[1]->getEmployeeId()
        );
        self::assertEquals(
            $users[1]['HomeGroup'],
            $this->group->getUsers()[1]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[1]['Permissions'];
        self::assertEquals(
            count($codeSegment),
            count($this->group->getUsers()[1]->getPermissions())
        );
        foreach ($this->group->getUsers()[1]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertContains('LearningModules', $groupTags);
        $modules = [];
        foreach ($xml->Parameters->Group->LearningModules->LearningModule as $module) {
            $modules[] = (array) $module;
        }

        self::assertEquals(
            count($modules),
            count($this->group->getLearningModules())
        );
        foreach ($modules as $module) {
            self::assertIsArray($module);
            self::assertCount(3, $module);
            self::assertArrayHasKey('ID', $module);
            self::assertArrayHasKey('AllowSelfEnroll', $module);
            self::assertArrayHasKey('AutoEnroll', $module);
        }
        self::assertEquals(
            $modules[0]['ID'],
            $this->group->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $this->group->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $this->group->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $this->group->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $this->group->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $this->group->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );

        self::assertContains('SubscriptionVariants', $groupTags);
        $variants = [];
        foreach ($xml->Parameters->Group->SubscriptionVariants->SubscriptionVariant as $variant) {
            $variants[] = (array) $variant;
        }

        self::assertEquals(count($variants), count($this->group->getSubscriptionVariants()));
        foreach ($variants as $variant) {
            self::assertIsArray($variant);
            self::assertCount(2, $variant);
            self::assertArrayHasKey('ID', $variant);
            self::assertArrayHasKey('RequiresCredits', $variant);
        }
        self::assertEquals(
            $variants[0]['ID'],
            $this->group->getSubscriptionVariants()[0]->getId()
        );
        self::assertEquals(
            $variants[0]['RequiresCredits'],
            $this->group->getSubscriptionVariants()[0]->getRequiresCredits() ? '1' : '0'
        );
        self::assertEquals(
            $variants[1]['ID'],
            $this->group->getSubscriptionVariants()[1]->getId()
        );
        self::assertEquals(
            $variants[1]['RequiresCredits'],
            $this->group->getSubscriptionVariants()[1]->getRequiresCredits() ? '1' : '0'
        );
        self::assertContains('DashboardSetID', $groupTags);
        self::assertEquals(
            $xml->Parameters->Group->DashboardSetID,
            $this->group->getDashboardSetId()
        );
    }

    /**
     * Tests that XML generation returns the expected XML string when all
     * required and optional information is present for an updateGroup query.
     * This includes an extra tag attached to each User, each LearningModule,
     * and each SubscriptionVariant that is not necessary when creating a Group.
     */
    public function testXMLGeneratedIsAsExpectedUpdateGroup() {
        $accountApi = 'account';
        $userApi = 'user';
        $method = 'updateGroup';

        $add = 'Add';
        $remove = 'Remove';
        $user1 = $this->group->getUsers()[0];
        $user1->setAction($add);
        $user2 = $this->group->getUsers()[1];
        $user2->setAction($remove);
        $users = [$user1, $user2];
        $module1 = $this->group->getLearningModules()[0];
        $module1->setAction($add);
        $module2 = $this->group->getLearningModules()[1];
        $module2->setAction($remove);
        $modules = [$module1, $module2];
        $variant1 = $this->group->getSubscriptionVariants()[0];
        $variant1->setAction($add);
        $variant2 = $this->group->getSubscriptionVariants()[1];
        $variant2->setAction($remove);
        $variants = [$variant1, $variant2];

        $group = $this->group;
        $group->setUsers($users);
        $group->setLearningModules($modules);
        $group->setSubscriptionVariants($variants);

        $xml = $group->toXML($accountApi, $userApi, $method);

        $xml = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xml->getName());
        $tags = [];
        foreach ($xml->children() as $tag) {
            $tags[] = $tag->getName();
        }
        self::assertCount(4, $tags);
        self::assertContains('AccountAPI', $tags);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $tags);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $tags);
        self::assertEquals($method, $xml->Method);
        self::assertContains('Parameters', $tags);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(2, $parameters);
        self::assertContains('Identifier', $parameters);
        $identifierTag = [];
        foreach ($xml->Parameters->Identifier->children() as $tag) {
            $identifierTag[] = $tag->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Name', $identifierTag);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->Identifier->Name
        );
        self::assertContains('Group', $parameters);
        $groupTags = [];
        foreach ($xml->Parameters->Group->children() as $tag) {
            $groupTags[] = $tag->getName();
        }
        self::assertCount(14, $groupTags);
        self::assertContains('Status', $groupTags);
        self::assertEquals(
            $group->getStatus(),
            $xml->Parameters->Group->Status
        );
        self::assertContains('Description', $groupTags);
        self::assertEquals(
            $group->getDescription(),
            $xml->Parameters->Group->Description
        );
        self::assertContains('HomeGroupMessage', $groupTags);
        self::assertEquals(
            $group->getHomeGroupMessage(),
            $xml->Parameters->Group->HomeGroupMessage
        );
        self::assertContains('NotificationEmails', $groupTags);
        $emails = [];
        foreach ((array) $xml->Parameters->Group->NotificationEmails->NotificationEmail as $email) {
            $emails[] = $email;
        }

        self::assertEquals(
            count($emails),
            count($group->getNotificationEmails())
        );
        foreach ($emails as $email) {
            self::assertContains($email, $group->getNotificationEmails());
        }
        self::assertContains('UserHelpOverrideDefault', $groupTags);
        self::assertEquals(
            $group->getUserHelpOverrideDefault() ? '1' : '0',
            (string) $xml->Parameters->Group->UserHelpOverrideDefault
        );
        self::assertContains('UserHelpEnabled', $groupTags);
        self::assertEquals(
            $group->getUserHelpEnabled() ? '1' : '0',
            (string) $xml->Parameters->Group->UserHelpEnabled
        );
        self::assertContains('UserHelpEmail', $groupTags);
        self::assertEquals(
            implode(',', $group->getUserHelpEmail()),
            $xml->Parameters->Group->UserHelpEmail
        );
        self::assertContains('UserHelpText', $groupTags);
        self::assertEquals(
            $group->getUserHelpText(),
            $xml->Parameters->Group->UserHelpText
        );
        self::assertContains('Tags2', $groupTags);
        $tags = [];
        foreach ($xml->Parameters->Group->Tags2->children() as $tag) {
            $tags[] = (array) $tag;
        }
        self::assertCount(2, $tags);
        self::assertIsArray($tags[0]);
        self::assertCount(2, $tags[0]);
        self::assertArrayHasKey('TagID', $tags[0]);
        self::assertEquals(
            $tags[0]['TagID'],
            $group->getTags()[0]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[0]);
        self::assertEquals(
            $tags[0]['TagValues'],
            $group->getTags()[0]->getTagValues()
        );
        self::assertIsArray($tags[1]);
        self::assertCount(2, $tags[1]);
        self::assertArrayHasKey('TagID', $tags[1]);
        self::assertEquals(
            $tags[1]['TagID'],
            $group->getTags()[1]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[1]);
        self::assertEquals(
            $tags[1]['TagValues'],
            $group->getTags()[1]->getTagValues()
        );
        self::assertContains('UserLimit', $groupTags);
        $limitTags = [];
        foreach ($xml->Parameters->Group->UserLimit->children() as $limitTag) {
            $limitTags[] = $limitTag->getName();
        }
        self::assertCount(2, $limitTags);
        self::assertContains('Enabled', $limitTags);
        self::assertEquals(
            $group->getUserLimitEnabled() ? '1' : '0',
            (string) $xml->Parameters->Group->UserLimit->Enabled
        );
        self::assertContains('Amount', $limitTags);
        self::assertEquals(
            $group->getUserLimitAmount(),
            (int) $xml->Parameters->Group->UserLimit->Amount
        );
        $users = [];
        foreach ($xml->Parameters->Group->Users->children() as $user) {
            $users[] = (array) $user;
        }

        self::assertCount(2, $users);
        foreach ($users as $user) {
            self::assertIsArray($user);
            self::assertCount(4, $user);
            self::assertArrayHasKey('EmployeeID', $user);
            self::assertArrayHasKey('HomeGroup', $user);
            self::assertArrayHasKey('UserAction', $user);
            self::assertArrayHasKey('Permissions', $user);
        }
        self::assertEquals(
            $users[0]['EmployeeID'],
            $group->getUsers()[0]->getEmployeeId()
        );
        self::assertEquals(
            $users[0]['HomeGroup'],
            $group->getUsers()[0]->getHomeGroup() ? '1' : '0'
        );
        self::assertEquals(
            $users[0]['UserAction'],
            $group->getUsers()[0]->getAction()
        );
        $codeSegment = (array) $users[0]['Permissions'];
        $codes = $codeSegment['Code'];
        self::assertEquals(
            count($codes),
            count($group->getUsers()[0]->getPermissions())
        );
        foreach ($group->getUsers()[0]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertEquals(
            $users[1]['EmployeeID'],
            $group->getUsers()[1]->getEmployeeId()
        );
        self::assertEquals(
            $users[1]['HomeGroup'],
            $group->getUsers()[1]->getHomeGroup() ? '1' : '0'
        );
        self::assertEquals(
            $users[1]['UserAction'],
            $group->getUsers()[1]->getAction()
        );
        $codeSegment = (array) $users[1]['Permissions'];
        self::assertEquals(
            count($codeSegment),
            count($group->getUsers()[1]->getPermissions())
        );
        foreach ($group->getUsers()[1]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertContains('LearningModules', $groupTags);
        $modules = [];
        foreach ($xml->Parameters->Group->LearningModules->LearningModule as $module) {
            $modules[] = (array) $module;
        }

        self::assertEquals(
            count($modules),
            count($group->getLearningModules())
        );
        foreach ($modules as $module) {
            self::assertIsArray($module);
            self::assertCount(4, $module);
            self::assertArrayHasKey('ID', $module);
            self::assertArrayHasKey('LearningModuleAction', $module);
            self::assertArrayHasKey('AllowSelfEnroll', $module);
            self::assertArrayHasKey('AutoEnroll', $module);
        }
        self::assertEquals(
            $modules[0]['ID'],
            $group->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['LearningModuleAction'],
            $group->getLearningModules()[0]->getAction()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $group->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $group->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $group->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['LearningModuleAction'],
            $group->getLearningModules()[1]->getAction()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $group->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $group->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );

        self::assertContains('SubscriptionVariants', $groupTags);
        $variants = [];
        foreach ($xml->Parameters->Group->SubscriptionVariants->SubscriptionVariant as $variant) {
            $variants[] = (array) $variant;
        }

        self::assertEquals(
            count($variants),
            count($group->getSubscriptionVariants())
        );
        foreach ($variants as $variant) {
            self::assertIsArray($variant);
            self::assertCount(3, $variant);
            self::assertArrayHasKey('ID', $variant);
            self::assertArrayHasKey('SubscriptionVariantAction', $variant);
            self::assertArrayHasKey('RequiresCredits', $variant);
        }
        self::assertEquals(
            $variants[0]['ID'],
            $group->getSubscriptionVariants()[0]->getId()
        );
        self::assertEquals(
            $variants[0]['SubscriptionVariantAction'],
            $group->getSubscriptionVariants()[0]->getAction()
        );
        self::assertEquals(
            $variants[0]['RequiresCredits'],
            $group->getSubscriptionVariants()[0]->getRequiresCredits() ? '1' : '0'
        );
        self::assertEquals(
            $variants[1]['ID'],
            $group->getSubscriptionVariants()[1]->getId()
        );
        self::assertEquals(
            $variants[1]['SubscriptionVariantAction'],
            $group->getSubscriptionVariants()[1]->getAction()
        );
        self::assertEquals(
            $variants[1]['RequiresCredits'],
            $group->getSubscriptionVariants()[1]->getRequiresCredits() ? '1' : '0'
        );
        self::assertContains('DashboardSetID', $groupTags);
        self::assertEquals(
            $xml->Parameters->Group->DashboardSetID,
            $group->getDashboardSetId()
        );
    }

    /**
     * Test that XML generation produces the expected result for createGroup
     * when all required values but no optional values are present.
     */
    public function testXMLOutputWithoutOptionalValues() {
        $accountApi = 'account';
        $userApi = 'user';
        $method = 'createGroup';
        $name = 'Test Group';
        $status = 'Active';
        $description = 'A group for testing';
        $homeGroupMessage = 'Home Group';
        $email1 = 'test@test.com';
        $email2 = 'phpunit@test.com';
        $notificationEmails = [$email1, $email2];
        $users = $this->group->getUsers();
        $learningModules = $this->group->getLearningModules();

        $group = (new Group())
            ->setName($name)
            ->setStatus($status)
            ->setDescription($description)
            ->setHomeGroupMessage($homeGroupMessage)
            ->setNotificationEmails($notificationEmails)
            ->setUsers($users)
            ->setLearningModules($learningModules);

        $xml = simplexml_load_string($group->toXML($accountApi, $userApi, $method));
        self::assertEquals('SmarterU', $xml->getName());
        $tags = [];
        foreach ($xml->children() as $tag) {
            $tags[] = $tag->getName();
        }
        self::assertCount(4, $tags);
        self::assertContains('AccountAPI', $tags);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $tags);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $tags);
        self::assertEquals($method, $xml->Method);
        self::assertContains('Parameters', $tags);
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
        self::assertCount(7, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals($group->getName(), $xml->Parameters->Group->Name);
        self::assertContains('Status', $groupTags);
        self::assertEquals(
            $group->getStatus(),
            $xml->Parameters->Group->Status
        );
        self::assertContains('Description', $groupTags);
        self::assertEquals(
            $group->getDescription(),
            $xml->Parameters->Group->Description
        );
        self::assertContains('HomeGroupMessage', $groupTags);
        self::assertEquals(
            $group->getHomeGroupMessage(),
            $xml->Parameters->Group->HomeGroupMessage
        );
        self::assertContains('NotificationEmails', $groupTags);
        $emails = [];
        foreach ((array) $xml->Parameters->Group->NotificationEmails->NotificationEmail as $email) {
            $emails[] = $email;
        }
        self::assertEquals(
            count($emails),
            count($group->getNotificationEmails())
        );
        foreach ($emails as $email) {
            self::assertContains($email, $group->getNotificationEmails());
        }
        $users = [];
        foreach ($xml->Parameters->Group->Users->children() as $user) {
            $users[] = (array) $user;
        }
        self::assertCount(2, $users);
        foreach ($users as $user) {
            self::assertIsArray($user);
            self::assertCount(3, $user);
            self::assertArrayHasKey('EmployeeID', $user);
            self::assertArrayHasKey('HomeGroup', $user);
            self::assertArrayHasKey('Permissions', $user);
        }
        self::assertEquals(
            $users[0]['EmployeeID'],
            $group->getUsers()[0]->getEmployeeId()
        );
        self::assertEquals(
            $users[0]['HomeGroup'],
            $group->getUsers()[0]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[0]['Permissions'];
        $codes = $codeSegment['Code'];
        self::assertEquals(
            count($codes),
            count($group->getUsers()[0]->getPermissions())
        );
        foreach ($group->getUsers()[0]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertEquals(
            $users[1]['EmployeeID'],
            $group->getUsers()[1]->getEmployeeId()
        );
        self::assertEquals(
            $users[1]['HomeGroup'],
            $group->getUsers()[1]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[1]['Permissions'];
        self::assertEquals(
            count($codeSegment),
            count($group->getUsers()[1]->getPermissions())
        );
        foreach ($group->getUsers()[1]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertContains('LearningModules', $groupTags);
        $modules = [];
        foreach ($xml->Parameters->Group->LearningModules->LearningModule as $module) {
            $modules[] = (array) $module;
        }

        self::assertEquals(count($modules), count($group->getLearningModules()));
        foreach ($modules as $module) {
            self::assertIsArray($module);
            self::assertCount(3, $module);
            self::assertArrayHasKey('ID', $module);
            self::assertArrayHasKey('AllowSelfEnroll', $module);
            self::assertArrayHasKey('AutoEnroll', $module);
        }
        self::assertEquals(
            $modules[0]['ID'],
            $group->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $group->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $group->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $group->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $group->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $group->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );
    }

    /**
     * Test that XML generation produces the expected result for updateGroup
     * when all required values but no optional values are present.
     */
    public function testXMLOutputWithoutOptionalValuesUpdateGroup() {
        $accountApi = 'account';
        $userApi = 'user';
        $method = 'updateGroup';
        $name = 'Test Group';
        $newName = 'New Group';
        $users = $this->group->getUsers();
        $user1 = $users[0];
        $user2 = $users[1];
        $user1->setAction('Add');
        $user2->setAction('Remove');
        $users = [$user1, $user2];
        $learningModules = $this->group->getLearningModules();
        $module1 = $learningModules[0];
        $module2 = $learningModules[1];
        $module1->setAction('Add');
        $module2->setAction('Remove');
        $learningModules = [$module1, $module2];
        $subscriptionVariants = $this->group->getSubscriptionVariants();
        $variant1 = $subscriptionVariants[0];
        $variant2 = $subscriptionVariants[1];
        $variant1->setAction('Add');
        $variant2->setAction('Remove');
        $subscriptionVariants = [$variant1, $variant2];

        $group = (new Group())
            ->setOldName($name)
            ->setName($newName)
            ->setUsers($users)
            ->setLearningModules($learningModules)
            ->setSubscriptionVariants($subscriptionVariants);

        $xml = simplexml_load_string($group->toXML($accountApi, $userApi, $method));
        self::assertEquals('SmarterU', $xml->getName());
        $tags = [];
        foreach ($xml->children() as $tag) {
            $tags[] = $tag->getName();
        }
        self::assertCount(4, $tags);
        self::assertContains('AccountAPI', $tags);
        self::assertEquals($accountApi, $xml->AccountAPI);
        self::assertContains('UserAPI', $tags);
        self::assertEquals($userApi, $xml->UserAPI);
        self::assertContains('Method', $tags);
        self::assertEquals($method, $xml->Method);
        self::assertContains('Parameters', $tags);
        $parameters = [];
        foreach ($xml->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(2, $parameters);
        self::assertContains('Identifier', $parameters);
        $identifierTag = [];
        foreach ($xml->Parameters->Identifier->children() as $tag) {
            $identifierTag[] = $tag->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Name', $identifierTag);
        self::assertEquals(
            $name,
            $xml->Parameters->Identifier->Name
        );
        self::assertContains('Group', $parameters);
        $groupTags = [];
        foreach ($xml->Parameters->Group->children() as $tag) {
            $groupTags[] = $tag->getName();
        }
        self::assertCount(4, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals($group->getName(), $xml->Parameters->Group->Name);
        $users = [];
        foreach ($xml->Parameters->Group->Users->children() as $user) {
            $users[] = (array) $user;
        }
        self::assertCount(2, $users);
        foreach ($users as $user) {
            self::assertIsArray($user);
            self::assertCount(4, $user);
            self::assertArrayHasKey('EmployeeID', $user);
            self::assertArrayHasKey('UserAction', $user);
            self::assertArrayHasKey('HomeGroup', $user);
            self::assertArrayHasKey('Permissions', $user);
        }
        self::assertEquals(
            $users[0]['EmployeeID'],
            $group->getUsers()[0]->getEmployeeId()
        );
        self::assertEquals(
            $users[0]['UserAction'],
            $group->getUsers()[0]->getAction()
        );
        self::assertEquals(
            $users[0]['HomeGroup'],
            $group->getUsers()[0]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[0]['Permissions'];
        $codes = $codeSegment['Code'];
        self::assertEquals(
            count($codes),
            count($group->getUsers()[0]->getPermissions())
        );
        foreach ($group->getUsers()[0]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertEquals(
            $users[1]['EmployeeID'],
            $group->getUsers()[1]->getEmployeeId()
        );
        self::assertEquals(
            $users[1]['UserAction'],
            $group->getUsers()[1]->getAction()
        );
        self::assertEquals(
            $users[1]['HomeGroup'],
            $group->getUsers()[1]->getHomeGroup() ? '1' : '0'
        );
        $codeSegment = (array) $users[1]['Permissions'];
        self::assertEquals(
            count($codeSegment),
            count($group->getUsers()[1]->getPermissions())
        );
        foreach ($group->getUsers()[1]->getPermissions() as $permission) {
            self::assertContains($permission->getCode(), $codes);
        }

        self::assertContains('LearningModules', $groupTags);
        $modules = [];
        foreach ($xml->Parameters->Group->LearningModules->LearningModule as $module) {
            $modules[] = (array) $module;
        }

        self::assertEquals(
            count($modules),
            count($group->getLearningModules())
        );
        foreach ($modules as $module) {
            self::assertIsArray($module);
            self::assertCount(4, $module);
            self::assertArrayHasKey('ID', $module);
            self::assertArrayHasKey('LearningModuleAction', $module);
            self::assertArrayHasKey('AllowSelfEnroll', $module);
            self::assertArrayHasKey('AutoEnroll', $module);
        }
        self::assertEquals(
            $modules[0]['ID'],
            $group->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['LearningModuleAction'],
            $group->getLearningModules()[0]->getAction()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $group->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $group->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $group->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['LearningModuleAction'],
            $group->getLearningModules()[1]->getAction()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $group->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $group->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );
        self::assertContains('SubscriptionVariants', $groupTags);
        $variants = [];
        foreach ($xml->Parameters->Group->SubscriptionVariants->SubscriptionVariant as $variant) {
            $variants[] = (array) $variant;
        }
        self::assertEquals(
            count($variants),
            count($group->getSubscriptionVariants())
        );
        foreach ($variants as $variant) {
            self::assertIsArray($variant);
            self::assertCount(3, $variant);
            self::assertArrayHasKey('ID', $variant);
            self::assertArrayHasKey('SubscriptionVariantAction', $variant);
            self::assertArrayHasKey('RequiresCredits', $variant);
        }
        self::assertEquals(
            $variants[0]['ID'],
            $group->getSubscriptionVariants()[0]->getId()
        );
        self::assertEquals(
            $variants[0]['SubscriptionVariantAction'],
            $group->getSubscriptionVariants()[0]->getAction()
        );
        self::assertEquals(
            $variants[0]['RequiresCredits'],
            $group->getSubscriptionVariants()[0]->getRequiresCredits() ? '1' : '0'
        );
        self::assertEquals(
            $variants[1]['ID'],
            $group->getSubscriptionVariants()[1]->getId()
        );
        self::assertEquals(
            $variants[1]['SubscriptionVariantAction'],
            $group->getSubscriptionVariants()[1]->getAction()
        );
        self::assertEquals(
            $variants[1]['RequiresCredits'],
            $group->getSubscriptionVariants()[1]->getRequiresCredits() ? '1' : '0'
        );
    }
}
