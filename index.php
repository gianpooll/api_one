<?php

$host = "localhost";
$usuario = "root";
$password = "";
$base = "prueba_api";

$conexion = new mysqli($host, $usuario, $password, $base);

if ($conexion->connect_error) {
		die("Error de conexión: " . $conexion->connect_error);
}

header('Content-Type: apllication/json');

$metodo = $_SERVER['REQUEST_METHOD'];

#print_r($_SERVER['PATH_INFO']);
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
#print_r($path);
$paths = explode('/', $path);
#print_r($paths);
$id = ($path!=='/') ? end($paths) : null;
#print_r($id);

switch ($metodo){
	case 'GET':
		obtenerRegistros($conexion);
		break;
	case 'POST':
		insertarRegistros($conexion);
		break;
	case 'PUT':
		echo "Actualización de registros - UPDATE";
		break;
	case 'DELETE':
		eliminarRegistros($conexion, $id);
		break;
	default:
		echo "Método no soportado";
		break;
}

function obtenerRegistros($conexion){
	$sql = "SELECT * FROM usuarios";
	$resultado = $conexion->query($sql);
	$datos = array();
	if ($resultado->num_rows > 0) {
		while ($fila = $resultado->fetch_assoc()) {
			$datos[] = $fila;
		}
	}
	echo json_encode($datos);
}

function insertarRegistros($conexion){
	$datos = json_decode(file_get_contents('php://input'), true);
	$nombre = $datos['nombre'];
	$usuario = $datos['usuario'];
	$pass = $datos['pass'];

	$sql = "INSERT INTO usuarios (nombre_usuario, usuario_ingreso, pass_ingreso) VALUES ('$nombre', '$usuario', '$pass')";
	$resultado = $conexion->query($sql);

	if($resultado){
		$dato['id'] = $conexion->insert_id;
		echo json_encode(array('mensaje' => 'Registro insertado correctamente con el id: ' . $dato['id']));
	}else{
		echo json_encode(array('mensaje' => 'Error al insertar el registro'));
	}
		
}

function eliminarRegistros($conexion, $id){
	echo "Eliminación de registros. ID: " . $id;
}