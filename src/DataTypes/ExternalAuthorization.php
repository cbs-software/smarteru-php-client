<?php

/**
 * Contains CBS\SmarterU\DataTypes\ExternalAuthorization
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version
 * @since       2022-10-10
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

/**
 * The ExternalAuthorization class represents the information returned by the
 * SmarterU API when a user logs in through a third-party interface using the
 * RequestExternalAuthorization method.
 */
class ExternalAuthorization {
    /**
     * The one-time authorization key used to authenticate a user within
     * SmarterU. This key will expire within 60 seconds of the request.
     */
    protected string $authKey;

    /**
     * The unique identifier of the authorization request. This key must be
     * used in tandem with the AuthKey to have a user authenticated in SmarterU.
     */
    protected string $requestKey;

    /**
     * The full path to redirect the user to SmarterU to finalize the
     * authentication process. This URL will change based upon the settings
     * of your SmarterU account to reflect whether you're using a CNAME entry
     * or a keyword for a customized login portal.
     */
    protected string $redirectPath;

    /**
     * Get the one-time authorization key.
     *
     * @return string The authorization key
     */
    public function getAuthKey(): string {
        return $this->authKey;
    }

    /**
     * Set the one-time authorization key.
     *
     * @param string $authKey The authorization key
     * @return self
     */
    public function setAuthKey(string $authKey): self {
        $this->authKey = $authKey;
        return $this;
    }

    /**
     * Get the unique identifier of the authorization request.
     *
     * @return string The unique identifier of the authorization request
     */
    public function getRequestKey(): string {
        return $this->requestKey;
    }

    /**
     * Set the unique identifier of the authorization request.
     *
     * @param string $requestKey The unique identifier of the authorization
     *      request
     * @return self
     */
    public function setRequestKey(string $requestKey): self {
        $this->requestKey = $requestKey;
        return $this;
    }

    /**
     * Get the full path to redirect the user to SmarterU.
     *
     * @return string The full path to redirect the user to SmarterU
     */
    public function getRedirectPath(): string {
        return $this->redirectPath;
    }

    /**
     * Set the full path to redirect the user to SmarterU.
     *
     * @param string $redirectPath The full path to redirect the user to SmarterU
     * @return self
     */
    public function setRedirectPath(string $redirectPath): self {
        $this->redirectPath = $redirectPath;
        return $this;
    }
}
