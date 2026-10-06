<?php
require_once __DIR__ . '/../config/database.php';

class UsuariosModel {
	private mysqli $db;

	public function __construct()
	{
		$this->db = Connect::connection();
	}

	public function create($usuario, $email, $password, $fecha_registro) {
		$hash = password_hash((string)$password, PASSWORD_DEFAULT);
		$stmt = $this->db->prepare("INSERT INTO usuarios (usuario, email, password, fecha_registro) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss', $usuario, $email, $hash, $fecha_registro);
		if (!$stmt->execute()) return 0;
		return (int)$this->db->insert_id;
	}

	public function login($usuario, $password) {
		$stmt = $this->db->prepare("SELECT id, usuario, email, password FROM usuarios WHERE usuario = ? LIMIT 1");
		$stmt->bind_param('s', $usuario);
		$stmt->execute();
		$res = $stmt->get_result();
		$row = $res->fetch_assoc();
		if (!$row) return null;

		$stored = (string)($row['password'] ?? '');
		$ok = false;
		if ($stored !== '' && preg_match('/^(\\$2y\\$|\\$argon2id\\$|\\$argon2i\\$)/', $stored)) {
			$ok = password_verify((string)$password, $stored);
		} else {
			$ok = hash_equals($stored, (string)$password);
		}
		if (!$ok) return null;

		unset($row['password']);
		return $row;
	}
}