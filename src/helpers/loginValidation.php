<?php

function validateLoginInputs(string $email, string $password)
{
    $errors = [];
    $email  = trim($email);
    if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password) || is_numeric($password)) {
        $errors["emailAndPassword"] = "Invalid email or password";
    }

    return $errors;
}
