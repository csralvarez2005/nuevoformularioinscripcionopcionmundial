<?php
class Programas {
    private static $programas = [
        ["id" => 1, "nombre" => "Técnico en Desarrollo de Software", "beca" => 50],
        ["id" => 2, "nombre" => "Técnico en Diseño Gráfico", "beca" => 50],
        ["id" => 3, "nombre" => "Técnico en Gestión Empresarial", "beca" => 50],
        ["id" => 4, "nombre" => "Técnico en Redes y Telecomunicaciones", "beca" => 50],
        ["id" => 5, "nombre" => "Técnico en Administración de Empresas", "beca" => 40],
        ["id" => 6, "nombre" => "Técnico en Contabilidad", "beca" => 40],
        ["id" => 7, "nombre" => "Técnico en Mercadeo", "beca" => 40],
    ];

    public static function obtenerPorBeca($beca) {
        return array_values(array_filter(self::$programas, fn($p) => $p["beca"] == $beca));
    }

    public static function procesarSolicitud() {
        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['beca'])) {
            header('Content-Type: application/json');
            echo json_encode(self::obtenerPorBeca(intval($_GET['beca'])));
            exit;
        }
    }
}

Programas::procesarSolicitud();
?>