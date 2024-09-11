<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

function is_date_invalid(string $datum)
{
    $currentDate = new DateTime();
    if ($datum > $currentDate) {
        return true;
    } else {
        return false;
    }
}
function check_status(int $status)
{
    if ($status === 1) {
        return true;
    } else {
        return false;
    }
}

function set_event_data(
    object $db,
    string $evenement_naam,
    string $datum,
    string $start_tijd,
    string $eind_tijd,
    string $beschrijving,
    string $status,
    string $locatie_id_fk,
    string $categorie_id_fk,
    string $zaal_id_fk
) {
    try {
        $errors = [];

        if (
            is_input_empty([
                $evenement_naam,
                $datum,
                $start_tijd,
                $eind_tijd,
                $beschrijving,
                $locatie_id_fk,
                $categorie_id_fk,
                $zaal_id_fk,
            ])
        ) {
            $errors['empty_input'] = 'Vul alle velden in!';
        }

        if (is_date_invalid($datum)) {
            $errors['invalid_date'] = 'De datum is ongeldig';
        }

        if ($errors) {
            $_SESSION['errors_event'] = $errors;
            $eventData = [
                'evenement_naam' => $evenement_naam,
                'datum' => $datum,
                'strat_tijd' => $start_tijd,
                'eind_tijd' => $eind_tijd,
                'beschrijving' => $beschrijving,
                'locatie_id' => $locatie_id_fk,
                'categorie_id' => $categorie_id_fk,
                'zaal_id' => $zaal_id_fk,
            ];
            $_SESSION['event_data'] = $eventData;
            return false;
        } else {
            $evenement_id_fk = set_event(
                $db,
                $evenement_naam,
                $datum,
                $start_tijd,
                $eind_tijd,
                $beschrijving,
                $status,
                $locatie_id_fk,
                $categorie_id_fk
            );
            set_evenement_categorie($db, $evenement_id_fk, $categorie_id_fk);

            // Redirect to admin page
            header('Location:../beheerder');
            return true;
        }
    } catch (PDOException $e) {
        return ['error' => 'Insert is niet gelukt: ' . $e->getMessage()];
    }
}

function select_zalen_by_locatie(object $db, int $locatie_id)
{
    try {
        $data = get_zalen_by_locatie($db, $locatie_id);
        return $data;
    } catch (PDOException $e) {
        return false;
    }
}