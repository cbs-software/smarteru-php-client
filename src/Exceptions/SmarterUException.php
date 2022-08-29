<?php

/**
 * Contains SmarterU\Exceptions\SmarterUException
 *
 * @author     Will Santanen <will.santanen@thecoresolution.com>
 * @copyright  $year$ Core Business Solutions
 * @license    MIT
 * @version    $version$
 * @since      2022/07/21
 */

declare(strict_types=1);

namespace CBS\SmarterU\Exceptions;

/**
 * An exception type to use when the SmarterU API returns a failure message
 * due to some kind of problem with the data provided.
 */
class SmarterUException extends \Exception {
}
