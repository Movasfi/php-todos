<?php

function validateRegistration(string $username, string $email, string $password, string $confirmPassword): array
{
    $errors = [];

    $username = trim($username);
    $email    = trim($email);

    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

    if (empty($email)) {
        $errors['email'] = "Email address is required.";
    } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password) || is_numeric($password)) {
        $errors['password'] = "please enter a valid password";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }

    if (empty($confirmPassword) || is_numeric($password)) {
        $errors['confirmPassword'] = "please enter a valid password";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm-password'] = "Passwords do not match.";
    }

    return $errors;
}

function existedEmail(object | bool | null $emailObj): array
{
    $errors = [];

    if ($emailObj !== false && ! empty($emailObj)) {
        $errors["email"] = "this email is used";
    }

    return $errors;
}
