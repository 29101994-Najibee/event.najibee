<?php
function set_spreker_data(
    object $db,
    string $spreker_naam,
    string $evenement_id,
    string $pr_start_tijd,
    string $pr_eind_tijd,
    string $pr_beschrijving,
    string $user_id
) {
    $result = [];

    try {
        $errors = [];

        if (
            is_input_empty([
                $spreker_naam,
                $pr_start_tijd,
                $pr_eind_tijd,
                $pr_beschrijving,
                $evenement_id,
                $user_id,
            ])
        ) {
            $errors['empty_input'] = 'vul alle velden in!';
        }
        if ($errors) {
            $result['error'] = [];
            $result['error']['errors_spreker'] = $errors;

            $eventSpreker = [
                'spreker_naam' => $spreker_naam,
                'evenement_id' => $evenement_id,
                'pr_start_tijd' => $pr_start_tijd,
                'pr_eind_tijd' => $pr_eind_tijd,
                'pr_beschrijving' => $pr_beschrijving,

                'user_id' => $user_id,
            ];

            $result['error']['spreker_data'] = $eventSpreker;
            return false;
        } else {
            $result = get_spreker(
                $db,
                $spreker_naam,
                $pr_start_tijd,
                $pr_eind_tijd,
                $pr_beschrijving,
                $evenement_id,
                $user_id
            );

            header('location: https://najibee.evenementenregistratie/spreker');
            $db = null;
            $stmt = null;
            return true;
        }
    } catch (PDOException $e) {
        $result = ['error' => 'Insert is niet gelukt: ' . $e->getMessage()];
    }
    return $result;
}

function get_alle_presentatie(object $db)
{
    return get_spreker_presentatie($db, $presentatie_id);
}

function update_spreker_data(
    object $db,
    string $spreker_naam,
    string $pr_start_tijd,
    string $pr_eind_tijd,
    string $pr_beschrijving,
    string $evenement_id,
    string $user_id,
    string $id
) {
    $result = [];

    try {
        $errors = [];

        if (
            is_input_empty([
                $spreker_naam,
                $pr_start_tijd,
                $pr_eind_tijd,
                $pr_beschrijving,
                $evenement_id,
                $user_id,
            ])
        ) {
            $errors['empty_input'] = 'vul alle velden in!';
        }
        if ($errors) {
            $result['error'] = [];
            $result['error']['errors_spreker'] = $errors;

            $eventSpreker = [
                'spreker_naam' => $spreker_naam,
                'updateStart_tijd' => $pr_start_tijd,
                'updateEind_tijd' => $pr_eind_tijd,
                'updateBeschrijving' => $pr_beschrijving,
                'updateEvenement_naam' => $evenement_id,
                'user_id' => $user_id,
            ];

            $result['error']['spreker_data'] = $eventSpreker;
        } else {
            $result = update_spreker(
                $db,
                $spreker_naam,
                $pr_start_tijd,
                $pr_eind_tijd,
                $pr_beschrijving,
                $id
            );
            return true;
        }
    } catch (PDOException $e) {
        $result = ['error' => 'Insert is niet gelukt: ' . $e->getMessage()];
    }
    return $result;
}

function delete_spreker_presentatie(object $db, string $id)
{
    try {
        delete_presentatie($db, $id);

        exit();
    } catch (PDOException $e) {
        return false;
    }
}