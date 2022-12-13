<?php

/**
 * Check if the login is an email address.
 *
 * @param $login string - The login to check.
 * @return bool - True if the login is an email address, false otherwise.
 */
function isMail(string $login): bool {
    return filter_var($login, FILTER_VALIDATE_EMAIL);
}

