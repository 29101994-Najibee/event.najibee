<?php
require_once '../includes/admin/admin_include.php';
header("Content-type: application/json");
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = in("id") ;

$action = in('a', '');
if ($action == 'create' && check_user_session("beheerder")){
        $zaal_naam = in('zaal_naam' , '');
        $locatie_id_fk = in('locatie_id','');
        set_zaal_data( $db, $zaal_naam, $locatie_id_fk);
    

}elseif ($action == 'get' && check_user_session("beheerder")){
        if(!isset($id) || $id === "") {
        $alle_zalen = get_alle_zalen( $db);
        echo json_encode($alle_zalen);

        } else {
        $zaal= get_zaal( $db ,$id);
        echo json_encode($zaal);
        }  
     

} elseif($action == 'update' && check_user_session("beheerder")){
        $zaal_naam = in ('zaal_naam' ,'');
        $result = set_update_zaal_data( $db, $zaal_naam ,$id);
        echo json_encode($result);
     
}  elseif($action == 'delete' && $_SERVER['REQUEST_METHOD'] === 'DELETE'){
        check_user_session("beheerder");
     set_delete_zaal_data( $db, $id);
   
     
}