<?php
include 'config.php';
header('Content-Type: application/json');
$response = ["success" => false, "message" => ""];

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Método no permitido.");
    }

    $id_sede = $conn->real_escape_string($_POST['id_sede'] ?? '');

    if (!$id_sede) {
        throw new Exception("Sede no especificada.");
    }

    $nombre          = $conn->real_escape_string($_POST['nombre'] ?? '');
    $numeroDocumento = $conn->real_escape_string($_POST['numeroDocumento'] ?? '');
    $fecha_registro  = date("Y-m-d H:i:s");

    // Verificar duplicado por número de documento
    $sql_check = "SELECT id FROM inscripciones WHERE numeroDocumento = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $numeroDocumento);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $response["message"] = "El usuario " . strtoupper($nombre) . " con documento " . strtoupper($numeroDocumento) . " ya está registrado. Para más información, comunícate con la línea 3043186185 de la Fundación Opción Mundial.";
        echo json_encode($response);
        exit;
    }
    $stmt_check->close();

    if ($id_sede == '1') {
        // Formulario Sede 1
        $direccion           = $conn->real_escape_string($_POST['direccion'] ?? '');
        $telefono            = $conn->real_escape_string($_POST['telefono'] ?? '');
        $sisben              = $conn->real_escape_string($_POST['sisben'] ?? '');
        $edad                = $conn->real_escape_string($_POST['edad'] ?? '');
        $porcentajeBeca      = $conn->real_escape_string($_POST['porcentajeBeca'] ?? '');
        $programaEstudio     = $conn->real_escape_string($_POST['programaEstudio'] ?? '');
        $horariosDisponibles = $conn->real_escape_string($_POST['horariosDisponibles'] ?? '');

        $sql_insert = "INSERT INTO inscripciones 
            (id_sede, nombre, numeroDocumento, direccion, telefono, sisben, edad, porcentajeBeca, programaEstudio, horariosDisponibles, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql_insert);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssss",
            $id_sede,
            $nombre,
            $numeroDocumento,
            $direccion,
            $telefono,
            $sisben,
            $edad,
            $porcentajeBeca,
            $programaEstudio,
            $horariosDisponibles,
            $fecha_registro
        );

    } elseif ($id_sede == '2') {
        // Formulario Sede 2
        $direccion           = $conn->real_escape_string($_POST['direccion'] ?? '');
        $telefono            = $conn->real_escape_string($_POST['telefono'] ?? '');
        $sisben              = $conn->real_escape_string($_POST['sisben'] ?? '');
        $edad                = $conn->real_escape_string($_POST['edad'] ?? '');
        $porcentajeBeca      = $conn->real_escape_string($_POST['porcentajeBeca'] ?? '');
        $programaEstudio     = $conn->real_escape_string($_POST['programaEstudio'] ?? '');
        $horariosDisponibles = $conn->real_escape_string($_POST['horariosDisponibles'] ?? '');
        $tipoEstudio = $conn->real_escape_string($_POST['tipoEstudio'] ?? '');
        $curso       = $conn->real_escape_string($_POST['curso'] ?? '');
        $programa    = $conn->real_escape_string($_POST['programa'] ?? '');

        $sql_insert = "INSERT INTO inscripciones 
            (id_sede, nombre, numeroDocumento, tipoEstudio, curso, programaEstudio, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql_insert);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssss",
            $id_sede,
            $nombre,
            $numeroDocumento,
            $tipoEstudio,
            $curso,
            $programa,
            $fecha_registro
        );

    } else {
        throw new Exception("Sede no reconocida.");
    }

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "¡Inscripción realizada con éxito, " . strtoupper($nombre) . "! Para más información, comunícate con nuestra línea de atención 3043186185.";
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
    error_log("[" . date("Y-m-d H:i:s") . "] " . $e->getMessage() . "\n", 3, "error_log.txt");
}

$conn->close();
echo json_encode($response);