<?php

function set_event(
    object $db,
    string $evenement_naam,
    string $datum,
    string $start_tijd,
    string $eind_tijd,
    string $beschrijving,
    int $status,
    int $locatie_id_fk
) {
    $query =
        'INSERT INTO evenement ( evenement_naam, datum, start_tijd, eind_tijd, beschrijving, status, locatie_id_fk) VALUES (:evenement_naam, :datum, :start_tijd, :eind_tijd, :beschrijving, :status , :locatie_id_fk);';
    $stmt = $db->prepare($query);

    if (!$stmt) {
        // Handle error, e.g., log or return an error message
        die('Error in prepare statement: ' . $db->errorInfo()[2]);
    }

    $stmt->bindParam(':evenement_naam', $evenement_naam);
    $stmt->bindParam(':datum', $datum);
    $stmt->bindParam(':start_tijd', $start_tijd);
    $stmt->bindParam(':eind_tijd', $eind_tijd);
    $stmt->bindParam(':beschrijving', $beschrijving);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':locatie_id_fk', $locatie_id_fk);

    if (!$stmt->execute()) {
        die('Error in execute statement: ' . $stmt->errorInfo()[2]);
    }
    // Get the last inserted ID
    $lastInsertedId = get_last_inserted_id($db);

    return $lastInsertedId;
}

function set_evenement_categorie(
    object $db,
    int $evenement_id_fk,
    int $categorie_id_fk
) {
    $query =
        'INSERT INTO evenement_categorie (evenement_id_fk, categorie_id_fk) VALUES (:evenement_id, :categorie_id)';

    $stmt = $db->prepare($query);

    if (!$stmt) {
        // Handle error, e.g., log or return an error message
        die('Error in prepare statement: ' . $db->errorInfo()[2]);
    }

    $stmt->bindParam(':evenement_id', $evenement_id_fk);
    $stmt->bindParam(':categorie_id', $categorie_id_fk);

    if (!$stmt->execute()) {
        // Handle error, e.g., log or return an error message
        die('Error in execute statement: ' . $stmt->errorInfo()[2]);
    }
    // Get the last inserted ID
    $lastInsertedId = get_last_inserted_id($db);

    // You can use $lastInsertedId as needed in your application

    return $lastInsertedId;
}

function get_locatie_id(object $db, int $locatie_id)
{
    $query = 'SELECT * FROM locatie';
    if (!is_null($locatie_id)) {
        $query .= ' WHERE id = :locatie_id;';
    }

    $stmt = $db->prepare($query);

    if (!is_null($locatie_id)) {
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_categorie_id(object $db, int $categorie_id)
{
    $query = 'SELECT * FROM categorie';
    if (!is_null($categorie_id)) {
        $query .= ' WHERE id = :categorie_id;';
    }

    $stmt = $db->prepare($query);

    if (!is_null($categorie_id)) {
        $stmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// make an function for the evenement get data using event id
function get_event(object $db, string $evenement_id = null)
{
    $query = 'SELECT * FROM evenement';
    if (!is_null($evenement_id)) {
        $query .= ' WHERE id = :evenement_id;';
    }

    $stmt = $db->prepare($query);

    if (!is_null($evenement_id)) {
        $stmt->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    // here make kind of if statement if retrun is_null fetchAll : else return !is_null fetch
    return is_null($evenement_id)
        ? $stmt->fetchAll(PDO::FETCH_ASSOC)
        : $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_event_by_user(object $db, string $user_id)
{
    $query = 'SELECT * FROM evenement WHERE user_id = id';
    if (!is_null($user_id)) {
        $query .= 'WHERE user_id = :user_id';
    }
    $stmt = $db->prepare($query);
    if (!is_null($user_id)) {
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    return is_null($evenement_id)
        ? $stmt->fetchAll(PDO::FETCH_ASSOC)
        : $stmt->fetch(PDO::FETCH_ASSOC);
}

function count_event(object $db)
{
    $query = 'SELECT COUNT(*)AS total FROM evenement';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function count_deelnemer(object $db)
{
    $query =
        "SELECT user_type, COUNT(*) AS total_deelnemer FROM users WHERE user_type = 'deelnemer' GROUP BY user_type";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}