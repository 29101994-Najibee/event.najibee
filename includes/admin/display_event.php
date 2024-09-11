<?php

function display_event($db, $evenement_id)
{
    $query = 'SELECT * FROM evenement';

    try {
        // Voorbereiden van de query met de databaseverbinding
        $stmt = $db->prepare($query);

        if (!is_null($evenement_id)) {
            $stmt->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            $data_arr = [];
            $i = 1;
            foreach ($results as $row) {
                $data_arr[$i]['id'] = $row['id'];
                $data_arr[$i]['title'] = $row['evenement_naam'];
                $data_arr[$i]['datum'] = date(
                    'Y-m-d',
                    strtotime($row['datum'])
                );
                $data_arr[$i]['color'] = '#' . substr(uniqid(), -6); // 'green'; pass color name
                $i++;
            }
            $data = [
                'status' => true,
                'msg' => 'successfully!',
                'data' => $data_arr,
            ];
        } else {
            $data = [
                'status' => false,
                'msg' => 'No events found!',
            ];
        }

        echo json_encode($data);
    } catch (PDOException $e) {
        $data = [
            'status' => false,
            'msg' => 'Error: ' . $e->getMessage(),
        ];
        echo json_encode($data);
    }
}