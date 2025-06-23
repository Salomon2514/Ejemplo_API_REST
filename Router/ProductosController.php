<?PHP
require_once "Modelo/conexion.php";
require_once "Modelo/Productos.php";


Class ProductoController{

	private $db;
	private $conn;
	
	
	
	private $codigo;
	private $producto;
	private $precio;
	private $cantidad;

	Private $myProducto;

	public function __construct(){

		$this->db = new mod_db();
		$this->conn = $this->db->getConexion();
		$this->myProducto = new ObjProductos($this->db);

	}

	public function crearProducto(){

		//es una función que lee el contenido completo de un 
		//archivo y lo devuelve como una cadena de texto
		//Acceso a distintos flujos de E/S
		/*🔧 "php://input" es una entrada especial de PHP que te permite leer el raw body (cuerpo sin procesar) de una petición HTTP. Es útil especialmente cuando usas JSON en APIs REST.*/

		$data = file_get_contents("php://input");

		$data = json_decode($data);
		//var_dump($data);//Confirmación que lo está leyendo
		//exit;
		
		if (!empty($data->codigo) && !empty($data->producto) 
			&& !empty($data->precio) && !empty($data->cantidad)){

			$this->codigo = $data->codigo;
			$this->producto = $data->producto;
			$this->precio = $data->precio;
			$this->cantidad = $data->cantidad;

			$datosProductos = array(
			"codigo" => "$this->codigo",
			"producto" => "$this->producto",
			"precio" => $this->precio,
        	"cantidad" =>$this->cantidad);

        	$this->myProducto->DatosRequeridos($datosProductos);

			if ($this->myProducto->registrarProductos()){
				http_response_code(201);
				echo json_encode(["message" =>"ProductoCreado Exitosamente"]);
			}else {
				//Hubo un problemita
				http_response_code(503);
				echo json_encode(["message" =>"Producto no Creado"]);
			}

		}else {
				http_response_code(400);
				echo json_encode(["message" =>"Producto noooo Creado"]);
		}

	}//fin de public function crearProducto


	
}
?>