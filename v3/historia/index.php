<?php
include_once '../version3.php';

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

if ($_version == 'v3') {
    if ($_mantenedor == 'historia') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    
                    $control = new Controlador();
                    $data = $control->getByID($valorId);
                    
                    if ($data) {
                        http_response_code(200);
                        echo json_encode(['data' => $data]);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'No data found for the given ID.']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'Error: No tiene autorización GET.']);
                }
                break;
            case 'POST':
                if ($_header == $_token_post) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input"));
                    $respuesta = $control->postNuevo($body, $body);
                    if ($respuesta) {
                        http_response_code(201);
                        echo json_encode(['data' => $respuesta]);
                    } else {
                        http_response_code(409);
                        echo json_encode(['error' => 'Error: El dato ya existe']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'Error: No tiene autorización POST']);
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
                            echo 'Error: No existe otro estado.';
                        }
                    } else {
                        echo 'Error: faltan parámetros.';
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización PATCH']);
                }
                break;
            case 'PUT':
                if ($_header == $_token_put) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $body = json_decode(file_get_contents("php://input", true));
                    $control = new Controlador();
                    $respuesta = $control->putTextoById($body, $body);
                    http_response_code(200);
                    echo json_encode(['data' => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'No tiene autorización PUT']);
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
                    echo json_encode(['error' => 'No tiene autorización DELETE']);
                }
                break;
            default:
                break;
        }
    }
}
?>