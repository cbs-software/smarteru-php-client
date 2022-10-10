<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\ExternalAuthorizationTest.php.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/10/10
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\ExternalAuthorization;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\ExternalAuthorization.
 */
class ExternalAuthorizationTest extends TestCase {
    /**
     * Test agreement between getters and setters.
     */
    public function testAgreement() {
        $authKey = 'authKey';
        $requestKey = 'requestKey';
        $redirectPath = 'redirectPath';

        $externalAuthorization = (new ExternalAuthorization())
            ->setAuthKey($authKey)
            ->setRequestKey($requestKey)
            ->setRedirectPath($redirectPath);

        self::assertEquals($authKey, $externalAuthorization->getAuthKey());
        self::assertEquals(
            $requestKey,
            $externalAuthorization->getRequestKey()
        );
        self::assertEquals(
            $redirectPath,
            $externalAuthorization->getRedirectPath()
        );
    }
}
