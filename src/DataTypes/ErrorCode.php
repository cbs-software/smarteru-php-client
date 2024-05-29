<?php

/**
 * Contains CBS\SmarterU\DataTypes\ErrorCode
 *
 * @author      CORE Software Team
 * @copyright $year$ Core Business Solutions
 * @license   MIT
 * @since     2022-12-07
 * @version   $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * ErrorCode encapsulates an error as reported by the SmarterU API.
 *
 * When reporting an error, the SmarterU API returns a list of errors; each
 * consisting of a code and a message.
 *
 * @see https://support.smarteru.com/docs/api-error-codes
 */
class ErrorCode {
    #region constants

    /**
     * The error code used by the SmarterU API to indicate that a group does not
     * exist
     */
    public const GROUP_NOT_FOUND = 'GG:03';

    /**
     * The error code used by the SmarterU API to indicate that a user does not
     * exist
     */
    public const USER_NOT_FOUND = 'GU:03';

    #endregion constants

    #region properties

    /** The error code */
    protected string $code;

    /** The error message */
    protected string $message;

    #endregion properties

    /**
     * Instantiate an ErrorCode
     *
     * @param string $code  the error code.
     * @param string $message  the error message.
     */
    public function __construct(string $code, string $message) {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * Get the error code
     *
     * @return string  the error code.
     */
    public function getErrorCode(): string {
        return $this->code;
    }

    /**
     * Get the error message
     *
     * @return string  the error message.
     */
    public function getErrorMessage(): string {
        return $this->message;
    }
}
