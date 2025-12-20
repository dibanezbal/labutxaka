<?php
require_once __DIR__ . '/../config/database.php';

class UsuariosModel {
	private mysqli $db;

	public function __construct()
	{
		$this->db = Connect::connection();
	}

	public function create($usuario, $email, $password, $fecha_registro) {
		$stmt = $this->db->prepare("INSERT INTO usuarios (usuario, email, password, fecha_registro) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss', $usuario, $email, $password, $fecha_registro);
		if (!$stmt->execute()) return 0;
		return (int)$this->db->insert_id;
	}

	public function login($usuario) {
		$stmt = $this->db->prepare("SELECT id, usuario, email, password FROM usuarios WHERE usuario = ? LIMIT 1");
		$stmt->bind_param('s', $usuario);
		$stmt->execute();
		$res = $stmt->get_result();
		return $res->fetch_assoc() ?: null;
	}
}