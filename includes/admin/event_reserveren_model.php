<?php
function set_event_reservering(
    object $db,
    int $user_id_fk,
    string $evenement_naam,
    string $start_datum,
    string $eind_datum,
    string $start_tijd,
    string $eind_tijd,
    string $beschrijving,
    int $spreker_id_fk,
    int $evenement_id_fk
) {
    $query =
        'INSERT INTO `reservering` (evenement_naam, start_datum, eind_datum, start_tijd, eind_tijd, beschrijving, spreker_id_fk, user_id_fk, evenement_id_fk) VALUES (:evenement_naam, :start_datum, :eind_datum, :start_tijd, :eind_tijd, :beschrijving, :spreker_id_fk, :user_id_fk, :evenement_id_fk)';
    $stmt = $db->prepare($query);

    if (!$stmt) {
        // Handle error, e.g., log or return an error message
        return 'Error in prepare statement: ' . $db->errorInfo()[2];
    }

    $stmt->bindParam(':evenement_naam', $evenement_naam);
    $stmt->bindParam(':start_datum', $start_datum);
    $stmt->bindParam(':eind_datum', $eind_datum);
    $stmt->bindParam(':start_tijd', $start_tijd);
    $stmt->bindParam(':eind_tijd', $eind_tijd);
    $stmt->bindParam(':beschrijving', $beschrijving);
    $stmt->bindParam(':spreker_id_fk', $spreker_id_fk);
    $stmt->bindParam(':user_id_fk', $user_id_fk);
    $stmt->bindParam(':evenement_id_fk', $evenement_id_fk);

    echo "Debugging: spreker_id_fk = $spreker_id_fk"; // Controleer de waarde van spreker_id_fk
    if (!$stmt->execute()) {
        // Voeg deze regel toe om de specifieke foutmelding op te halen
        return 'Query failed: ' . implode(' ', $stmt->errorInfo());
    }
    // Return true if the execution is successful
    return true;
}

// get resevering data based on user_id_fk
function my_event_reservering(object $db, string $user_id_fk = null)
{
    $query = 'SELECT * FROM reservering';
    if (!is_null($user_id_fk)) {
        $query .= ' WHERE user_id_fk = :user_id_fk;';
    }

    $stmt = $db->prepare($query);
    if (!is_null($user_id_fk)) {
        $stmt->bindParam(':user_id_fk', $user_id_fk, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}