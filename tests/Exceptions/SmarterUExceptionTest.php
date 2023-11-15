<?php

/**
 * Contains Tests\CBS\SmarterU\Exceptions\SmarterUExceptionTest
 *
 * @author      Tom Egan <tom.egan@thecoresolution.com>
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
        $request = '<SmarterU><AccountAPI>12345</AccountAPI><UserAPI>password</UserAPI><Method>updateUser</Method><Parameters><User><Identifier><Email>ryan.davis@thecoresolution.com</Email></Identifier><Info><Email>ryan.davis@thecoresolution.com</Email><EmployeeID/><GivenName>Ryann</GivenName></Info><Profile><Status>Active</Status><ReceiveNotifications>1</ReceiveNotifications></Profile><Groups/><Venues/><Wages/></User></Parameters></SmarterU>';
        $response = '<SmarterU><Result>Failed</Result><Info/><Errors><Error><ErrorID>UU:69</ErrorID><ErrorMessage>The requested user cannot be updated via the API.</ErrorMessage></Error></Errors></SmarterU>';
        $errorCode = new ErrorCode($code, $message);

        $exception = new SmarterUException($message, [$errorCode], $request, $response);

        self::assertEquals($message, $exception->getMessage());
        $errorCodes = $exception->getErrorCodes();
        self::assertIsArray($errorCodes);
        self::assertCount(1, $errorCodes);

        $errorCode = reset($errorCodes);
        $expectedRequest = '<SmarterU><AccountAPI>********</AccountAPI><UserAPI>********</UserAPI><Method>updateUser</Method><Parameters><User><Identifier><Email>ryan.davis@thecoresolution.com</Email></Identifier><Info><Email>ryan.davis@thecoresolution.com</Email><EmployeeID/><GivenName>Ryann</GivenName></Info><Profile><Status>Active</Status><ReceiveNotifications>1</ReceiveNotifications></Profile><Groups/><Venues/><Wages/></User></Parameters></SmarterU>';
        self::assertEquals($code, $errorCode->getErrorCode());
        self::assertEquals($message, $errorCode->getErrorMessage());
        self::assertEquals($expectedRequest, $exception->getRequest());
        self::assertEquals($response, $exception->getResponse());
    }
}
