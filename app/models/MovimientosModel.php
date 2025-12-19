<?php

require_once __DIR__ . '/../models/CuentasModel.php';
require_once __DIR__ . '/../models/CategoriasModel.php';

	class MovimientosModel {
		
		private $db;
		private $movimientos;
		private CuentasModel $cuentasModel;
		private CategoriasModel $categoriasModel;
		
		public function __construct()
		{
			$this->db = Connect::connection();
			$this->movimientos = array();
			$this->cuentasModel = new CuentasModel();
			$this->categoriasModel = new CategoriasModel();
		}
		
		public function getAllMovimientosByUser($userId)
		{
			$sql = "SELECT m.*, c.nombre AS categoria_nombre, a.nombre AS cuenta_nombre
			FROM movimientos m
			LEFT JOIN categorias c ON c.id = m.categoria_id
			LEFT JOIN cuentas a    ON a.id = m.cuenta_id
			WHERE m.usuario_id = ?
			ORDER BY m.fecha_registro DESC, m.id DESC";

			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('i', $userId);
			$stmt->execute();
			$res = $stmt->get_result();
			
			return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
		}

		public function insertMovimiento($categoria, $cuenta, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario)
		{
			$userId = $_SESSION['user_id'] ?? null;
			
			$result = $this->db->query("INSERT INTO `movimientos` (usuario_id, categoria_id, cuenta_id, tipo_registro, tipo_movimiento, cantidad, fecha_registro, comentario) VALUES ( $userId, $categoria, $cuenta, '$tipo_registro', '$tipo_movimiento', $cantidad, '$fecha', '$comentario')");
		}
		
		public function editMovimiento($id ,$categoria, $cuenta, $tipo_registro, $tipo_movimiento, $cantidad, $fecha, $comentario)
		{	
			$userId = $_SESSION['user_id'] ?? null;
			$result = $this->db->query("UPDATE `movimientos` SET id=$id, usuario_id = $userId, categoria_id = $categoria, cuenta_id = $cuenta, tipo_movimiento = '$tipo_movimiento', tipo_registro = '$tipo_registro', cantidad = $cantidad, fecha_registro = '$fecha', comentario = '$comentario' WHERE id = $id");			
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

		// Obtener cuentas
		public function getCuentas() {
			return $this->cuentasModel->getAllCuentas();
		}

		// Obtener categorías
		public function getCategorias() {
			return $this->categoriasModel->getAllCategorias();
		}
    
	} 
?>