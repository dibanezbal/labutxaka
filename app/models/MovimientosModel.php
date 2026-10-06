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
		return $this->getMovimientosByUserFiltered((int)$userId, []);
	}

	private function bindDynamicParams(mysqli_stmt $stmt, string $types, array $values): void
	{
		// mysqli requiere referencias en bind_param
		$refs = [];
		foreach ($values as $k => $v) {
			$refs[$k] = &$values[$k];
		}
		array_unshift($refs, $types);
		call_user_func_array([$stmt, 'bind_param'], $refs);
	}

	/**
	 * Filtros soportados:
	 * - mes (YYYY-MM)
	 * - cuenta_id (int)
	 * - categoria_id (int)
	 * - tipo_movimiento ('Ingreso'|'Gasto'|'Transferencia')
	 * - tipo_registro ('Fijo'|'Variable')
	 * - q (string, busca en comentario/categoría/cuenta)
	 */
	public function getMovimientosByUserFiltered(int $userId, array $filters): array
	{
		$sql = "SELECT m.*, c.nombre AS categoria_nombre, a.nombre AS cuenta_nombre
		FROM movimientos m
		LEFT JOIN categorias c ON c.id = m.categoria_id
		LEFT JOIN cuentas a    ON a.id = m.cuenta_id
		WHERE m.usuario_id = ?";

		$types = 'i';
		$params = [$userId];

		if (!empty($filters['cuenta_id'])) {
			$sql .= " AND m.cuenta_id = ?";
			$types .= 'i';
			$params[] = (int)$filters['cuenta_id'];
		}

		if (!empty($filters['categoria_id'])) {
			$sql .= " AND m.categoria_id = ?";
			$types .= 'i';
			$params[] = (int)$filters['categoria_id'];
		}

		if (!empty($filters['tipo_movimiento'])) {
			$sql .= " AND m.tipo_movimiento = ?";
			$types .= 's';
			$params[] = (string)$filters['tipo_movimiento'];
		}

		if (!empty($filters['tipo_registro'])) {
			$sql .= " AND m.tipo_registro = ?";
			$types .= 's';
			$params[] = (string)$filters['tipo_registro'];
		}

		if (!empty($filters['mes'])) {
			$mes = (string)$filters['mes'];
			// Espera YYYY-MM
			if (preg_match('/^\d{4}-\d{2}$/', $mes)) {
				$start = $mes . '-01 00:00:00';
				$end = date('Y-m-d H:i:s', strtotime($mes . '-01 +1 month'));
				$sql .= " AND m.fecha_registro >= ? AND m.fecha_registro < ?";
				$types .= 'ss';
				$params[] = $start;
				$params[] = $end;
			}
		}

		if (!empty($filters['q'])) {
			$q = trim((string)$filters['q']);
			if ($q !== '') {
				$like = '%' . $q . '%';
				$sql .= " AND (m.comentario LIKE ? OR c.nombre LIKE ? OR a.nombre LIKE ?)";
				$types .= 'sss';
				$params[] = $like;
				$params[] = $like;
				$params[] = $like;
			}
		}

		$sql .= " ORDER BY m.fecha_registro DESC, m.id DESC";

		$stmt = $this->db->prepare($sql);
		$this->bindDynamicParams($stmt, $types, $params);
		$stmt->execute();
		$res = $stmt->get_result();
		return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
	}

	public function insertMovimiento($userId, $categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha_registro, $comentario) {
		$connection = $this->db;
		$sql = sprintf(
			"INSERT INTO movimientos (usuario_id, categoria_id, cuenta_id, tipo_registro, tipo_movimiento, cantidad, fecha_registro, comentario)
			VALUES (%d, %d, %d, '%s', '%s', %F, '%s', %s)",
			$userId,
			$categoria_id,
			$cuenta_id,
			$connection->real_escape_string($tipo_registro),
			$connection->real_escape_string($tipo_movimiento),
			$cantidad,
			$connection->real_escape_string($fecha_registro),
			$comentario === null ? "NULL" : ("'" . $connection->real_escape_string($comentario) . "'")
		);
		return $connection->query($sql);
	}

	public function editMovimiento($id, $categoria_id, $cuenta_id, string $tipo_registro, string $tipo_movimiento, float $cantidad, string $fecha_registro, ?string $comentario) {
		$userId = ($_SESSION['user_id'] ?? 0);
		$connection = $this->db;

		$tipo_registro   = $connection->real_escape_string($tipo_registro);
		$tipo_movimiento = $connection->real_escape_string($tipo_movimiento);
		$fecha_registro  = $connection->real_escape_string($fecha_registro);
		$comentarioEsc   = $comentario === null ? "NULL" : ("'" . $connection->real_escape_string($comentario) . "'");

		$sql = sprintf(
			"UPDATE movimientos SET categoria_id=%d, cuenta_id=%d, tipo_registro='%s', tipo_movimiento='%s', cantidad=%F, fecha_registro='%s', comentario=%s WHERE id=%d AND usuario_id=%d",
			$categoria_id, $cuenta_id, $tipo_registro, $tipo_movimiento, $cantidad, $fecha_registro, $comentarioEsc, $id, $userId
		);
		return $connection->query($sql);
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