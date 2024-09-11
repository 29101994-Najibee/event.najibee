<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Adjust the path as needed
require_once __DIR__ . '/session.php'; 
// Ensure ROOT is defined correctly
if (!defined('ROOT')) {
    define('ROOT', __DIR__); // Adjust this to your project's root if needed
}

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;

// Load Twig templates
$loader = new FilesystemLoader(ROOT . '/templates'); // Adjust path as needed
$twig = new Environment($loader, [
    'debug' => true,
]);

// Add extensions and global variables
$twig->addExtension(new DebugExtension());
$twig->addGlobal('session', $_SESSION);

function templateFromString($string, $variables = [])
{
    global $twig;
    $template = $twig->createTemplate($string);
    return $template->render($variables);
}
