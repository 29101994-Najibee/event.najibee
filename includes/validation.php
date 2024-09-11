<?php

function definePermissions()
{
    $permissions = selectQuery('SELECT bit, user_permission FROM permission');
    $all_permissions = 0;
    foreach ($permissions as $p) {
        define($p['user_permission'], $p['bit']);
        $all_permissions |= $p['bit'];
    }

    return $all_permissions;
}
//define
// $all_permissions = definePermissions();
// define('USER_TYPE_BEHEERDER', $all_permissions);
// define('USER_TYPE_DEELNEMER', READ_POST | WRITE_POST);
// define('USER_TYPE_SPREKER', READ_POST | WRITE_POST | EDIT_POST);
// define(
//     'USER_TYPE_COORDINATOR',
//     READ_POST | WRITE_POST | EDIT_POST | DELETE_POST | READ_USER | EDIT_USER
// );
// define('USER_TYPE_VISITOR', 0);
// function getUserPermission()
// {
//     if (isset($_SESSION['user_permission'])) {
//         return $_SESSION['user_permission'];
//     }
//     return 0;
// }
// $user_permission = getUserPermission();
function check_permission($user_permission, $check_permission)
{
    return ($user_permission & $check_permission) === $check_permission;
}

function CheckAccessRole($AccessRole)
{
    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['user_type'] !== $AccessRole
    ) {
        header('Location: ../login.php');
        exit();
    }
    foreach ($AccessRole as $role) {
        if ($_SESSION['user_type'] === $role) {
            return true;
        }
    }
    header('Location: ../login.php');
    exit();
}