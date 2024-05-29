<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\ErrorCodeTest
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
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
