<?php

	// CONEXIÓN DB PROD
	// class Connect {
		
	// 	public static function connection(){

    //         $host = "localhost";
    //         $user = "dibanezba";
    //         $pass = ".Vt59NQiH6cXkRK3";
    //         $bd = "dibanezba";

	// 		$connect = new mysqli($host, $user, $pass, $bd);
	// 		return $connect;
			
	// 	}
	// }
	
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