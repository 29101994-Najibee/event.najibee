<?php

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

function set_categorie_data(object $db, string $categorie_naam) {
    try {
        $errors = [];
        
        if (is_input_empty([$categorie_naam ])) {
            $errors['empty_input'] = 'vul alle velden in!';
        }  

        if( is_categorie_naam_taken($categorie_naam)){
                $errors['categorie_naam_taken'] = 'Deze categorie naam bestaat al!'; 
        }
        if ($errors) {
            $_SESSION['errors_categorie'] = $errors;
           
            $eventCetegorie = [
                'categorie_naam' => $categorie_naam,
             
            ];
            $_SESSION['event_categorie'] = $eventCategorie;
           return false;
            
            die();
        } else {
          add_categorie($db, $categorie_naam);
          return true;
        }
    } catch (PDOException $e) {
        return ["error" => 'Insert is niet gelukt: ' . $e->getMessage()];
    }

}

    function set_delete_categorie_data(object $db, string $id){
      
                try {
                   
                    $result = delete_categorie($db, $id);
                 return $result;
                } catch (PDOException $e) {
                   return false;
                }
            
        }

function set_update_categorie_data(object $db, string $categorie_naam , int $categorie_id){
 
        try {
            $errors = [];

            if (is_input_empty([$categorie_naam])) {
                $errors['empty_input'] = 'vul alle velden in!';
            }  

            if(is_categorie_naam_taken( $categorie_naam)){
                    $errors['categorie_taken'] = 'Deze categorie naam bestaat al!'; 
            }
            if ($errors) {
                $res["error"] = [];
                $res["error"]['errors_categorie'] = $errors;

                $eventCategorie = [
                    'categorie_naam' => $categorie_naam,
                ];
                
                $res["error"]['categorie_data'] = $eventCategorie;
                return $res;

                
            } else {
               return update_categorie( $db, $categorie_naam, $categorie_id); 
            
            }
        } catch (PDOException $e) {
            // Print or log the specific error message for debugging
            $res = ["error" => 'Insert is niet gelukt: ' . $e->getMessage()];
        }
       
    } 

  