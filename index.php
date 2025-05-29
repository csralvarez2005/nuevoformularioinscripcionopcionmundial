<?php
// Simulación de sedes, normalmente proviene de la base de datos
$sedes = [
    ['id' => 1, 'nombre' => 'Cartagena'],
    ['id' => 2, 'nombre' => 'Medellín'],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inscripción</title>
    <link rel="icon" type="image/png" href="fabicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container mt-4 p-0">        
    <div class="card p-0">
        <div class="header-container">
            <img src="becas.webp" alt="Fundación Opción Mundial">
        </div>
        <div class="p-4 text-center">
            <h2>FORMULARIO DE INSCRIPCIÓN FUNDACIÓN OPCIÓN MUNDIAL</h2>
            <p>Este formulario permitirá a la persona que lo diligencie estar vinculada en la Fundación, haciéndola beneficiaria del convenio académico.</p>
            <p class="text-danger">* Indica que la pregunta es obligatoria</p>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4">Datos Personales</h2>

        <!-- Campo de selección de sede -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_sede" class="form-label">Seleccione su sede <span class="text-danger">*</span></label>
                <select name="id_sede" id="id_sede" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($sedes as $sede): ?>
                        <option value="<?= $sede['id'] ?>"><?= $sede['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Contenedor del formulario dinámico -->
        <div id="formulario-container"></div>

    </div>
</div>

<!-- jQuery y script de lógica -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>

</body>
</html>