<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\LearningModuleTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/03
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\LearningModule;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\LearningModule.
 */
class LearningModuleTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $id = '1';
        $allowSelfEnroll = true;
        $autoEnroll = false;
        $action = 'Add';

        $learningModule = (new LearningModule())
            ->setId($id)
            ->setAllowSelfEnroll($allowSelfEnroll)
            ->setAutoEnroll($autoEnroll)
            ->setAction($action);

        self::assertEquals($id, $learningModule->getId());
        self::assertEquals(
            $allowSelfEnroll,
            $learningModule->getAllowSelfEnroll()
        );
        self::assertEquals($autoEnroll, $learningModule->getAutoEnroll());
        self::assertEquals($action, $learningModule->getAction());
    }
}
