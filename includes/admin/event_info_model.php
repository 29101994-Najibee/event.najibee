<?php
    // get category evenement using foriegn key 
    function get_category_by_event(object $db, int $evenement_id=null ){ 
    
    $query = 'SELECT c.id, c.`categorie_naam` 
    FROM categorie AS c
    INNER JOIN evenement_categorie AS ec ON ec.`categorie_id_fk` = c.`id`
    WHERE ec.`evenement_id_fk` =:id';
    
    
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $evenement_id]);

    $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
    }
 
    
    // get evenement locatie using foriegn key 
    function get_locatie_by_event(object $db, int $evenement_id =null) {
    try {
        $query = "SELECT l.id, l.locatie_naam ,l.straat, l.huisnummer ,l.toevoeging, l.postcode, l.plaats , l.beschikbaarheid
                  FROM locatie AS l
                  INNER JOIN evenement AS e ON e.locatie_id_fk = l.id
                  WHERE e.id = :evenement_id";

        $statement = $db->prepare($query);
        $statement->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
        $statement->execute();

        $result= $statement->fetch(PDO::FETCH_ASSOC);
      
        return $result;
    } catch (PDOException $e) {
       
       return false;
    }
}
 function get_zaal_by_event(object $db, int $evenement_id ){
    try {
       $query = "SELECT z.id, z.`zaal_naam` 
       FROM zaal AS z
       INNER JOIN locatie AS l ON z.`locatie_id_fk` = l.`id`
       INNER JOIN evenement AS e ON e.locatie_id_fk = l.`id`
       WHERE e.`id`= :evenement_id ";

       $statement = $db->prepare($query);
       $statement->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
       $statement->execute();
       $result= $statement->fetchAll(PDO::FETCH_ASSOC);
   
      return $result;
    } catch (PDOException $e) {
      return false;
    }
 }