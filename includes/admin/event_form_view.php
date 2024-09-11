<?php
function check_event_errors()
{
    if (isset($_SESSION['errors_event'])) {
        $errors = $_SESSION['errors_event'];
        $result = '<br>';
        foreach ($errors as $error) {
            // echo '<div class="error_form">';
            $result .= '<p class="errors_form">' . $error . '</p>';
            // echo '</div>';
        }
        unset($_SESSION['errors_event']);
        return $result;
    } elseif (isset($_GET['evenement']) && $_GET['evenement']) {
        // echo '<div class="error_form">';
        return '<p class="form_success">"Evenement is geregistreerd"</p>';
        // echo '</div>';
    }
}
function check_categorie_errors()
{
    if (isset($_SESSION['errors_categorie'])) {
        $errors = $_SESSION['errors_categorie'];
        $result = '<br>';
        foreach ($errors as $error) {
            // echo '<div class="error_form">';
            $result .= '<p class="errors_form">' . $error . '</p>';
            // echo '</div>';
        }
        unset($_SESSION['errors_categorie']);
        return $result;
    } elseif (isset($_GET['categorie']) && $_GET['categorie']) {
        // echo '<div class="error_form">';
        return '<p class="form_success">"categorie is geregistreerd"</p>';
        // echo '</div>';
    }
}
function check_locatie_errors()
{
    if (isset($_SESSION['errors_locatie'])) {
        $errors = $_SESSION['errors_locatie'];
        $result = '<br>';
        foreach ($errors as $error) {
            echo '<div class="error_form">';
            $result .= '<p class="errors_form">' . $error . '</p>';
            echo '</div>';
        }
        unset($_SESSION['errors_locatie']);
        return $result;
    } elseif (isset($_GET['locatie']) && $_GET['locatie']) {
        // echo '<div class="error_form">';
        return '<p class="form_success">"Locatie is geregistreerd"</p>';
        // echo '</div>';
    }
}

function check_zaal_errors()
{
    if (isset($_SESSION['errors_zaal'])) {
        $errors = $_SESSION['errors_zaal'];
        $result = '<br>';
        foreach ($errors as $error) {
            echo '<div class="error_form">';
            $result .= '<p class="errors_form">' . $error . '</p>';
            echo '</div>';
        }
        unset($_SESSION['errors_zaal']);
        return $result;
    } elseif (isset($_GET['zaal']) && $_GET['zaal']) {
        // echo '<div class="error_form">';
        return '<p class="form_success">"Zaal is geregistreerd"</p>';
        // echo '</div>';
    }
}
function check_spreker_errors()
{
    if (isset($_SESSION['errors_spreker'])) {
        $errors = $_SESSION['errors_spreker'];
        $result = '<br>';
        foreach ($errors as $error) {
            // echo '<div class="error_form">';
            $result .= '<p class="errors_form">' . $error . '</p>';
            // echo '</div>';
        }
        unset($_SESSION['errors_spreker']);
        return $result;
    } elseif (isset($_GET['reserveren']) && $_GET['reserveren']) {
        // echo '<div class="error_form">';
        return '<p class="form_success">"Spereker is geregistreerd"</p>';
        // echo '</div>';
    }
}

?>