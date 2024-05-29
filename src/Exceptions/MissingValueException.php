<?php

/**
 * Contains CBS\SmarterU\Exceptions\MissingValueException
 *
 * @author      CORE Software Team
 * @copyright  $year$ Core Business Solutions
 * @license    MIT
 * @version    $version$
 * @since      2022/07/25
 */

declare(strict_types=1);

namespace CBS\SmarterU\Exceptions;

/**
 * An exception type to be thrown when the XML body of the request to be made
 * to the SmarterU API cannot be created because one or more required values
 * are missing.
 */
class MissingValueException extends \Exception {
}
