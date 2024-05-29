<?php

/**
 * Contains CBS\SmarterU\Exceptions\MissingValueException
 *
 * @copyright  $year$ Core Business Solutions
 * @license    MIT
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
