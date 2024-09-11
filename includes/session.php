<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'najibee.evenementenregistratie',
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

if (session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}


function regenerate_session_id_loggedin() {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $newSessionId = session_create_id();
        $sessionId = $newSessionId . '_' . $user_id;
        session_regenerate_id(true);
        session_id($sessionId);
        $_SESSION["last_regeneration"] = time();
         session_start();
    }
}

function regeneration_session_id() {
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}

// Your existing logic here...

if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION["last_regeneration"])) {
        regeneration_session_id();
    } else {
        $regenerationInterval = 60 * 60; // 1 uur
        if (time() - $_SESSION["last_regeneration"] >= $regenerationInterval) {
            regenerate_session_id_loggedin();
        }
    }
}  

function restart_session(object $db, array $result) {
    global $db;
    // Destroy the current session
    session_destroy();
      // Create a new session ID based on the user's information
      $newSessionId = session_create_id();
      $sessionId = $newSessionId . '_' . $result['id'];
      session_id($sessionId);
    // Start a new session
    session_start();
    // Regenerate the session ID
    session_regenerate_id(true);

    // Set session variables based on the retrieved user data (if needed)
    $_SESSION["username"] = $result["username"];
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['user_type'] = $result['user_type'];
   
}