<?php
return [
    'paths' => ['api/*', 'storage/*'],  // Ajoutez 'storage/*' pour permettre l'accès aux fichiers dans storage
    'allowed_methods' => ['*'],  // Autoriser toutes les méthodes HTTP (GET, POST, etc.)
    'allowed_origins' => ['*'],  // L'origine de votre app Flutter
    'allowed_headers' => ['*'],  // Autoriser tous les en-têtes
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,  // False si vous n'utilisez pas les cookies
];
