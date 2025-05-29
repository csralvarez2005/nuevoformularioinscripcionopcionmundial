<?php
require 'vendor/autoload.php'; // Cargar la librería PhpSpreadsheet
include 'config.php'; // Conexión a la base de datos

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Consulta para obtener todos los registros de la base de datos
$sql = "SELECT * FROM inscripciones ORDER BY id DESC";
$result = $conn->query($sql);

// Crear un nuevo archivo de Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de la tabla
$headers = ["Nombre", "Documento", "Dirección", "Teléfono", "Sisbén", "Edad", "Programa", "Porcentaje Beca", "Horario", "Fecha Registro"];
$sheet->fromArray($headers, NULL, 'A1');

// Aplicar formato de negrita a los encabezados
$styleArray = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    'borders' => ['bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
];

$sheet->getStyle('A1:J1')->applyFromArray($styleArray);

// Llenar datos desde la base de datos
$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowIndex", $row['nombre']);
    $sheet->setCellValue("B$rowIndex", $row['numeroDocumento']);
    $sheet->setCellValue("C$rowIndex", $row['direccion']);
    $sheet->setCellValue("D$rowIndex", $row['telefono']);
    $sheet->setCellValue("E$rowIndex", $row['sisben']);
    $sheet->setCellValue("F$rowIndex", $row['edad']);
    $sheet->setCellValue("G$rowIndex", $row['programaEstudio']); // Aquí se corrigió la columna G
    $sheet->setCellValue("H$rowIndex", $row['porcentajeBeca'] . '%');
    $sheet->setCellValue("I$rowIndex", $row['horariosDisponibles']);
    $sheet->setCellValue("J$rowIndex", date("d/m/Y", strtotime($row['fecha_registro'])));
    $rowIndex++;
}

// Aplicar autofiltro
$sheet->setAutoFilter($sheet->calculateWorksheetDimension());

// Ajustar automáticamente el ancho de las columnas
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Definir encabezados para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="inscripciones.xlsx"');
header('Cache-Control: max-age=0');

// Guardar y enviar el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>