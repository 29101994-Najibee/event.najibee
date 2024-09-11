<?php ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php'; // Include Composer autoloader
require_once __DIR__ . '/includes/twig_init.php'; // Include your Twig initialization

// Your other includes
require_once __DIR__ . '/includes/include.php';
include 'includes/include.user.php';
require_once 'includes/admin/admin_include.php';
$db = require_once __DIR__ . '/includes/db_connect.php'; // Include db_connect.php and assign to $db
$url = (string) in('url');
$id = (int) in('id');
$db = getDatabaseConnection();
$routes = [
    'login' => [
        'template' => 'login.html',
        'data' => [
            'errors' => check_login_errors(),
            'user' => get_user_by_session_id($db),
        ],
    ],
    'register' => [
        'template' => 'register.html',
        'data' => [
            'errors' => check_register_errors(),
        ],
    ],

    'logout' => [
        'template' => 'logout.html',
        'data' => [],
    ],
    'home' => [
        'template' => 'home.html',
        'data' => [
            'categorie' => get_categorie($db),
            'locatie' => get_locatie($db),
            'zaal' => get_zaal($db),
            'events' => get_event($db),
        ],
    ],

    'beheerder' => [
        'template' => 'home_admin.html',
        'data' => [
            'events' => get_event($db),
            'count' => count_event($db),
            'deelnemer' => count_deelnemer($db),
            'zalen' => count_zalen($db),
        ],
        // "user_type" => "beheerder"
        'user_type' => 'beheerder',
    ],
    'info' => [
        'template' => 'event_info.html',
        'data' => [
            'event' => get_event_info($db, $id), // pass the hole evenement data
        ],
    ],
    'evenement' => [
        'template' => 'event_form.html',
        'data' => [
            'errors' => check_event_errors(),
            'categorie' => get_categorie($db),
            'locatie' => get_locatie($db),
        ],
        // 'user_type' => 'beheerder',
        // 'user_type' => 'coordinator'
    ],
    'locatie' => [
        'template' => 'locatie_form.html',
        'data' => [
            'errors' => check_event_errors(),
        ],
        // 'user_type' => 'beheerder',
        // 'user_type' => 'coordinator'
    ],
    'categorie' => [
        'template' => 'categorie_form.html',
        'data' => [
            'errors' => check_categorie_errors(),
            'categorie' => get_categorie($db),
        ],
        // 'user_type' => 'beheerder',
        // 'user_type' => 'coordinator'
    ],
    'zaal' => [
        'template' => 'zaal.html',
        'data' => [
            'errors' => check_zaal_errors(),
            'zaal' => get_zaal($db),
            'locatie' => get_locatie($db),
        ],
        // 'user_type' => 'beheerder',
        // 'user_type' => 'coordinator'
    ],

    'myaccount' => [
        'template' => 'user_account.html',
        'data' => [
            'user' => get_user_by_session_id($db),
        ],
        // 'user_type' => 'deelnemer',
    ],
    'reservering' => [
        'template' => 'reservering.html',
        'data' => [
            'errors' => check_event_errors(),
            'events' => get_event($db),
            'user' => get_user_by_session_id($db),
            'spreker' => get_spreker_data($db),
        ],
        //  'user_type' => 'beheerder',
        //  'user_type' => 'coordinator'
    ],
    'spreker' => [
        'template' => 'spreker.html',
        'data' => [
            'user' => get_user_by_session_id($db),
            'presentaties' => spreker_reservering_data($db, $id),
            'events' => get_event($db),
        ],
        // 'user_type' => 'spereker',
    ],
    'presentatie' => [
        'template' => 'presentatie.html',
        'data' => [
            'user' => get_user_by_session_id($db),
            'events' => get_event($db),
        ],
        // 'user_type' => 'spreker'
    ],
    'coordinator' => [
        'template' => 'coordinator.html',
        'data' => [
            'user' => get_user_by_session_id($db),
            'events' => get_event($db),
            'reserverings' => my_event_reservering($db),
        ],
        // 'user_type' => 'coordinator',
    ],
    'contact' => [
        'template' => 'contact.html',
        'data' => [
            'user' => get_user_by_session_id($db),
        ],
        // 'user_type' => 'spreker'
    ],
];

if (array_key_exists($url, $routes) || $url == '') {
    if ($url == '') {
        $url = 'home';
    }

    $route = $routes[$url];

    // Check if 'user_type' is set in $_SESSION before accessing it
    if (
        !isset($route['user_type']) ||
        (isset($_SESSION['user_type']) &&
            isset($route['user_type']) &&
            $route['user_type'] === $_SESSION['user_type'])
    ) {
        echo $twig->render($route['template'], $route['data']);

        //check if uesr_type = access_role have permission for access to the page
        // if (!isset($route["user_type"]) || CheckAccessRole($route["user_type"])) {
        //     echo $twig->render($route['template'], $route['data']);
    } else {
        // Handle the case when 'user_type' is not set in $_SESSION
        echo $twig->render('login.html', ['errors' => 'Login failed']);
    }
} else {
    echo $twig->render('404.html');
}