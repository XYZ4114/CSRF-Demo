<?php
// Generate a new CSRF token and store it in session
function generateToken() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_used'] = false;
    return $token;
}

function validateToken($token) {
    if (
        isset($_SESSION['csrf_token']) &&
        hash_equals($_SESSION['csrf_token'], $token) &&
        $_SESSION['csrf_token_used'] === false
    ) {
        $_SESSION['csrf_token_used'] = true; // mark as used
        return true;
    }
    return false;
}

// Clear token completely after use
function clearToken() {
    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_token_used']);
}
