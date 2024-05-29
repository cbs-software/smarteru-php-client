<?php

/**
 * Contains Tests\CBS\SmarterU\Exceptions\SmarterUExceptionTest
 *
 * @author      CORE Software Team
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022-12-08
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Exceptions;

use CBS\SmarterU\DataTypes\ErrorCode;
use CBS\SmarterU\Exceptions\SmarterUException;
use PHPUnit\Framework\TestCase;

/** Tests CBS\SmarterU\Exceptions\SmarterUException */
class SmarterUExceptionTest extends TestCase {
    /** Test that properties are correctly initialized */
    public function testAgreement() {
        $code = 'SU:01';
        $message = 'No POST data detected';
        $errorCode = new ErrorCode($code, $message);

        $exception = new SmarterUException($message, [$errorCode]);

        self::assertEquals($message, $exception->getMessage());
        $errorCodes = $exception->getErrorCodes();
        self::assertIsArray($errorCodes);
        self::assertCount(1, $errorCodes);

        $errorCode = reset($errorCodes);
        self::assertEquals($code, $errorCode->getErrorCode());
        self::assertEquals($message, $errorCode->getErrorMessage());
    }
}
