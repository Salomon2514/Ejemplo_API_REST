<?PHP  

require("Router/ProductosController.php");

//Método header de PHP - Acceso de Cualquier origen (All)
header("Access-Control-Allow-Origin:*");
header("Content-type:application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

$MyProductoController = new ProductoController();

switch ($method){

	case 'POST':
	//Crear un Producto
	$MyProductoController->crearProducto();

	break;

	case 'GET':
	
	$MyProductoController->listarProductos();

	break;

	default:
	http_response_code(404);
	echo json_encode(["success" => false, "message" => "404 Not Found - El servidor no pudo encontrar el recurso solicitado."]);
}


/*
break;
case 'GET':
$MyProductoController->leerProductos();

case 'PUT':
$MyProductoController->ActualizarProducto();
*/