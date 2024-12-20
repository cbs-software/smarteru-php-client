<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\CreateGroupXMLTest.
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\XMLGenerator;

use CBS\SmarterU\DataTypes\Group;
use CBS\SmarterU\DataTypes\LearningModule;
use CBS\SmarterU\DataTypes\SubscriptionVariant;
use CBS\SmarterU\DataTypes\Tag;
use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\XMLGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\XMLGenerator::createGroup().
 */
class CreateGroupXMLTest extends TestCase {
    /**
     * Tests that the XML generation process for a CreateGroup request throws
     * an exception if the Group being created has a Tag that does not have
     * an identifier.
     */
    public function testCreateGroupThrowsExceptionWhenNoTagIdentifier(): void {
        $tag = (new Tag())
            ->setTagValues('This will throw an exception');

        $module1 = (new LearningModule())
            ->setId('4')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);

        $group = (new Group())
        ->setName('Third Group')
        ->setGroupId('3')
        ->setStatus('Active')
        ->setDescription('A Group with an invalid tag')
        ->setHomeGroupMessage('Third group\'s message')
        ->setNotificationEmails(['test5@test.com', 'test6@test.com'])
        ->setTags([$tag])
        ->setLearningModules([$module1, $module2]);

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Every tag must have either a name or an ID.'
        );
        $xml = $xmlGenerator->createGroup(
            $accountApi,
            $userApi,
            $group
        );
    }

    /**
     * Tests that the XML generation process for a CreateGroup request produces
     * the expected output when all required information is present but all
     * optional attributes are left blank.
     */
    public function testCreateGroupProducesExpectedOutputWithoutOptionalInfo(): void {
        $module1 = (new LearningModule())
            ->setId('4')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);

        $group = (new Group())
        ->setName('Second Group')
        ->setStatus('Active')
        ->setDescription('A Group without optional values')
        ->setHomeGroupMessage('Second group\'s message')
        ->setNotificationEmails(['test3@test.com', 'test4@test.com'])
        ->setLearningModules([$module1, $module2]);

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createGroup(
            $accountApi,
            $userApi,
            $group
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
        self::assertEquals('createGroup', $xml->Method);
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
        self::assertCount(7, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->Group->Name
        );
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
            self::assertContains(
                $email,
                $group->getNotificationEmails()
            );
        }

        self::assertCount(0, $xml->Parameters->Group->Users->children());

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
     * Tests that the XML generation process for a CreateGroup request produces
     * the expected output when all required and optional information is present.
     */
    public function testCreateGroupProducesExpectedOutputWithAllInfo(): void {
        $tag1 = (new Tag())
            ->setTagId('1')
            ->setTagValues('Tag1 values');
        $tag2 = (new Tag())
            ->setTagName('My Tag')
            ->setTagValues('Tag2 values');
        $tag3 = (new Tag())
            ->setTagValues('This will throw an exception');

        $module1 = (new LearningModule())
            ->setId('4')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);

        $variant1 = (new SubscriptionVariant())
            ->setId('6')
            ->setRequiresCredits(true);
        $variant2 = (new SubscriptionVariant())
            ->setId('7')
            ->setRequiresCredits(false);

        $group = (new Group())
            ->setName('First Group')
            ->setGroupId('1')
            ->setStatus('Active')
            ->setDescription('A Group with all optional values')
            ->setHomeGroupMessage('First group\'s message')
            ->setNotificationEmails(['test@test.com', 'test2@test.com'])
            ->setUserHelpOverrideDefault(false)
            ->setUserHelpEnabled(true)
            ->setUserHelpEmail(['help@test.com', 'help2@test.com'])
            ->setUserHelpText('Help')
            ->setTags([$tag1, $tag2])
            ->setUserLimitEnabled(true)
            ->setUserLimitAmount(20)
            ->setLearningModules([$module1, $module2])
            ->setSubscriptionVariants([$variant1, $variant2])
            ->setDashboardSetId('3');
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createGroup(
            $accountApi,
            $userApi,
            $group
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
        self::assertEquals('createGroup', $xml->Method);
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
        self::assertCount(16, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->Group->Name
        );
        self::assertContains('GroupID', $groupTags);
        self::assertEquals(
            $group->getGroupId(),
            $xml->Parameters->Group->GroupID
        );
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
            self::assertContains(
                $email,
                $group->getNotificationEmails()
            );
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
        self::assertArrayHasKey('TagName', $tags[1]);
        self::assertEquals(
            $tags[1]['TagName'],
            $group->getTags()[1]->getTagName()
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
        self::assertCount(0, $xml->Parameters->Group->Users->children());

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

        self::assertContains('SubscriptionVariants', $groupTags);
        $variants = [];
        foreach ($xml->Parameters->Group->SubscriptionVariants->SubscriptionVariant as $variant) {
            $variants[] = (array) $variant;
        }

        self::assertEquals(count($variants), count($group->getSubscriptionVariants()));
        foreach ($variants as $variant) {
            self::assertIsArray($variant);
            self::assertCount(2, $variant);
            self::assertArrayHasKey('ID', $variant);
            self::assertArrayHasKey('RequiresCredits', $variant);
        }
        self::assertEquals(
            $variants[0]['ID'],
            $group->getSubscriptionVariants()[0]->getId()
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
     * Verifies that createGroup() runs without errors when fields contain
     * ampersands.
     */
    public function testCreateGroupWithAmpersands(): void {
        $module1 = (new LearningModule())
            ->setId('4')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);

        $groupName = 'A & W Rootbeer';
        $homeGroupMessage = 'A & W Rootbeer is a great place to eat!';
        $description = 'A & W Rootbeer is a great drink! yum!';

        $group = (new Group())
            ->setName($groupName)
            ->setStatus('Active')
            ->setDescription($description)
            ->setHomeGroupMessage($homeGroupMessage)
            ->setNotificationEmails(['test3@test.com', 'test4@test.com'])
            ->setLearningModules([$module1, $module2]);

        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->createGroup(
            $accountApi,
            $userApi,
            $group
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
        self::assertEquals('createGroup', $xml->Method);
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
        self::assertCount(7, $groupTags);
        self::assertContains('Name', $groupTags);
        self::assertEquals(
            $group->getName(),
            $xml->Parameters->Group->Name
        );
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
            self::assertContains(
                $email,
                $group->getNotificationEmails()
            );
        }

        self::assertCount(0, $xml->Parameters->Group->Users->children());

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
}
