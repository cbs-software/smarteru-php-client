<?php

/**
 * Contains CBS\SmarterU\Exceptions\SmarterUException
 *
 * @copyright  $year$ Core Business Solutions
 * @license    MIT
 * @version    $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\Exceptions;

use CBS\SmarterU\DataTypes\ErrorCode;

/**
 * An exception type to use when the SmarterU API returns a failure message
 * due to some kind of problem with the data provided.
 */
class SmarterUException extends \Exception {
    /**
     * The list of errors detected by the SmarterU API which resulted in this
     * exception
     *
     * @var ErrorCode[]
     */
    protected array $errorCodes = [];

    /**
     * Create a new exception instance
     *
     * @param string $message  the exception message
     * @param ErrorCode[]  the list of SmarterU error codes which were detected
     *      when the exception was thrown
     */
    public function __construct(string $message = '', array $errorCodes = []) {
        parent::__construct($message);

        foreach ($errorCodes as $errorCode) {
            if (is_object($errorCode) && $errorCode instanceof ErrorCode) {
                $this->errorCodes[] = $errorCode;
            }
        }
    }

    /**
     * Get the string representation of the exception
     *
     * @return string  a string representing the exception
     */
    public function __toString() {
        $lines = [
            __CLASS__ . ': ' . $this->message
        ];

        foreach ($this->errorCodes as $errorCode) {
            $line[] = "\t{" . $errorCode->getErrorCode() . ': ' . $errorCode->getErrorMessage() . '}';
        }

        return implode("\n", $lines);
    }

    /**
     * Get the list of Error codes detected when the exception was thrown
     *
     * @return ErrorCode[]  the list of error codes
     */
    public function getErrorCodes(): array {
        return $this->errorCodes;
    }
}
