<?php

/**
 * Contains CBS\SmarterU\Exceptions\InvalidArgumentException
 *
 * @copyright  $year$ Core Business Solutions
 * @license    MIT
 */

declare(strict_types=1);

namespace CBS\SmarterU\Exceptions;

/**
 * An exception type to use when a parameter supplied to the method does not
 * meet some requirement of the method eg. if a parameter must be a string
 * corresponding to a class name and is a string but does not correspond to a
 * class name registered with PHP.
 */
class InvalidArgumentException extends \Exception {
}
