<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Crear el objeto Request desde la petición actual
$request = Request::createFromGlobals();

if ($request->isMethod('POST')) {
    $phone = $request->request->get('phone', '');
    $sms_consent  = $request->request->get('sms_consent', '');

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