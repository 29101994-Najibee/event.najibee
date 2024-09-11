<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
function set_locatie_data(object $db, string $locatie_naam, string $straat, string $huisnummer, string $toevoeging, string $postcode, string $plaats, int $beschikbaarheid , int $capaciteit){
                try {
                        $errors =[];

                        if (is_input_empty([ $locatie_naam,  $straat,  $huisnummer,  $postcode, $beschikbaarheid ,$capaciteit])) {
                                $errors['empty_input'] = 'vul alle velden in!';
                        }
                

                        if ($errors) {
                                $_SESSION['errors_locatie'] = $errors;
                                $eventLocatie = [
                                        'locatie_naam' => $locatie_naam,
                                        'straat' => $straat,
                                        'huismummer' => $huisnummer,
                                        'toevoeging' => $toevoeging,
                                        'postcode' => $postcode,
                                        'beschikbaarheid' => $beschikbaarheid,
                                        'capaciteit' => $capaciteit
                                       
                                ];
                                
                                $_SESSION['event_locatie'] = $eventLocatie;
                                header('Location:../locatie');
                                $db = null;
                               return false;
                        }else{
                                set_locatie_event($db, $locatie_naam,  $straat, $huisnummer,  $toevoeging ,  $postcode,  $plaats,  $beschikbaarheid ,  $capaciteit);
                                header ('Location:../locatie');
                                return true;
                        }

                } catch (PDOException $e) {
                        return ["error" => 'Insert is niet gelukt: ' . $e->getMessage()];
                }
        }