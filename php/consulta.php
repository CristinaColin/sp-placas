<?php
	class placas {
		private $cnx;
		private $dbhost = "localhost";
		private $dbuser = "root";
		private $dbpass = "";
		private $dbname = "sp_placas";

		function __construct() {
			$this->connect_db();
		}

		public function connect_db() {
			$this->cnx = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
			$this->cnx->set_charset("utf8");
			if(mysqli_connect_error()) {
				die("Conexión a la base de datos falló " . mysqli_connect_error() . mysqli_connect_errno());
			}
		}

		// Nuevo método para obtener la conexión
		public function getConnection() {
			return $this->cnx;
		}

		public function getCatTiposVehiculos() {
			$sql = "SELECT descripcion FROM sp_cat_tipo_vehiculo ORDER BY descripcion asc";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}

		public function getPersonas() {
			$sql = "SELECT curp, nombre, primerApellido, segundoApellido FROM sp_personas ORDER BY nombre ASC LIMIT 100";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}
		public function getVehiculos() {
			$sql = "SELECT niv, numMotor, marca, modelo FROM sp_vehiculos ORDER BY niv ASC LIMIT 100";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}

		public function getPlacas(){
			$sql = "SELECT matricula, uso, clase, precio FROM sp_placas ORDER BY createdAt DESC LIMIT 100";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}

		public function getPersonaByCurp($curp){
			$sql = "SELECT curp, nombre, primerApellido, segundoApellido FROM sp_personas WHERE curp = '".$curp ."' ORDER BY nombre ASC LIMIT 100";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}

		public function getPersonaByNombre($nombre){
			$sql = "SELECT curp, nombre, primerApellido, segundoApellido FROM sp_personas WHERE nombre = '".$nombre ."' ORDER BY curp ASC";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}

		public function getPagoByPersona($curp){ //YO
			$sql = "SELECT pago.persona_curp, pago.monto, pago.fecha as fechaPago,
							p.curp, p.nombre, p.primerApellido, p.segundoApellido
					FROM sp_pago_placa AS pago
					LEFT JOIN sp_personas p on p.curp = pago.persona_curp
					WHERE pago.persona_curp = '".$curp."'";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}
		public function getPagoByMatricula($matricula){ // todos los datos
			$sql = "SELECT persona_curp, fechaPago, monto, concepto FROM sp_pago_placa WHERE placa_matricula = '". $matricula."' ORDER BY persona_curp ASC LIMIT 100";
			$res = mysqli_query($this->cnx, $sql);
			return $res;
		}

		public function getPrecioPlacaByClase($clase){
			$sql = "SELECT precio FROM sp_cat_precio_placa WHERE clase = '". $clase."' LIMIT 1";
			$res = mysqli_query($this->cnx, $sql);

			if ($res) {
				$row = mysqli_fetch_assoc($res);
				return $row['precio'];
			} else {
				return null;
			}
		}
	}

?>
