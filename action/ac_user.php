<?php

require_once '../includes/include.user.php';
header("Content-type: application/json");

$action = in('a', '');
if ($action == 'register') {
        $voornaam = in('voornaam', '');
        $achternaam = in('achternaam', '');
        $telefoon = in('telefoon', '');
        $email = in('email', '');
        $username = in('username', '');
        $password = in('password', '');
        $cpassword = in('cpassword', '');
        $user_type = in('user_type', '');
       $result = user_register($db, $voornaam, $achternaam, $telefoon, $email, $username, $password, $cpassword ,$user_permission ,$user_type );
if($result === true){
        header('Location:../login');
}else{
        header('Location:../register');
}

}elseif ($action == 'login') {
        $username = in('username', '');
        $password = in('password', '');
      
        $result = user_login($db,$username, $password);
        echo json_encode($result);
}elseif ($action == 'logout') {
   $res = user_logout($db);   
   echo json_encode($res);
}