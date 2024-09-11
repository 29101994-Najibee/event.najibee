<?php

function check_login_errors() {
        if(isset($_SESSION['errors_login'])){
                $errors = $_SESSION['errors_login'];
              
                $result =  '<br>';
                foreach($errors as $error){
                        $result .='<p class="errors_form">'. $error .'</p>';
                }
              unset($_SESSION['errors_login']);
        return $result;
        }elseif(isset($_GET['login']) && $_GET['login'] === 'success')
        {
                $_SESSION["username"] = $result["username"];
                header ('location:../myaccount');
        }
}
function check_user_type($user_type) {
        $check_user_type = ['beheerder', 'coordinator', 'spreker', 'deelnemer'];
        
        return in_array(strtolower($user_type), $check_user_type);
    }
    
    function check_register_errors() {
        if (isset($_SESSION['errors_register'])) {
            $errors = $_SESSION['errors_register'];
            $result = '<br>';
            foreach ($errors as $error) {
                // echo '<div class="error_form">';
                $result .= '<p class="errors_form">' . $error . '</p>';
                // echo '</div>';
            }
            unset($_SESSION['errors_register']);
    return $result;
        } elseif (isset($_GET['register']) && $_GET['register'] === 'success') {
            // echo '<div class="error_form">';
            return '<p class="form_success">"registeren is gelukt"</p>';
            // echo '</div>';
        }
    }