<?php 
    class CuentasModel {
        
        private mysqli $db;
        private $cuentas;
        
        
        public function __construct()
        {
            $this->db = Connect::connection();
        }

        public function getAllCuentas() {
            $result = $this->db->query("SELECT id, nombre, saldo_inicial FROM cuentas ORDER BY nombre");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        public function getAllCuentasByUser($userId) {
            $stmt = $this->db->prepare("SELECT id, nombre, saldo_inicial FROM cuentas WHERE usuario_id = ? ORDER BY nombre");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        }

        public function insertCuenta($nombre, $saldo_inicial, $tipo_cuenta, $descripcion)
        {
            $usuario_id = $_SESSION['user_id'] ?? null;
            $result = $this->db->query("INSERT INTO `cuentas` (usuario_id, nombre, saldo_inicial, tipo_cuenta, descripcion) VALUES ( '$usuario_id', '$nombre', $saldo_inicial, '$tipo_cuenta', '$descripcion')");
        }

        public function getCuentaById($id)
        {
            $sql = "SELECT * FROM cuentas WHERE id='$id' LIMIT 1";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        }

        public function editCuenta($id, $nombre, $saldo_inicial, $tipo_cuenta, $descripcion)
        {   
            $usuario_id = $_SESSION['user_id'] ?? null;
            $result = $this->db->query("UPDATE `cuentas` SET id=$id, usuario_id = '$usuario_id', nombre = '$nombre', saldo_inicial = $saldo_inicial, tipo_cuenta = '$tipo_cuenta' id = $id");         
        }

        public function deleteCuenta($id){
            $result = $this->db->query("DELETE FROM cuentas WHERE id = '$id'");
        }
    }
?>