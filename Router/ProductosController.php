<?PHP
require_once "Modelo/conexion.php";
require_once "Modelo/ValidarForm.php";
require_once "Modelo/Productos.php";





Class ProductoController{

	private $db;
	private $conn;
	
	
	
	private $codigo;
	private $producto;
	private $precio;
	private $cantidad;

	private $misDatos;

	Private $myProducto;

	public function __construct(){

		$this->db = new mod_db();
		$this->conn = $this->db->getConexion();

		$this->misDatos = new FormValidator();

		$this->myProducto = new ObjProductos($this->db);

	}

	public function crearProducto(){

		//es una función que lee el contenido completo de un 
		//archivo y lo devuelve como una cadena de texto
		//Acceso a distintos flujos de E/S
		/*🔧 "php://input" es una entrada especial de PHP que te permite leer el raw body (cuerpo sin procesar) de una petición HTTP. Es útil especialmente cuando usas JSON en APIs REST.*/

		$data = file_get_contents("php://input");

		$data = json_decode($data,true);
		//var_dump($data);//Confirmación que lo está leyendo
		//exit;
		
		//DETENTE Y VERIFICA: Si $data es nulo, el JSON estaba mal.
		    if (is_null($data)) {
		        http_response_code(400);
		        echo json_encode(["message" => "JSON inválido o vacío. Asegura Content-Type: application/json en Postman."]);
		        exit;
		    }


			$this->misDatos->enviarDatos($data);
			$this->misDatos->setRequiredFields(['codigo', 'producto',
				'precio', 'cantidad']);

			$this->misDatos->validate();

			if ($this->misDatos->getError()){
				//print_r($this->misDatos->arrayErrores);
				http_response_code(400);
				echo json_encode(["message" =>"los datos vienen con errores", "errores" =>
				 $this->misDatos->getErrorArray()]);
			} else {

        		$this->myProducto->DatosRequeridos($data);

				if ($this->myProducto->registrarProductos()){
				http_response_code(201);
				echo json_encode(["message" =>"ProductoCreado Exitosamente"]);
				}else {
				//Hubo un problemita
				http_response_code(503);
				echo json_encode(["message" =>"Producto no Creado"]);
				}

			}
		

	}//fin de public function crearProducto


		public function listarProductos(){
			$resultados = $this->myProducto->AllProducts();
			if (count($resultados) > 0) {
				//echo "Se encontraron " . count($resultados) . " registros.";

					$product_arr = [];
					$contador = 0;
					//si usas fetchAll(PDO::FETCH_ASSOC)
					
					foreach ($resultados as $row){
						
						$contador = $contador + 1;
						$product_item = [
							"id"       => $row["id"],       // <-- 
							"producto" => $row["producto"],
							"precio"   => $row["precio"],
							"cantidad" => $row["cantidad"],
							"codigo"   => $row["codigo"]

						];
						array_push($product_arr,$product_item);
						}//fin del doreach;
							
							
							http_response_code(200);
       						echo json_encode([
									"total" => $contador,
									"data"  => $product_arr]);
							
					
			

			} else {
					echo json_encode(["message" => "no se encontraron los registros"]);
			}

		} //fin del método ListarProductos	


} //fin de la clase

?>