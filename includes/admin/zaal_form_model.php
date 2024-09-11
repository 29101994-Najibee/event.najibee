<?php
function add_zaal(object $db, string $zaal_naam ,string $locatie_id_fk) {
    $query = 'INSERT INTO zaal (zaal_naam , locatie_id_fk) VALUES (:zaal_naam , :locatie_id_fk);';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':zaal_naam', $zaal_naam, PDO::PARAM_STR);  // Use PDO::PARAM_STR for string
    $stmt->bindParam(':locatie_id_fk', $locatie_id_fk, PDO::PARAM_STR); 
    $stmt->execute();

    return $db->lastInsertId();
}

function get_zaal(object $db, string $zaal_id = null){
    $query = 'SELECT
    z.id,
    z.`zaal_naam`,
    l.`locatie_naam`
  FROM
    zaal AS z
    INNER JOIN locatie AS l
      ON l.`id` = z.`locatie_id_fk`';
    if(!is_null($zaal_id)) {
        $query .= ' WHERE z.id = :zaal_id;';
    }
    $fetch = "fetchAll";
    $stmt = $db->prepare($query);
    
    if(!is_null($zaal_id)) {
        $stmt->bindParam(':zaal_id', $zaal_id, PDO::PARAM_INT);
        $fetch = "fetch";
    } 
    
    $stmt->execute();
    $result = $stmt->$fetch(PDO::FETCH_ASSOC);
    
    return $result;
}

function get_alle_zalen(object $db){
    return get_zaal($db);
}

function is_zaal_naam_taken(string $zaal_naam)
{
    global $db;
    $sql = 'SELECT zaal_naam FROM zaal WHERE zaal_naam =:zaal_naam';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':zaal_naam', $zaal_naam);
    $stmt->execute();
    $Result = $stmt->rowCount();

    if ($Result == 1) {
        return true;
    } else {
        return false;
    }
}

function update_zaal(object $db, string $zaal_naam, int $id) {
        $query = 'UPDATE zaal SET zaal_naam = :zaal_naam WHERE id = :id';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':zaal_naam', $zaal_naam, PDO::PARAM_STR);
       
        $stmt->execute();
    
        // Check for errors
        if ($stmt->errorCode() !== '00000') {
            $errors = $stmt->errorInfo();
            return ['error' => 'Error updating zaal: ' . implode(' ', $errors)];
        }
    
        return ['success' => 'Zaal successfully updated'];
    }
  
    function delete_zaal(object $db, $id) {
        $stmt = $db->prepare("DELETE FROM `zaal` WHERE `id` = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
        $stmt->execute();
    
        if ($stmt->errorCode() !== '00000') {
            $errors = $stmt->errorInfo();
            return ['error' => 'Error deleting zaal: ' . implode(' ', $errors)];
        }
    
        $stmt->closeCursor();
    }
    
    function get_zalen_by_locatie(object $db,int $locatie_id){

        $query = 'SELECT z.id , z.zaal_naam
        FROM zaal AS z
        INNER JOIN locatie AS l ON z.`locatie_id_fk` = l.`id`
        WHERE l.id =:locatie_id';

        $stmt = $db->prepare($query);
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
       
       
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function count_zalen(object $db){
        $query  ="SELECT l.locatie_naam, COUNT(z.id) AS aantal_zalen
        FROM locatie l
        LEFT JOIN zaal z ON l.id = z.locatie_id_fk
        GROUP BY l.id, l.locatie_naam";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      
    }