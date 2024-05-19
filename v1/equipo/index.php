<?php
include_once '../version1.php';

//parametros
$existeId = false;
$valorId = 0;
$existeAccion = false;
$valorAccion = 0;


if (count($_parametros) > 0) {
    foreach ($_parametros as $p) {
        if (strpos($p, 'id') !== false) {
            $existeId = true;
            $valorId = explode('=', $p)[1];
        }
        if (strpos($p, 'accion') !== false) {
            $existeAccion = true;
            $valorAccion = explode('=', $p)[1];
        }
    }
}

if ($_version == 'v1') {
    if ($_mantenedor == 'equipo') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $lista = $control->getAll();
                    http_response_code(200);
                    echo json_encode(['data' => $lista]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion get']);
                }
                break;
            case 'POST':
                if ($_header == $_token_post) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input", true));
                    //trae el getall y corrobora q el dato a insertar no exista. Si existe. salta al error 409. 
                    //Si no existe, entonces se intenta insertar
                    $respuesta = $control->postNuevo($$_tipo, $_texto, $_accion);
                    if ($respuesta) {
                        http_response_code(201);
                        echo json_encode(['data' => $respuesta]);
                    } else {
                        http_response_code(409);
                        echo json_encode(['error' => 'Error: El dato ya existe']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'Error: No tiene autorizacion post']);
                }
                break;
            case 'PATCH':
                if ($_header == $_token_patch) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    if ($existeId && $existeAccion) {
                        if ($valorAccion == 'apagar') {
                            $respuesta = $control->patchEncenderApagar($valorId, 'false');
                            http_response_code(200);
                            echo json_encode(['data' => $respuesta]);
                        } else if ($valorAccion == 'encender') {
                            $respuesta = $control->patchEncenderApagar($valorId, 'true');
                            http_response_code(200);
                            echo json_encode(['data' => $respuesta]);
                        } else {
                            echo 'Error: No se pudo cambiar el estado.';
                        }
                    } else {
                        echo 'Error: faltan parametros.';
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion patch']);
                }
                break;
            case 'PUT':
                if ($_header == $_token_put) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $body = json_decode(file_get_contents("php://input", true));
                    $control = new Controlador();
                    $respuesta = $control->putTextoById($body->nombre, $body->id);
                    http_response_code(200);
                    echo json_encode(['data' => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion put']);
                }
                break;
            case 'DELETE':
                if ($_header == $_token_delete) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $respuesta = $control->deleteById($valorId);
                    http_response_code(200);
                    echo json_encode(['data' => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion put']);
                }
                break;
            default:
                break;
        }
    }
}
