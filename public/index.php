<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RedBeanPHP\R as DB;

// Crear el objeto Request desde la petición actual
$request = Request::createFromGlobals();

if ($request->isMethod('POST')) {
    $first_name = $request->request->get('first_name', '');
    $last_name = $request->request->get('last_name', '');
    $email  = $request->request->get('email', '');
    $phone = $request->request->get('phone', '');
    $message = $request->request->get('message', '');
    $sms_consent  = $request->request->get('sms_consent', '');

    $config = require '../src/config.php';
    $dbUser = $config['db_user'];
    $dbPass = $config['db_pass'];
    $dbName = $config['db_name'];
    $dbHost = $config['db_host'];
    $dbTimeZone = $config['db_timezone'];
    date_default_timezone_set($dbTimeZone);
    DB::setup("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);

    $register = DB::dispense('contact');
    $register->created_at = date('Y-m-d H:i:s', time());
    $register->first_name = $first_name;
    $register->last_name = $last_name;
    $register->email = $email;
    $register->phone = $phone;
    $register->sms_consent = filter_var($sms_consent, FILTER_VALIDATE_BOOLEAN);
    $register->message = $message;
    $id = DB::store($register);

    $nombre = "{$first_name} {$last_name}";

    // Aquí puedes procesar los datos (validarlos, guardarlos, etc.)

    $contenido = "
        <h1>Data received</h1>
        <p>thanks for contacting us {$nombre}</p>
        <p>Your submission has been successfully sent.</p>
        <a href=\"/\">Go back</a>
    ";

    $response = new Response($contenido);
    $response->send();
    exit;
}
else if ($request->isMethod('GET')){
    $formulario = file_get_contents('../src/form.html');
    $response = new Response($formulario);
    $response->send();
}