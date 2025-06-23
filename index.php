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

	default:
	http_response_code(405);
	echo json_encode(["success" => false, "message" => "Método no permitido"]);
}


/*
break;
case 'GET':
$MyProductoController->leerProductos();

case 'PUT':
$MyProductoController->ActualizarProducto();
*/