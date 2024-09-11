<?php

require_once '../includes/admin/admin_include.php';
header('Content-type: application/json');
$action = in('a', '');

if ($action == 'evenement' && check_user_session('beheerder')) {
    $evenement_naam = in('evenement_naam', '');
    $datum = in('datum', '');
    $start_tijd = in('start_tijd', '');
    $eind_tijd = in('eind_tijd', '');
    $beschrijving = in('beschrijving', '');
    $status = in('status', 0);
    $locatie_id_fk = in('locatie_id', '');
    $categorie_id_fk = in('categorie_id', '');
    $zaal_id_fk = in('zaal_id', '');
    set_event_data(
        $db,
        $evenement_naam,
        $datum,
        $start_tijd,
        $eind_tijd,
        $beschrijving,
        $status,
        $locatie_id_fk,
        $categorie_id_fk,
        $zaal_id_fk
    );

    $id = in('id');
    $result = select_zalen_by_locatie($db, $id);
    if ($result) {
        echo json_encode($result);
    } else {
        echo "Geen resultaten gevonden voor locatie met ID: $id";
    }
}