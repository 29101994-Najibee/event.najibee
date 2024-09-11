<?php

function is_username_wrong($result): bool
{
    return $result === null || $result === false;
}
function is_password_wrong(string $password, string $hashPassword)
{
    return !password_verify($password, $hashPassword);
}
function user_login(object $db, string $username, string $password)
{
    $result = false;

    try {
        $errors = [];

        if (is_input_empty([$username, $password])) {
            $errors['input_empty'] = 'Vul alle velden in!';
        }

        $result = get_user($db, $username);
        if ($result !== false) {
            if (is_username_wrong($result)) {
                $errors['login_incorrect'] = 'Ongeldige username!';
            } else {
                if (
                    $result &&
                    isset($result['password']) &&
                    is_password_wrong($password, $result['password'])
                ) {
                    $errors['errors_login'] = 'Ongeldige username of password!';
                } elseif (isset($result['password'])) {
                    unset($result['password']);
                }
            }

            if ($errors) {
                return $errors;
            }
            restart_session($db, $result);
            // Check user or admin based on user_type
            if (
                isset($result['user_type']) &&
                isset($result['user_permission'])
            ) {
                if (
                    check_permission(
                        $result['user_permission'],
                        WRITE_POST | READ_POST
                    )
                ) {
                    $_SESSION['user_username'] = $result['username'];
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_type'] = $result['user_type']; /// make user_permission
                    $_SESSION['user_permission'] = $result['user_permission'];
                } elseif ($result['user_type'] === 'deelnemer') {
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_username'] = htmlspecialchars(
                        $result['username']
                    );
                    $_SESSION['last_regeneration'] = time();
                }
            } else {
                echo 'Invalid user type';
                exit();
            }
        }
    } catch (PDOException $e) {
        return 'Query failed:' . $e->getMessage();
    }
    return $result;
}

function get_user_by_session_id(object $db)
{
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $query = 'SELECT * FROM users WHERE id = :user_id';
        $statement = $db->prepare($query);

        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $statement->execute();

        $user_data = $statement->fetch(PDO::FETCH_ASSOC);

        return $user_data;
    } else {
        return null;
    }
}

function user_register(
    object $db,
    string $voornaam,
    string $achternaam,
    string $telefoon,
    string $email,
    string $username,
    string $password,
    string $cpassword,
    int $user_permission,
    string $user_type
) {
    try {
        $errors = [];

        if (
            is_input_empty([
                $voornaam,
                $achternaam,
                $telefoon,
                $email,
                $username,
                $password,
                $cpassword,
                $user_type,
            ])
        ) {
            $errors['empty_input'] = 'vul alle velden in!';
        }
        if (!is_name_valid($voornaam, $achternaam)) {
            $errors['invalid_name'] =
                'naam of achternaam bevaat geen spatie tussen! ';
        }
        if (is_telefoon_valid($telefoon)) {
            $errors['invalid_telefoon'] =
                'Ongeldig telefoonnummer, probeer het opnieuw';
        }
        if (is_email_valid($email)) {
            $errors['invalid_email'] = 'Ongeldig email, probeer het opnieuw!';
        }
        if (is_email_registered($email)) {
            $errors['email_taken'] =
                'Deze emailadres is all in gebruik, registeer met andere emailadres.';
        }
        if (is_username_taken($username)) {
            $errors['username_taken'] =
                'Deze username all in gebruik, registeer met andere username';
        }
        if (is_password_not_matched($password, $cpassword)) {
            $errors['unmatched_password'] =
                'Password en bevistiging password zijn neit hetzelfde!';
        }
        if (empty($errors)) {
            set_user(
                $db,
                $voornaam,
                $achternaam,
                $telefoon,
                $email,
                $username,
                $password,
                $user_permission,
                $user_type
            );
            // Redirect or return success

            return false;
        } else {
            // Handle errors, set them in session or display them
            $_SESSION['errors_register'] = $errors;
            $_SESSION['redirect_to_admin'] = true;
            return false;
        }
    } catch (Exception $e) {
        return 'Query failed:' . $e->getMessage();
    }
}

function is_name_valid(string $voornaam, string $achternaam)
{
    if (
        !preg_match(
            "/^([a-zA-Z]{2,}\s[a-zA-Z]{1,}'?-?[a-zA-Z]{2,}\s?([a-zA-Z]{1,})?)/",
            $voornaam
        ) ||
        !preg_match(
            "/^([a-zA-Z]{2,}\s[a-zA-Z]{1,}'?-?[a-zA-Z]{2,}\s?([a-zA-Z]{1,})?)/",
            $achternaam
        )
    ) {
        return true;
    } else {
        return false;
    }
}

function is_telefoon_valid(string $telefoon)
{
    $pattern = '/^(?:(?:\+31|0)(?:[1-9]|3[0-9])|(?:\+31|0)6)-?\d{8}$/';

    if (!preg_match($pattern, $telefoon)) {
        return true; // Het phone number is valid
    } else {
        return false; // the phone number is invalid
    }
}

function is_email_valid(string $email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_email_registered(string $email)
{
    global $db;
    $sql = 'SELECT email FROM users WHERE email=:email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $Result = $stmt->rowCount();

    if ($Result == 1) {
        return true;
    } else {
        return false;
    }
}

function is_username_taken(string $username)
{
    global $db;
    $sql = 'SELECT username FROM users WHERE username=:username';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $Result = $stmt->rowCount();

    if ($Result == 1) {
        return true;
    } else {
        return false;
    }
}

function is_password_not_matched(string $password, string $cpassword)
{
    if ($password !== $cpassword) {
        return true;
    } else {
        return false;
    }
}
function get_users_info(object $db, int $user_id = null)
{
    $query = 'SELECT * FROM users';
    if (!is_null($user_id)) {
        $query .= ' WHERE id = :user_id';
    }
    $stmt = $db->prepare($query);
    if (!is_null($user_id)) {
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    }
    $stmt->execute();

    if (!is_null($user_id)) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $result;
}

function user_logout(object $db)
{
    try {
        if (isset($_SESSION['user_id'])) {
            session_destroy();
        }
    } catch (Exception $e) {
        return 'Logout failed: ' . $e->getMessage();
    }
    return true;
}