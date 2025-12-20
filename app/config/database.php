<?php
	// CONEXIÓN DB DEV - LOCAL 
	class Connect {
		
		public static function connection(){

            $host = "localhost:8889";
            $user = "root";
            $pass = "root";
            $bd = "labutxaka_DEV";

			$connect = new mysqli($host, $user, $pass, $bd);
			return $connect;
			
		}
	}
?>