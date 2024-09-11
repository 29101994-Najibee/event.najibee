<?php
function set_locatie_event(
    object $db,
    string $locatie_naam,
    string $straat,
    string $huisnummer,
    string $toevoeging,
    string $postcode,
    string $plaats,
    int $beschikbaarheid,
    int $capaciteit
) {
    $query =
        'INSERT INTO locatie (locatie_naam, straat, huisnummer, toevoeging, postcode, plaats, beschikbaarheid, capaciteit) VALUES (:locatie_naam, :straat, :huisnummer, :toevoeging, :postcode, :plaats , :beschikbaarheid , :capaciteit);';
    $stmt = $db->prepare($query);

    if (!$stmt) {
        // Handle error, e.g., log or return an error message
        die('Error in prepare statement: ' . $db->errorInfo()[2]);
    }

    $stmt->bindParam(':locatie_naam', $locatie_naam);
    $stmt->bindParam(':straat', $straat);
    $stmt->bindParam(':huisnummer', $huisnummer);
    $stmt->bindParam(':toevoeging', $toevoeging);
    $stmt->bindParam(':postcode', $postcode);
    $stmt->bindParam(':plaats', $plaats);
    $stmt->bindParam(':beschikbaarheid', $beschikbaarheid);
    $stmt->bindParam(':capaciteit', $capaciteit);

    if (!$stmt->execute()) {
        return false;
    }
}

function get_locatie(object $db, string $locatie_id = null)
{
    $query = 'SELECT * FROM locatie';
    if (!is_null($locatie_id)) {
        $query .= ' WHERE id = :locatie_id;';
    }
    $fetch = 'fetchAll';
    $stmt = $db->prepare($query);
    if (!is_null($locatie_id)) {
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
        $fetch = 'fetch';
    }
    $stmt->execute();

    $resulte = $stmt->$fetch(PDO::FETCH_ASSOC);
    return $resulte;
}