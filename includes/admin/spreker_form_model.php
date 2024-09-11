<?php
// Zorg ervoor dat de sessie gestart is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Functie om een nieuwe spreker toe te voegen
function get_spreker(object $db, string $spreker_naam, string $pr_start_tijd, string $pr_eind_tijd, string $pr_beschrijving, string $evenement_id, string $user_id) {
    $query = 'INSERT INTO presentaties (spreker_naam, pr_start_tijd, pr_eind_tijd, pr_beschrijving, evenement_id_fk, user_id_fk) VALUES (:spreker_naam, :pr_start_tijd, :pr_eind_tijd, :pr_beschrijving, :evenement_id_fk, :user_id_fk);';
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        return ("Error in prepare statement: " . $db->errorInfo()[2]);
    }
    
    $stmt->bindParam(':spreker_naam', $spreker_naam);
    $stmt->bindParam(':pr_start_tijd', $pr_start_tijd);
    $stmt->bindParam(':pr_eind_tijd', $pr_eind_tijd);
    $stmt->bindParam(':pr_beschrijving', $pr_beschrijving);
    $stmt->bindParam(':evenement_id_fk', $evenement_id);
    $stmt->bindParam(':user_id_fk', $user_id);
    
    if (!$stmt->execute()) {
        return ("Error in execute statement: " . $stmt->errorInfo()[2]);
    }
    
    $lastInsertedId = get_last_inserted_id($db);
    return $lastInsertedId;
}

// Functie om een spreker te updaten
function update_spreker(object $db, string $spreker_naam, string $pr_start_tijd, string $pr_eind_tijd, string $pr_beschrijving, int $id) {
    $query = 'UPDATE presentaties SET spreker_naam = :spreker_naam, pr_start_tijd = :pr_start_tijd, pr_eind_tijd = :pr_eind_tijd, pr_beschrijving = :pr_beschrijving WHERE id= :id';
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        return ("Error in prepare statement: " . $db->errorInfo()[2]);
    }
    
    $stmt->bindParam(':spreker_naam', $spreker_naam, PDO::PARAM_STR);
    $stmt->bindParam(':pr_start_tijd', $pr_start_tijd, PDO::PARAM_STR);
    $stmt->bindParam(':pr_eind_tijd', $pr_eind_tijd, PDO::PARAM_STR);
    $stmt->bindParam(':pr_beschrijving', $pr_beschrijving, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    if ($stmt->errorCode() !== '00000') {
        $errors = $stmt->errorInfo();
        return ['error' => 'Error updating presentatie: ' . implode(' ', $errors)];
    }
    
    return ['success' => 'Presentatie successfully updated'];
}

// Functie om spreker reservering gegevens op te halen
function spreker_reservering_data(object $db) {
    // Controleer of de user_id in de sessie is ingesteld
    if (!isset($_SESSION['user_id'])) {
        return ['error' => 'User ID is not set in the session.'];
    }
    
    $spreker_id = $_SESSION['user_id'];
    
    $query = "SELECT presentaties.*, evenement.evenement_naam
              FROM presentaties
              JOIN evenement ON presentaties.evenement_id_fk = evenement.id
              WHERE presentaties.user_id_fk = :spreker_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':spreker_id', $spreker_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om alle spreker data op te halen
function get_spreker_data(object $db) {
    $query = "SELECT * FROM presentaties";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om specifieke spreker presentaties op te halen
function get_spreker_presentatie(object $db, string $id) {
    $query = 'SELECT presentaties.*, evenement.id, evenement_naam
              FROM presentaties
              JOIN evenement ON presentaties.evenement_id_fk = evenement.id';
    
    if (!is_null($id)) {
        $query .= ' WHERE presentaties.id = :id';
    }
    
    $fetch = "fetchAll";
    $stmt = $db->prepare($query);
    
    if (!is_null($id)) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $fetch = "fetch";
    }
    
    $stmt->execute();
    $result = $stmt->$fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Functie om een presentatie te verwijderen
function delete_presentatie(object $db, int $id) {
    $stmt = $db->prepare("DELETE FROM presentaties WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    if ($stmt->errorCode() !== '00000') {
        $errors = $stmt->errorInfo();
        return ['error' => 'Error deleting presentatie: ' . implode(' ', $errors)];
    }
    
    $stmt->closeCursor();    
}
?>
