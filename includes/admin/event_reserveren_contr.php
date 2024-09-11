<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

function is_datum_invalid( string $start_datum){

        $currentDate = new DateTime();
        if ($start_datum > $currentDate) {
                return true;
        }else{
                return false;
        }
}


function event_reservering(object $db,int $user_id_fk,string $evenement_naam, string $start_datum, string $eind_datum,string $start_tijd,string $eind_tijd,string $beschrijving,int $spreker_id_fk, int $evenement_id_fk ){
            
    
                try{
                $errors = [];
    
                if (is_input_empty([$evenement_naam, $start_datum, $eind_datum, $start_tijd, $eind_tijd, $beschrijving, $spreker_id_fk, $evenement_id_fk])) {
                    $errors['empty_input'] = 'Vul alle velden in!';
                }
    
                if (is_datum_invalid($start_datum)) {
                    $errors['invalid_date'] = 'De datum is ongeldig';
                }
    
                if ($errors) {
                    $_SESSION['errors_reservering'] = $errors;
                  
                    $eventReservering = [
                        'user_id'=> $user_id_fk,
                        'evenement_naam' => $evenement_naam,
                        'start_datum' => $start_datum,
                        'eind_datum' => $eind_datum,
                        'start_tijd' => $start_tijd,
                        'eind_tijd' => $eind_tijd,
                        'beschrijving' => $beschrijving,
                        'spreker_id'=> $spreker_id_fk,
                        'evenement_id' => $evenement_id_fk
                    ];
                $_SESSION['reservering_data'] = $eventReservering;
                header('Location:../reservering');
                    return false;
                } else {
            
                    $result= set_event_reservering($db, $user_id_fk, $evenement_naam, $start_datum, $eind_datum, $start_tijd, $eind_tijd,  $beschrijving, $spreker_id_fk, $evenement_id_fk );
    
                    // Redirect to coordiantor page
                    header('location: https://najibee.evenementenregistratie/coordinator');
                    $db = null;
                    $stmt = null;
                    return false;
                }
            } catch (PDOException $e) {
                return ('Query failed:' . $e->getMessage());
            }
            return $result;
          }
        