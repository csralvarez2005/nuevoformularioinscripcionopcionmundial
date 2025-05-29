<?php
if (!isset($_GET['id_sede'])) {
    echo "<div class='alert alert-danger'>Sede no seleccionada.</div>";
    exit;
}

$id_sede = intval($_GET['id_sede']);
if ($id_sede === 1):
?>
<form id="formulario-inscripcion" method="POST">
    <input type="hidden" id="id_sede" name="id_sede" value="<?php echo $id_sede; ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre y Apellidos <span class="text-danger">*</span></label>
            <input type="text" id="nombre" name="nombre" class="form-control" required autocomplete="off">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de Documento <span class="text-danger">*</span></label>
            <input type="number" id="numeroDocumento" name="numeroDocumento" class="form-control" required autocomplete="off">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Dirección <span class="text-danger">*</span></label>
            <input type="text" id="direccion" name="direccion" class="form-control" required autocomplete="off">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono / Celular <span class="text-danger">*</span></label>
            <input type="tel" id="telefono" name="telefono" class="form-control" required autocomplete="off" minlength="10" maxlength="10"
                pattern="\d{10}" title="Debe contener exactamente 10 dígitos">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Sisbén <span class="text-danger">*</span></label>
            <select id="sisben" name="sisben" class="form-select" required>
                <option value="" disabled selected>Seleccionar...</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Ingrese su edad <span class="text-danger">*</span></label>
            <input type="number" id="edad" name="edad" class="form-control" required min="1" max="99" oninput="this.value = this.value.slice(0, 2)">
        </div>
    </div>
    <h2 class="text-center mb-4">Programa subsidio educativo del aspirante</h2>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Porcentaje de beca solicitado <span class="text-danger">*</span></label>
            <div class="d-flex">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="porcentajeBeca" id="beca50" value="50" required>
                    <label class="form-check-label" for="beca50">50%</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="porcentajeBeca" id="beca40" value="40" required>
                    <label class="form-check-label" for="beca40">40%</label>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Horarios Disponibles <span class="text-danger">*</span></label>
            <select id="horariosDisponibles" name="horariosDisponibles" class="form-select" required>
                <option value="" disabled selected>Seleccionar...</option>
                <option value="6:45 AM - 12:15 PM - Semipresencial-Domingo">6:45 AM - 12:15 PM - Semipresencial-Domingo</option>
                <option value="4:15 PM - 6:30 PM - Presencial">4:15 PM - 6:30 PM - Presencial</option>
                <option value="7:00 PM - 9:00 PM - Nocturna">7:00 PM - 9:00 PM - Nocturna</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Programa técnico <span class="text-danger">*</span></label>
            <select id="programaEstudio" name="programaEstudio" class="form-select" required>
                <option value="" disabled selected>Seleccionar...</option>
            </select>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-info w-50">Enviar</button>
    </div>

<?php elseif ($id_sede === 2): ?>
<form id="formulario-inscripcion" method="POST">
    <input type="hidden" id="id_sede" name="id_sede" value="<?php echo $id_sede; ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre y Apellidos <span class="text-danger">*</span></label>
            <input type="text" id="nombre" name="nombre" class="form-control" required autocomplete="off">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de Documento <span class="text-danger">*</span></label>
            <input type="number" id="numeroDocumento" name="numeroDocumento" class="form-control" required autocomplete="off">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Dirección <span class="text-danger">*</span></label>
            <input type="text" id="direccion" name="direccion" class="form-control" required autocomplete="off">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono / Celular <span class="text-danger">*</span></label>
            <input type="tel" id="telefono" name="telefono" class="form-control" required autocomplete="off" minlength="10" maxlength="10" pattern="\d{10}" title="Debe contener exactamente 10 dígitos">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Sisbén <span class="text-danger">*</span></label>
            <select id="sisben" name="sisben" class="form-select" required>
                <option value="" disabled selected>Seleccionar...</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Ingrese su edad <span class="text-danger">*</span></label>
            <input type="number" id="edad" name="edad" class="form-control" required min="1" max="99" oninput="this.value = this.value.slice(0, 2)">
        </div>
    </div>
    <hr>
    <div class="row mb-3 align-items-end">
        <div class="col-md-6">
            <label class="form-label">Seleccione una opción:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoEstudio" id="radioCurso" value="curso" checked>
                <label class="form-check-label" for="radioCurso">Curso</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoEstudio" id="radioPrograma" value="programa">
                <label class="form-check-label" for="radioPrograma">Programa</label>
            </div>
        </div>

        <div class="col-md-6" id="opcionesCurso">
            <label class="form-label">Seleccione un curso:</label>
            <select name="curso" id="curso" class="form-select">
                <option value="">Seleccionar...</option>
            </select>
        </div>

        <div class="col-md-6 d-none" id="opcionesPrograma">
            <label class="form-label">Seleccione un programa:</label>
            <select name="programa" id="programa" class="form-select">
                <option value="">Seleccionar...</option>
            </select>
        </div>
    </div>

    <div class="col-md-6 mt-3" id="horarioContainer">
        <label class="form-label">Seleccione un horario:</label>
        <select name="horariosDisponibles" id="horariosDisponibles" class="form-select" required>
            <option value="">Seleccionar...</option>
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-info w-50">Enviar</button>
    </div>
<?php else: ?>
    <div class="alert alert-warning">Sede no reconocida.</div>
<?php endif; ?>
</form>
