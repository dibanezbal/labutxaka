<?php
	// CONEXIÓN DB DEV - LOCAL 
	class Connect {
		
		public static function connection(){
			$hostEnv = getenv('DB_HOST') ?: 'db:3306';
			$user = getenv('DB_USER') ?: 'labutxaka';
			$pass = getenv('DB_PASS');
			if (!is_string($pass) || $pass === '') {
				throw new RuntimeException('Configura DB_PASS mediante una variable de entorno.');
			}
			$bd   = getenv('DB_NAME') ?: 'labutxaka_DEV';

			$host = $hostEnv;
			$port = null;
			$socket = null;

			// Permite DB_HOST en formato host:puerto (p.ej. db:3306)
			if (is_string($hostEnv) && str_contains($hostEnv, ':') && !str_contains($hostEnv, '/')) {
				[$h, $p] = explode(':', $hostEnv, 2);
				$host = $h;
				if (ctype_digit($p)) {
					$port = (int)$p;
				}
			}

			// Si se pasa un socket (ruta), úsalo
			if (is_string($hostEnv) && str_starts_with($hostEnv, '/')) {
				$host = 'localhost';
				$socket = $hostEnv;
			}

			$connect = new mysqli($host, $user, $pass, $bd, $port ?? 3306, $socket);
			if ($connect->connect_errno) {
				throw new RuntimeException('Error conectando a MySQL: ' . $connect->connect_error);
			}
			$connect->set_charset('utf8mb4');
			return $connect;
			
		}
	}
?>