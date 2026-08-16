<?php

function todosInputsValidation(string $title, string $content, string $status, string $date): array
{
    $errors      = [];
    $statusArray = [
        'pending',
        'in_progress',
        'completed',
    ];
    $title   = trim($title);
    $status  = strtolower(trim($status));
    $content = trim($content);
    $date    = trim($date);

    if (empty($title)) {
        $errors["title"] = "please fill a valid title";
    }

    if (empty($content)) {
        $errors["content"] = "please fill a valid content";
    }
    if (empty($date)) {
        $errors["date"] = "please fill a valid date";
    }

    if (empty($status) || ! in_array($status, $statusArray, true)) {
        $errors["status"] = "please choose a valid status";
    }
    return $errors;
}
