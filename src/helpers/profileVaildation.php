<?php

function VaildatePassword(string $newPassword, string $currentPassword): array
{
    $errors = [];

    if (empty($newPassword)) {
        $errors["new-password"] = "enter a valid password";
    }
    if (empty($currentPassword)) {
        $errors["current-password"] = "enter a valid password";
    }
    return $errors;
}
