<?php
function add_categorie(object $db, string $categorie_naam)
{
    $query = 'INSERT INTO categorie (categorie_naam) VALUES (:categorie_naam);';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':categorie_naam', $categorie_naam, PDO::PARAM_STR); // Use PDO::PARAM_STR for string
    $stmt->execute();

    return $db->lastInsertId();
}

function get_categorie(object $db, string $categorie_id = null)
{
    $query = 'SELECT * FROM categorie';
    if (!is_null($categorie_id)) {
        $query .= ' WHERE id = :categorie_id;';
    }
    $fetch = 'fetchAll';
    $stmt = $db->prepare($query);
    if (!is_null($categorie_id)) {
        $stmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
        $fetch = 'fetch';
    }
    $stmt->execute();

    $resulte = $stmt->$fetch(PDO::FETCH_ASSOC);
    return $resulte;
}

function is_categorie_naam_taken(string $categorie_naam)
{
    global $db;
    $sql =
        'SELECT categorie_naam FROM categorie WHERE categorie_naam=:categorie_naam';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':categorie_naam', $categorie_naam);
    $stmt->execute();
    $Result = $stmt->rowCount();

    if ($Result == 1) {
        return true;
    } else {
        return false;
    }
}

function update_categorie(object $db, string $categorie_naam, int $categorie_id)
{
    $query =
        'UPDATE categorie SET categorie_naam = :categorie_naam WHERE id = :id';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $categorie_id, PDO::PARAM_STR);
    $stmt->bindParam(':categorie_naam', $categorie_naam, PDO::PARAM_STR);

    $stmt->execute();

    // Check for errors
    if ($stmt->errorCode() !== '00000') {
        $errors = $stmt->errorInfo();
        return [
            'error' => 'Error updating categorie: ' . implode(' ', $errors),
        ];
    }

    return ['success' => 'Categorie successfully updated'];
}

function delete_categorie(object $db, $id)
{
    $stmt = $db->prepare('DELETE FROM categorie WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->errorCode() !== '00000') {
        $errors = $stmt->errorInfo();
        return [
            'error' => 'Error deleting categorie: ' . implode(' ', $errors),
        ];
    }

    $stmt->closeCursor(); // Close the cursor to enable the next execution
}