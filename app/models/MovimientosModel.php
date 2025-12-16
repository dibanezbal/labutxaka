<?php
	
	class MovimientosModel {
		
		private $db;
		private $movimientos;
		
		public function __construct()
		{
			$this->db = Connect::connection();
			$this->movimientos = array();
		}
		
		public function getAllMovimientos($sortBy = 'date_desc')
		{
			switch ($sortBy) {
				case 'fecha_asc':
					$orderBy = 'fecha_registro ASC';
					break;
				case 'cantidad_desc':
					$orderBy = 'cantidad DESC';
					break;
				case 'cantidad_asc':
					$orderBy = 'cantidad ASC';
					break;
				default:
					$orderBy = 'fecha_registro DESC';
			}
			$sql = "SELECT * FROM movimientos ORDER BY $orderBy";
			$result = $this->db->query($sql);
			while($row = $result->fetch_assoc())
			{
				$this->movimientos[] = $row;
			}
			return $this->movimientos;
		}

		public function insertMovimiento($categoria, $cuenta, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario)
		{
			
			$result = $this->db->query("INSERT INTO `movimientos` (usuario_id, categoria_id, cuenta_id, tipo_registro, tipo_movimiento, cantidad, fecha_registro, comentario) VALUES ( 1, $categoria, $cuenta, '$tipo_registro', '$tipo_movimiento', $cantidad, '$fecha', '$comentario')");
		}
		
		public function editMovimiento($id ,$categoria, $cuenta, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario)
		{	
			$result = $this->db->query("UPDATE `movimientos` SET id=$id, usuario_id = 1, categoria_id = $categoria, cuenta_id = $cuenta, tipo_movimiento = '$tipo_movimiento', tipo_registro = '$tipo_registro', cantidad = $cantidad, fecha_registro = '$fecha', comentario = '$comentario' WHERE id = $id");			
		}
		
		public function delete(array $ids) {
			if (empty($ids)) return false;
			$ids = array_map('intval', $ids);
			$items = implode(',', array_fill(0, count($ids), '?'));
			$sql = "DELETE FROM movimientos WHERE id IN ($items)";
			$stmt = $this->db->prepare($sql);
			return $stmt->execute($ids);
		}
		
		public function getMovimientoById($id)
		{
			$sql = "SELECT * FROM movimientos WHERE id='$id' LIMIT 1";
			$result = $this->db->query($sql);
			$row = $result->fetch_assoc();

			return $row;
		}

		// Obtener categorías

		public function getCategorias(){
			$categorias = array();
			$sql = "SELECT id, nombre FROM categorias";
			$result = $this->db->query($sql);
			while($row = $result->fetch_assoc())
			{
				$categorias[$row['id']] = $row['nombre'];
			}
			return $categorias;
		}

		// Obtener cuentas
		public function getCuentas(){
			$cuentas = array();
			$sql = "SELECT id, nombre FROM cuentas";
			$result = $this->db->query($sql);
			while($row = $result->fetch_assoc())
			{
				$cuentas[$row['id']] = $row['nombre'];
			}
			return $cuentas;
		}
    
	} 
?>