<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function get_event_info(object $db, int $evenement_id)
{
    $event = [];

    try {
        // make array key for evenement categories
        $event = get_event($db, $evenement_id);
        $categories = get_category_by_event($db, $evenement_id);
        $locaties = get_locatie_by_event($db, $evenement_id); // zaal function + location function in get_locatie_by_event
        $zalen = get_zaal_by_event($db, $evenement_id);
        $event['categories'] = $categories;
        $event['locaties'] = $locaties;
        $event['zalen'] = $zalen;
    } catch (PDOException $e) {
        return ['error' => 'Insert is niet gelukt: ' . $e->getMessage()];
    }

    return $event;
}