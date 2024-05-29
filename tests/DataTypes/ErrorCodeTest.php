<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\ErrorCodeTest
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022-12-08
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\ErrorCode;
use PHPUnit\Framework\TestCase;

/** Tests CBS\SmarterU\DataTypes\ErrorCode */
class ErrorCodeTest extends TestCase {
    /** Test that properties are correctly initialized */
    public function testAgreement() {
        $code = 'SU:01';
        $message = 'No POST data detected';

        $errorCode = new ErrorCode($code, $message);

        self::assertEquals($code, $errorCode->getErrorCode());
        self::assertEquals($message, $errorCode->getErrorMessage());
    }
}
