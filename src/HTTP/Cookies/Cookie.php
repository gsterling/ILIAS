<?php

namespace ILIAS\HTTP\Cookies;

/**
 * Interface Cookie
 *
 * ILIAS cookie representation.
 * All implementations must be immutable.
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 * @package ILIAS\HTTP\Cookies
 * @since   5.3
 * @version 1.0.0
 */
interface Cookie
{

    /**
     * Cookie name.
     *
     * @return string
     */
    public function getName() : string;


    /**
     * Cookie value.
     *
     * @return string|null
     */
    public function getValue() : ?string;


    /**
     * Expiration date as unix timestamp.
     *
     * @return int
     */
    public function getExpires() : int;


    /**
     * Max age measured in seconds.
     * If the max age is zero no max age is set.
     *
     * @return int
     */
    public function getMaxAge() : int;


    /**
     * Cookie path.
     *
     * @return string
     */
    public function getPath() : ?string;


    /**
     * Cookie domain.
     *
     * @return string
     */
    public function getDomain() : ?string;


    /**
     * True if it's secure cookie otherwise false.
     *
     * @return bool
     */
    public function getSecure() : bool;


    /**
     * True if the cookie is http only otherwise false.
     *
     * @return bool
     */
    public function getHttpOnly() : bool;


    /**
     * Sets the cookie value.
     *
     * @param null|string $value The cookie value.
     *
     * @return Cookie
     */
    public function withValue(string $value = null) : Cookie;


    /**
     * Sets the expiration date of the cookie.
     * If the cookie should be expired please use the expire function.
     *
     * If the expires parameter equals null,
     * then the expires key will be removed from the cookie.
     *
     * @param null|\DateTimeInterface|int|string $expires The expiration time of the Cookie.
     *
     * @return Cookie
     */
    public function withExpires($expires = null) : Cookie;


    /**
     * Sets the expiration date to +5 years.
     *
     * @return Cookie
     */
    public function rememberForLongTime() : Cookie;


    /**
     * Expire the cookie.
     * Useful if the cookie should be deleted at the client side.
     *
     * @return Cookie
     */
    public function expire() : Cookie;


    /**
     * Maximal life time of the cookie in seconds.
     * The most browser prefer max age over expiration date.
     *
     * @param null|int $maxAge Lifetime in seconds.
     *
     * @return Cookie
     */
    public function withMaxAge(int $maxAge = null) : Cookie;


    /**
     * Sets the cookie path.
     *
     * @param null|string $path The cookie path.
     *
     * @return Cookie
     */
    public function withPath(string $path = null) : Cookie;


    /**
     * Sets the domain name for the cookie.
     *
     * @param null|string $domain Cookie domain.
     *
     * @return Cookie
     */
    public function withDomain(string $domain = null) : Cookie;


    /**
     * Sets if the cookie is a secure cookie or not.
     *
     * @param null|bool $secure Secure flag.
     *
     * @return Cookie
     */
    public function withSecure(bool $secure = null) : Cookie;


    /**
     * Sets if the cookie is http only.
     *
     * @param null|bool $httpOnly http only flag.
     *
     * @return Cookie
     */
    public function withHttpOnly(bool $httpOnly = null) : Cookie;


    /**
     * Returns the string representation of the object.
     *
     * @return string String representation.
     */
    public function __toString() : string;
}