<?php
const DATA_FILE = "data.txt";
function showRegistrants()
{
    $totalLines = file(DATA_FILE);
    $registrants = [];
    foreach ($totalLines as $line) {
        list($title, $firstName, $middleName, $lastName, $age, $email, $phone,  $date) = explode(";", $line);
        $registrants[] = [
            "title" => $title,
            "firstName" => $firstName,
            "middleName" => $middleName,
            "lastName" => $lastName,
            "age" => $age,
            "email" => $email,
            "phone" => $phone,
            "date" => $date
        ];
    }
    return $registrants;
}
