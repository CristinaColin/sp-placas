<?php
	class send {
		private $cnx;
		private $dbhost = "localhost";
		private $dbuser = "root";
		private $dbpass = "";
		private $dbname = "sp_placas";

        private $successMessage = "";
        private $errorMessage = "";

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

        function showAlert($mensaje){ // mostrar mensaje y redireccionar
            echo "<script> 
                    alert('$mensaje');
                    window.location.href = 'index.php'; 
                  </script>";
        }

        function catchDuplicatedPrimaryKey($mensajeExito, $mensajeError, $sql){
            try {
                if (mysqli_query($this->cnx, $sql)) {
                    $this->showAlert($mensajeExito);
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) { //Error de clave duplicada
                    $this->showAlert($mensajeError);
                } else {
                    $mensaje = 'Error al registrar: ' . $e->getMessage();
                }
            }
        }

        public function registrarVehiculo($data) {
            $$tipo_id_query = mysqli_query($this->cnx, "SELECT id FROM sp_cat_tipo_vehiculo WHERE descripcion ='".$data['tipo']."'"); 
            $tipo_id = mysqli_fetch_assoc($tipo_id_query)['id'];

            $sql = "INSERT INTO sp_vehiculos (niv, numMotor, numChasis, tipo_id ,tipo, clase, color, modelo, marca, numPuertas,
                                            combustible, cilindros, ejes) 
                    VALUES ('".$data['niv']."','".$data['numMotor']."','".$data['numChasis']."','".$tipo_id. "','".$data['tipo']."', 
                            '".$data['clase']."','".$data['color']."','".$data['modelo']."','".$data['marca']."', 
                            '".$data['numPuertas']."','".$data['combustible']."','".$data['cilindros']."','".$data['ejes']."'
                    )";

            $this->catchDuplicatedPrimaryKey('Vehículo registrado con éxito','Erro: el vehículo con NIV '.$data['niv'].' ya está registrado.', $sql);
        }

        public function registrarPersona($data) {
            $sql = "INSERT INTO sp_personas (curp, nombre, primerApellido, segundoApellido, fechaNacimiento, sexo, direccion, numCelular) 
                    VALUES ('".$data['curp']."','".$data['nombre']."','".$data['primerApellido']."','".$data['segundoApellido']."','"
                            .$data['fechaNacimiento']."','".$data['sexo']."','".$data['direccion']."','".$data['numCelular']."'
                    )";
        
            $this->catchDuplicatedPrimaryKey('Persona registrada exitosamente.', 'Error: La curp '.$data['curp'].' ya está registrada.', $sql);
        }

        public function getPersona($data) {
            $sql = "INSERT INTO sp_personas (curp, nombre, primerApellido, segundoApellido, fechaNacimiento, sexo, direccion, numCelular) 
                    VALUES ('".$data['curp']."','".$data['nombre']."','".$data['primerApellido']."','".$data['segundoApellido']."','"
                            .$data['fechaNacimiento']."','".$data['sexo']."','".$data['direccion']."','".$data['numCelular']."'
                    )";
        
            $this->catchDuplicatedPrimaryKey('Persona registrada exitosamente.', 'Error: La curp '.$data['curp'].' ya está registrada.', $sql);
        }

	    public function registrarPlaca($data) {
            $sql = "INSERT INTO sp_placas (matricula, clase, precio) 
                    VALUES ('".$data['matricula']."','".$data['clase']."','".$data['precio']."')";
            
            if (mysqli_query($this->cnx, $sql)) {
                return "Placa registrada con éxito";
            } else {
                return "Error al registrar a la placa: " . mysqli_error($this->cnx);
            }
        }
        public function registrarPagoPlaca($data) {
            $sql = "INSERT INTO sp_pago_placa (id, concepto, persona_curp, placa_matricula, fechaPago, monto) 
                    VALUES ('".$data['id']."','".$data['concepto']."','".$data['persona_curp']."','".$data['placa_matricula']."','".$data['fechaPago']."','".$data['monto']."')";
            
            if (mysqli_query($this->cnx, $sql)) {
                return "Placa pagada con éxito";
            } else {
                return "Error al pagar la placa: " . mysqli_error($this->cnx);
            }
        }
        public function asignarPersonaAVehiculo($data) {
            $sql = "INSERT INTO sp_rel_persona_vehiculo (id, persona_curp, vehiculo_niv, fechaInicial, fechaFinal) 
                    VALUES ('".$data['id']."','".$data['persona_curp']."','".$data['vehiculo_niv']."','".$data['fechaInicial']."','".$data['fechaFinal']."')";
            
            if (mysqli_query($this->cnx, $sql)) {
                return "Personada asignada al vehículo con éxito";
            } else {
                return "Error al asignar la persona al vehículo: " . mysqli_error($this->cnx);
            }
        }
        public function asignarPlacaAVehiculo($data) {
            $sql = "INSERT INTO sp_rel_persona_vehiculo (id, placa_matricula, vehiculo_niv, fechaAlta, fechaBaja, motivoBaja) 
                    VALUES ('".$data['id']."','".$data['placa_matricula']."','".$data['vehiculo_niv']."','".$data['fechaAlta']."','".$data['fechaBaja']."','".$data['motivoBaja']."')";
            
            if (mysqli_query($this->cnx, $sql)) {
                return "Placa asignada con éxito";
            } else {
                return "Error al asignar la placa: " . mysqli_error($this->cnx);
            }
        
        }
	}

?>
