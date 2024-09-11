<?php
function get_input()
{
    $input = file_get_contents('php://input');
    $decoded_input = json_decode($input, true);
    return $decoded_input;
}

function view(string $filename, array $data = []): void
{
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    require_once __DIR__ . '/../include' . $filename . '.php';
}

function selectQuery($query, $arguments = [])
{
    global $db;
    $sth = $db->prepare($query);
    $sth->execute($arguments);
    return $sth->fetchAll();
}
function singleSelectQuery($query, $arguments = [])
{
    global $db;
    $sth = $db->prepare($query);
    $sth->execute($arguments);
    return $sth->fetch();
}

function in($name, $default = false)
{
    $data = get_input();
    if ($data !== false && isset($data[$name])) {
        return $data[$name];
    } elseif (isset($_GET[$name])) {
        return $_GET[$name];
    } elseif (isset($_POST[$name])) {
        return $_POST[$name];
    }
    return $default;
}

function redirect($path, $permanent = false)
{
    if (headers_sent() === false) {
        header('Location: ' . $path, true, $permanent === true ? 301 : 302);
    }
    return true;
}

function is_input_empty($array = [])
{
    foreach ($array as $value) {
        if (empty($value)) {
            return true;
        }
    }
    return false;
}

function get_last_inserted_id(object $db)
{
    return $db->lastInsertId();
}
function check_user_session($user_type_required)
{
    return isset($_SESSION['user_id']) &&
        isset($_SESSION['user_type']) &&
        !empty($user_type_required) &&
        $_SESSION['user_type'] === $user_type_required;
}
function check_user_login($user_type_required)
{
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
        header('Location:../reservering');
        throw new Exception('Allow user.');
    } else {
        if (
            !empty($user_type_required) &&
            $_SESSION['user_type'] !== $user_type_required
        ) {
            header('Location:../login');
            throw new Exception('U moet weer inloggen.');
        }
    }
}