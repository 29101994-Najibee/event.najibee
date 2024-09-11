<?php

require_once '../includes/admin/admin_include.php';
header("Content-type: application/json");
$action = in('a', '');

if ($action == 'reservering' && check_user_session("beheerder") ) {
    // check event reservering data value
   
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; 
        $evenement_naam = in('evenement_naam', '');
        $start_datum = in('start_datum', '');
        $eind_datum = in('eind_datum', '');
        $start_tijd = in('start_tijd', '');
        $eind_tijd = in('eind_tijd', '');
        $beschrijving = in('beschrijving', ''); 
        $spreker_id_fk = in ('spreker_id', '');
        $evenement_id_fk = in('evenement_id', '');
       
        $result= event_reservering($db, $user_id_fk, $evenement_naam, $start_datum, $eind_datum, $start_tijd, $eind_tijd, $beschrijving, $spreker_id_fk, $evenement_id_fk);
        echo json_encode($result);
    } else {
        
        header('Location:../login');
    }
} elseif ($action == 'checkReseveren' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    check_user_login("coordinator");
}