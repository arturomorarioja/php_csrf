<?php
/**
 * Token functions.
 * session_start() must be invoked before running them.
 */

/**
 * Generate and store CSRF token in user session.
 * Requires session to have been started already.
 */
function createCsrfToken(): string {
    $token = md5(uniqid(rand(), true));
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
    return $token;
}

/**
 * Destroys a token by removing it from the session.
 */
function destroyCsrfToken(): void {
    $_SESSION['csrf_token'] = null;
    $_SESSION['csrf_token_time'] = null;
}

/**
 * Return an HTML tag including the CSRF token
 * for use in a form.
 */
function csrfTokenTag(): string {
    $token = createCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . $token .'">';
}

/**
 * Returns true if user-submitted POST token is
 * identical to the previously stored SESSION token.
 * Returns false otherwise.
 */
function csrfTokenIsValid(): bool {
    if (isset($_POST['csrf_token'])) {
        $user_token = $_POST['csrf_token'];
        $stored_token = $_SESSION['csrf_token'];
        return $user_token === $stored_token;
    } else {
        return false;
    }
}

/**
 * Optional check to see if token is also recent
 */
function csrfTokenIsRecent(): bool {
    $maxElapsed = 60 * 60 * 24;     // 1 day
    if(isset($_SESSION['csrf_token_time'])) {
        $storedTime = $_SESSION['csrf_token_time'];
        if (($storedTime + $maxElapsed) >= time()) {
            return true;
        } else {
            destroyCsrfToken();   // Remove expired token
            return false;
        }    
    } else {    
        destroyCsrfToken();       // Remove expired token
        return false;
    }
}