<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

function set_zaal_data(object $db, string $zaal_naam ,string $locatie_id_fk) {
   
    $locatie_id_fk = intval($locatie_id_fk);
      
            try {
                $errors = [];
    
                if (is_input_empty([$zaal_naam , $locatie_id_fk])) {
                    $errors['empty_input'] = 'vul alle velden in!';
                }  

                if(is_zaal_naam_taken( $zaal_naam)){
                        $errors['zaal_naam_taken'] = 'Deze zaal naam bestaat al!'; 
                }
                if ($errors) {
                    $_SESSION['errors_zaal'] = $errors;
                   
                    $eventZaal = [
                        'zaal_naam' => $zaal_naam,
                        'id' => $locatie_id_fk
                    ];
                    $_SESSION['event_zaal'] = $eventZaal;
                    header('Location:../zaal');
                    $db = null;
                    
                   return false;
                } else {
                  add_zaal($db, $zaal_naam , $locatie_id_fk);
                    header('Location:../zaal');
                    return true;
                }
            } catch (PDOException $e) {
                // Print or log the specific error message for debugging
                return ('Insert is niet gelukt: ' . $e->getMessage());
            }
        } 
    
    function set_delete_zaal_data(object $db, string $id){
  
                try {
                   
                   $result = delete_zaal($db, $id);
                    return $result;
                } catch (PDOException $e) {
                    return false;
                }
            
        }

function set_update_zaal_data(object $db, string $zaal_naam, string $id ){
    $result = [];
   
        try {
            $errors = [];
            if (is_input_empty([$zaal_naam])) {
                $errors['empty_input'] = 'vul alle velden in!';
            }  
            if(is_zaal_naam_taken( $zaal_naam)){
                $errors['zaal_taken'] = 'Deze zaal naam bestaat al!'; 
            }
            if ($errors) {
                $result["error"] = [];
                $result["error"]['errors_zaal'] = $errors;
                $eventZaal = [
                    'zaal_naam' => $zaal_naam,
                ];
                $result["error"]['zaal_data'] = $eventZaal;
            } else {
                $result = update_zaal( $db, $zaal_naam, $id); 
                return true;
            }
        } catch (PDOException $e) {
           return false;
        }
        
        return $result ;
    } 
   