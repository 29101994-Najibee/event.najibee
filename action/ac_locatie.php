<?php

require_once '../includes/admin/admin_include.php';
header('Content-type: application/json');
$action = in('a', '');

if ($action == 'locatie' && check_user_session('beheerder')) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $locatie_naam = in('locatie_naam', '');
        $straat = in('straat', '');
        $huisnummer = in('huisnummer', '');
        $toevoeging = in('toevoeging', '');
        $postcode = in('postcode', '');
        $plaats = in('plaats', '');
        $beschikbaarheid = in('beschikbaarheid', 1);
        $capaciteit = in('capaciteit', '');

        set_locatie_data(
            $db,
            $locatie_naam,
            $straat,
            $huisnummer,
            $toevoeging,
            $postcode,
            $plaats,
            $beschikbaarheid,
            $capaciteit
        );
    } else {
        header('Location:../login');
    }
}