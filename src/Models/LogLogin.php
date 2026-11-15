<?php
// BREVE DESCRIPCIÓN:
// Modelo para la gestión de registros de intentos de login.
// Almacena auditoría de accesos, intentos fallidos y bloqueos de cuentas.

namespace Models;

use Core\Modelo;

class LogLogin extends Modelo
{
    protected $tabla = 'logs_login';

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY fecha DESC LIMIT 100";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorEmail($email)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE email = ? ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchAll();
    }

    public function obtenerIntentosFallidos($email)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE email = ? AND exitoso = 0 AND fecha >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function registrar($email, $ip, $exitoso, $mensaje = null)
    {
        $sql = "INSERT INTO {$this->tabla} (email, ip, exitoso, mensaje) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$email, $ip, $exitoso, $mensaje]);
    }

    public function limpiarViejos($dias = 30)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE fecha < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$dias]);
    }
}