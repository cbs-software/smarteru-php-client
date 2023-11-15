<?php

/**
 * Contains CBS\SmarterU\Exceptions\SmarterUException
 *
 * @author     Will Santanen <will.santanen@thecoresolution.com>
 * @copyright  $year$ Core Business Solutions
 * @license    MIT
 * @version    $version$
 * @since      2022/07/21
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
     * The request sent to the SmarterU API which resulted in this exception.
     *
     * @var string
     */
    protected ?string $request = null;

    /**
     * The response sent to the SmarterU API which resulted in this exception.
     */
    protected ?string $response = null;

    /**
     * Create a new exception instance
     *
     * @param string $message  the exception message
     * @param ErrorCode[]  the list of SmarterU error codes which were detected
     *      when the exception was thrown
     */
    public function __construct(
        string $message = '',
        array $errorCodes = [],
        ?string $request = null,
        ?string $response = null
    ) {
        parent::__construct($message);

        foreach ($errorCodes as $errorCode) {
            if (is_object($errorCode) && $errorCode instanceof ErrorCode) {
                $this->errorCodes[] = $errorCode;
            }
        }

        $this->setRequest($request);
        $this->setResponse($response);
    }

    /**
     * Set the response XML returned from the SmarterU API which resulted in
     * this exception.
     *
     * @param string $response  the response XML
     * @return self
     */
    public function setResponse(?string $response = null): self {
        $this->response = $response;
        return $this;
    }

    /**
     * Returns the response XML returned from the SmarterU API which resulted in
     * this exception.
     *
     * @return string  the response XML
     */
    public function getResponse(): ?string {
        return $this->response;
    }

    /**
     * Set the request XML sent to the SmarterU API which resulted in this
     * exception.
     *
     * The request ill have the AccountAPI and UserAPI values hidden to prevent
     * sensitive information from being logged.
     *
     * @param string $request  the request XML
     * @return self
     */
    public function setRequest(?string $request = null): self {
        $this->request = $this->sanitizeRequest($request);
        return $this;
    }

    /**
     * Returns the request XML sent to the SmarterU API which resulted in this
     * exception.
     *
     * @return string  the request XML
     */
    public function getRequest(): ?string {
        return $this->request;
    }

    /**
     * Sanitize the request XML to remove sensitive information.
     *
     * @param string $request  the request XML
     * @return string the sanitized request XML
     */
    private function sanitizeRequest(?string $request = null): ?string {
        if (is_string($request)) {
            $request = preg_replace('/<AccountAPI>.*<\/AccountAPI>/', '<AccountAPI>********</AccountAPI>', $request);
            $request = preg_replace('/<UserAPI>.*<\/UserAPI>/', '<UserAPI>********</UserAPI>', $request);
        }

        return $request;
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
