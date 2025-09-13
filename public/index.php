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
    $sms_consent  = $request->request->get('sms_consent', '');

    $config = require '../src/config.php';
    $dbUser = $config['db_user'];
    $dbPass = $config['db_pass'];
    $dbName = $config['db_name'];
    $dbHost = $config['db_host'];
    $dbTimeZone = $config['db_timezone'];
    date_default_timezone_set($dbTimeZone);
    DB::setup("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);

    $persona = DB::dispense('registers');
    $persona->created_at = date('Y-m-d H:i:s', time());
    $persona->first_name = $first_name;
    $persona->last_name = $last_name;
    $persona->email = $email;
    $persona->phone = $phone;
    $persona->sms_consent = filter_var($sms_consent, FILTER_VALIDATE_BOOLEAN);
    $id = DB::store($persona);

    $nombre = "{$first_name} {$last_name}";

    // Aquí puedes procesar los datos (validarlos, guardarlos, etc.)

    $contenido = "
        <h1>Datos recibidos</h1>
        <p><strong>Nombre:</strong> {$nombre}</p>
        <p><strong>Email:</strong> {$email}</p>
        <a href=\"/\">Volver</a>
    ";

    $response = new Response($contenido);
    $response->send();
    exit;
}
else if ($request->isMethod('GET')){
    $formulario = file_get_contents('../src/policy.html');
    $response = new Response($formulario);
    $response->send();
}