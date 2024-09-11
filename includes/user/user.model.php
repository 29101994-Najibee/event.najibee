<?php

function set_user(
    object $db,
    string $voornaam,
    string $achternaam,
    string $telefoon,
    string $email,
    string $username,
    string $password,
    int $user_permission,
    string $user_type
) {
    $valid_user_types = ['coordinator', 'deelnemer', 'spreker'];

    if (!in_array($user_type, $valid_user_types)) {
        throw new InvalidArgumentException('Error: Invalid user_type');
    } else {
        $user_permissions = [
            'beheerder' => 255,
            'coordinator' => 159,
            'deelnemer' => 3,
            'spreker' => 7,
        ];

        $user_permission = $user_permissions[$user_type];
        $query =
            'INSERT INTO users (voornaam, achternaam, telefoon, email, username, password, user_permission, user_type) VALUES (:voornaam, :achternaam, :telefoon, :email, :username, :password ,:user_permission, :user_type );';
        $stmt = $db->prepare($query);

        if (!$stmt) {
            // Handle error, e.g., log or return an error message
            return 'Error in prepare statement: ' . $db->errorInfo()[2];
        }

        $options = [
            'cost' => 16,
        ];
        $hashPwd = password_hash($password, PASSWORD_BCRYPT, $options);

        $stmt->bindParam(':voornaam', $voornaam);
        $stmt->bindParam(':achternaam', $achternaam);
        $stmt->bindParam(':telefoon', $telefoon);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashPwd);
        $stmt->bindParam(':user_permission', $user_permission);
        $stmt->bindParam(':user_type', $user_type);

        if (!$stmt->execute()) {
            // Handle error, e.g., log or return an error message
            return 'Error in execute statement: ' . $stmt->errorInfo()[2];
        }
    }
}

function get_user(object $db, string $username)
{
    $query = 'SELECT * FROM users WHERE username = :username';
    $statement = $db->prepare($query);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result;
}