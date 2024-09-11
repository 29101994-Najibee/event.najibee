<?php 
require_once '../includes/admin/admin_include.php';
header("Content-type: application/json");
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = in("id") ;

$action = in('a', '');

if($action =='presentatie'&& $_SERVER['REQUEST_METHOD'] === 'POST' && check_user_session("spreker")){
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $spreker_naam = in('spreker_naam', '');
    $evenement_id = in('evenement_id', '');
    $pr_start_tijd = in('pr_start_tijd', '');
    $pr_eind_tijd = in('pr_eind_tijd', '');
    $pr_beschrijving = in('pr_beschrijving', '');
    set_spreker_data($db ,$spreker_naam ,$evenement_id , $pr_start_tijd , $pr_eind_tijd, $pr_beschrijving, $user_id );
}else{
    header('Location:../login');
    }
    

} elseif($action == 'get' && check_user_session("spreker")){
    
       $presentatie = get_spreker_presentatie( $db,$id);
       echo json_encode($presentatie);
  
}

elseif($action == 'edit' && check_user_session("spreker")){
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; 
        $spreker_naam = in('spreker_naam', '');
        $evenement_id = in('evenement_naam', '');
        $pr_start_tijd = in('pr_start_tijd', '');
        $pr_eind_tijd = in('pr_eind_tijd', '');
        $pr_beschrijving = in('pr_beschrijving', '');
     
        $result = update_spreker_data($db, $spreker_naam, $pr_start_tijd, $pr_eind_tijd, $pr_beschrijving, $evenement_id, $user_id, $id);
    
        echo json_encode($result);
    } else {
        
        header('Location:../login');
    }
    
   
}
elseif($action == 'delete'){
    check_user_session("spreker");
     delete_spreker_presentatie($db, $id);
   
}