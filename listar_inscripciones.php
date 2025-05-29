<?php
include 'config.php'; 

// Parámetros de búsqueda
$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Asegurar formato de fecha compatible con MySQL
if (!empty($fecha_inicio)) {
    $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
}
if (!empty($fecha_fin)) {
    $fecha_fin = date('Y-m-d', strtotime($fecha_fin));
}

// Construcción del filtro WHERE
$where = "WHERE 1=1";
if (!empty($busqueda)) {
    $where .= " AND (nombre LIKE '%$busqueda%' OR numeroDocumento LIKE '%$busqueda%')";
}
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $where .= " AND (DATE(fecha_registro) BETWEEN '$fecha_inicio' AND '$fecha_fin')";
}

// Paginación
$registros_por_pagina = 5;
$sql_total = "SELECT COUNT(*) as total FROM inscripciones $where";
$result_total = $conn->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_actual = max(1, min($pagina_actual, $total_paginas));
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta con paginación
$sql = "SELECT * FROM inscripciones $where ORDER BY id DESC LIMIT $offset, $registros_por_pagina";
$result = $conn->query($sql);
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Inscripciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        .search-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        .search-container input {
            height: 40px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            white-space: nowrap;
            text-align: center;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="border p-4 bg-light rounded">
            <h2 class="text-center">Lista de Inscripciones</h2>

            <form method="GET" class="search-container mb-3">
                <input type="text" name="busqueda" class="form-control w-25" placeholder="Buscar por nombre o documento" value="<?php echo htmlspecialchars($busqueda); ?>">
                <input type="date" name="fecha_inicio" class="form-control w-25" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                <input type="date" name="fecha_fin" class="form-control w-25" value="<?php echo htmlspecialchars($fecha_fin); ?>">

                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                <a href="listar_inscripciones.php" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i></a>
                <a href="exportar_excel.php" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> 
                </a>
            </form>

     

            <div class="table-responsive">
                <table id="tablaInscripciones" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Sisbén</th>
                            <th>Edad</th>                         
                            <th>Programa</th>
                            <th>Porcentaje Beca</th>
                            <th>Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['numeroDocumento']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['sisben']); ?></td>
                            <td><?php echo htmlspecialchars($row['edad']); ?></td>
                            <td><?php echo htmlspecialchars($row['programaEstudio']); ?></td>
                            <td><?php echo htmlspecialchars($row['porcentajeBeca']); ?>%</td>
                            <td><?php echo htmlspecialchars($row['horariosDisponibles']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
    <p class="fw-bold">Total de registros: <?php echo $total_registros; ?></p>
</div>

            <!-- Paginador -->
           <nav>
    <ul class="pagination">
        <!-- Botón "Primero" -->
        <li class="page-item <?php if ($pagina_actual <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="?pagina=1&busqueda=<?php echo $busqueda; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>">Primero</a>
        </li>

        <!-- Botón "Anterior" -->
        <li class="page-item <?php if ($pagina_actual <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>&busqueda=<?php echo $busqueda; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>">Anterior</a>
        </li>

        <!-- Mostrar un rango dinámico de páginas -->
        <?php 
        $rango = 3; // Número de páginas visibles a la izquierda y derecha de la actual
        $inicio = max(1, $pagina_actual - $rango);
        $fin = min($total_paginas, $pagina_actual + $rango);

        for ($i = $inicio; $i <= $fin; $i++): ?>
            <li class="page-item <?php if ($i == $pagina_actual) echo 'active'; ?>">
                <a class="page-link" href="?pagina=<?php echo $i; ?>&busqueda=<?php echo $busqueda; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Botón "Siguiente" -->
        <li class="page-item <?php if ($pagina_actual >= $total_paginas) echo 'disabled'; ?>">
            <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>&busqueda=<?php echo $busqueda; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>">Siguiente</a>
        </li>

        <!-- Botón "Último" -->
        <li class="page-item <?php if ($pagina_actual >= $total_paginas) echo 'disabled'; ?>">
            <a class="page-link" href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>">Último</a>
        </li>
    </ul>
</nav>
        
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>