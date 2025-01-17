<?php

/**
 * Contains Tests\CBS\SmarterU\XMLGenerator\UpdateGroupXMLTest.
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
 * Tests CBS\SmarterU\XMLGenerator::updateGroup().
 */
class UpdateGroupXMLTest extends TestCase {
    /**
     * A Group to use for testing purposes. This Group has all optional and all
     * required information set.
     */
    protected Group $group1;

    /**
     * A Group to use for testing purposes. This Group only has optional
     * information set.
     */
    protected Group $group2;

    /**
     * A Group to use for testing purposes. This Group has a missing attribute
     * that will trigger an exception.
     */
    protected Group $group3;

    /**
     * Set up the test Groups.
     */
    public function setUp(): void {
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
            ->setAction('Add')
            ->setAllowSelfEnroll(true)
            ->setAutoEnroll(false);
        $module2 = (new LearningModule())
            ->setId('5')
            ->setAction('Remove')
            ->setAllowSelfEnroll(false)
            ->setAutoEnroll(true);

        $variant1 = (new SubscriptionVariant())
            ->setId('6')
            ->setAction('Add')
            ->setRequiresCredits(true);
        $variant2 = (new SubscriptionVariant())
            ->setId('7')
            ->setAction('Remove')
            ->setRequiresCredits(false);

        $this->group1 = (new Group())
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

        $this->group2 = (new Group())
            ->setName('Second Group')
            ->setOldName('Old Name')
            ->setLearningModules([])
            ->setSubscriptionVariants([]);

        $this->group3 = (new Group())
            ->setName('Third Group')
            ->setGroupId('3')
            ->setStatus('Active')
            ->setDescription('A Group with an invalid tag')
            ->setHomeGroupMessage('Third group\'s message')
            ->setNotificationEmails(['test5@test.com', 'test6@test.com'])
            ->setUserHelpOverrideDefault(false)
            ->setUserHelpEnabled(true)
            ->setUserHelpEmail(['help3@test.com', 'help4@test.com'])
            ->setUserHelpText('Help')
            ->setTags([$tag3])
            ->setUserLimitEnabled(true)
            ->setUserLimitAmount(20)
            ->setLearningModules([$module1, $module2])
            ->setSubscriptionVariants([$variant1, $variant2])
            ->setDashboardSetId('3');
    }

    /**
     * Tests that the XML generation process for an UpdateGroup request throws
     * an exception if the Group being updated has a Tag that does not have
     * an identifier.
     */
    public function testUpdateGroupThrowsExceptionWhenNoTagIdentifier(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Every tag must have either a name or an ID.'
        );
        $xml = $xmlGenerator->updateGroup(
            $accountApi,
            $userApi,
            $this->group3
        );
    }

    /**
     * Tests that the XML generation process for an UpdateGroup request produces
     * the expected output when all required information is present but all
     * optional attributes are left blank.
     */
    public function testUpdateGroupProducesExpectedOutputWithoutOptionalInfo(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->updateGroup(
            $accountApi,
            $userApi,
            $this->group2
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
        self::assertCount(5, $groupTags);
        self::assertContains('Identifier', $groupTags);
        $identifierTag = [];
        foreach ($xml->Parameters->Group->Identifier->children() as $identifier) {
            $identifierTag[] = $identifier->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Name', $identifierTag);
        self::assertEquals(
            'Old Name',
            $xml->Parameters->Group->Identifier->Name
        );
        self::assertContains('Name', $groupTags);
        self::assertEquals(
            $this->group2->getName(),
            $xml->Parameters->Group->Name
        );
        self::assertContains('Users', $groupTags);
        self::assertCount(0, $xml->Parameters->Group->Users->children());
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
     * Tests that the XML generation process for an UpdateGroup request produces
     * the expected output when all required and optional information is present.
     */
    public function testUpdateGroupProducesExpectedOutputWithAllInfo(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $xml = $xmlGenerator->updateGroup(
            $accountApi,
            $userApi,
            $this->group1
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
        $groupTags = [];
        foreach ($xml->Parameters->Group->children() as $tag) {
            $groupTags[] = $tag->getName();
        }
        self::assertCount(15, $groupTags);
        self::assertContains('Identifier', $groupTags);
        $identifierTag = [];
        foreach ($xml->Parameters->Group->Identifier->children() as $identifier) {
            $identifierTag[] = $identifier->getName();
        }
        self::assertCount(1, $identifierTag);
        self::assertContains('Name', $identifierTag);
        self::assertEquals(
            $this->group1->getName(),
            $xml->Parameters->Group->Identifier->Name
        );
        self::assertContains('Group', $parameters);
        self::assertContains('Status', $groupTags);
        self::assertEquals(
            $this->group1->getStatus(),
            $xml->Parameters->Group->Status
        );
        self::assertContains('Description', $groupTags);
        self::assertEquals(
            $this->group1->getDescription(),
            $xml->Parameters->Group->Description
        );
        self::assertContains('HomeGroupMessage', $groupTags);
        self::assertEquals(
            $this->group1->getHomeGroupMessage(),
            $xml->Parameters->Group->HomeGroupMessage
        );
        self::assertContains('NotificationEmails', $groupTags);
        $emails = [];
        foreach ((array) $xml->Parameters->Group->NotificationEmails->NotificationEmail as $email) {
            $emails[] = $email;
        }
        self::assertEquals(
            count($emails),
            count($this->group1->getNotificationEmails())
        );
        foreach ($emails as $email) {
            self::assertContains(
                $email,
                $this->group1->getNotificationEmails()
            );
        }
        self::assertContains('UserHelpOverrideDefault', $groupTags);
        self::assertEquals(
            $this->group1->getUserHelpOverrideDefault() ? '1' : '0',
            (string) $xml->Parameters->Group->UserHelpOverrideDefault
        );
        self::assertContains('UserHelpEnabled', $groupTags);
        self::assertEquals(
            $this->group1->getUserHelpEnabled() ? '1' : '0',
            (string) $xml->Parameters->Group->UserHelpEnabled
        );
        self::assertContains('UserHelpEmail', $groupTags);
        self::assertEquals(
            implode(',', $this->group1->getUserHelpEmail()),
            $xml->Parameters->Group->UserHelpEmail
        );
        self::assertContains('UserHelpText', $groupTags);
        self::assertEquals(
            $this->group1->getUserHelpText(),
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
            $this->group1->getTags()[0]->getTagId()
        );
        self::assertArrayHasKey('TagValues', $tags[0]);
        self::assertEquals(
            $tags[0]['TagValues'],
            $this->group1->getTags()[0]->getTagValues()
        );
        self::assertIsArray($tags[1]);
        self::assertCount(2, $tags[1]);
        self::assertArrayHasKey('TagName', $tags[1]);
        self::assertEquals(
            $tags[1]['TagName'],
            $this->group1->getTags()[1]->getTagName()
        );
        self::assertArrayHasKey('TagValues', $tags[1]);
        self::assertEquals(
            $tags[1]['TagValues'],
            $this->group1->getTags()[1]->getTagValues()
        );
        self::assertContains('UserLimit', $groupTags);
        $limitTags = [];
        foreach ($xml->Parameters->Group->UserLimit->children() as $limitTag) {
            $limitTags[] = $limitTag->getName();
        }
        self::assertCount(2, $limitTags);
        self::assertContains('Enabled', $limitTags);
        self::assertEquals(
            $this->group1->getUserLimitEnabled() ? '1' : '0',
            (string) $xml->Parameters->Group->UserLimit->Enabled
        );
        self::assertContains('Amount', $limitTags);
        self::assertEquals(
            $this->group1->getUserLimitAmount(),
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
            count($this->group1->getLearningModules())
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
            $this->group1->getLearningModules()[0]->getId()
        );
        self::assertEquals(
            $modules[0]['LearningModuleAction'],
            $this->group1->getLearningModules()[0]->getAction()
        );
        self::assertEquals(
            $modules[0]['AllowSelfEnroll'],
            $this->group1->getLearningModules()[0]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[0]['AutoEnroll'],
            $this->group1->getLearningModules()[0]->getAutoEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['ID'],
            $this->group1->getLearningModules()[1]->getId()
        );
        self::assertEquals(
            $modules[1]['LearningModuleAction'],
            $this->group1->getLearningModules()[1]->getAction()
        );
        self::assertEquals(
            $modules[1]['AllowSelfEnroll'],
            $this->group1->getLearningModules()[1]->getAllowSelfEnroll() ? '1' : '0'
        );
        self::assertEquals(
            $modules[1]['AutoEnroll'],
            $this->group1->getLearningModules()[1]->getAutoEnroll() ? '1' : '0'
        );

        self::assertContains('SubscriptionVariants', $groupTags);
        $variants = [];
        foreach ($xml->Parameters->Group->SubscriptionVariants->SubscriptionVariant as $variant) {
            $variants[] = (array) $variant;
        }

        self::assertEquals(count($variants), count($this->group1->getSubscriptionVariants()));
        foreach ($variants as $variant) {
            self::assertIsArray($variant);
            self::assertCount(3, $variant);
            self::assertArrayHasKey('ID', $variant);
            self::assertArrayHasKey('SubscriptionVariantAction', $variant);
            self::assertArrayHasKey('RequiresCredits', $variant);
        }
        self::assertEquals(
            $variants[0]['ID'],
            $this->group1->getSubscriptionVariants()[0]->getId()
        );
        self::assertEquals(
            $variants[0]['SubscriptionVariantAction'],
            $this->group1->getSubscriptionVariants()[0]->getAction()
        );
        self::assertEquals(
            $variants[0]['RequiresCredits'],
            $this->group1->getSubscriptionVariants()[0]->getRequiresCredits() ? '1' : '0'
        );
        self::assertEquals(
            $variants[1]['ID'],
            $this->group1->getSubscriptionVariants()[1]->getId()
        );
        self::assertEquals(
            $variants[1]['SubscriptionVariantAction'],
            $this->group1->getSubscriptionVariants()[1]->getAction()
        );
        self::assertEquals(
            $variants[1]['RequiresCredits'],
            $this->group1->getSubscriptionVariants()[1]->getRequiresCredits() ? '1' : '0'
        );
        self::assertContains('DashboardSetID', $groupTags);
        self::assertEquals(
            $xml->Parameters->Group->DashboardSetID,
            $this->group1->getDashboardSetId()
        );
    }

    /**
     * Tests that the XML generation process for an UpdateGroup request produces
     * the expected output when group name contains a special character.
     */
    public function testUpdateGroupWithSpecialCharInName(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $name = 'Sanford & Son';
        $group = $this->group2->setName($name);
        $xml = $xmlGenerator->updateGroup(
            $accountApi,
            $userApi,
            $group
        );

        self::assertIsString($xml);
        self::assertStringContainsString('Sanford &amp; Son', $xml);
    }

    /**
     * Tests that the XML generation process for an UpdateGroup request produces
     * the expected output when the old group name contains a special character.
     */
    public function testUpdateGroupWithSpecialCharInOldName(): void {
        $xmlGenerator = new XMLGenerator();
        $accountApi = 'account';
        $userApi = 'user';
        $oldName = 'Sanford & Son';
        $group = $this->group2->setOldName($oldName);
        $xml = $xmlGenerator->updateGroup(
            $accountApi,
            $userApi,
            $group
        );

        self::assertIsString($xml);
        self::assertStringContainsString('Sanford &amp; Son', $xml);
    }
}
