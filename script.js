document.addEventListener("DOMContentLoaded", () => {
  const selectSede = document.getElementById("id_sede");
  const contenedorFormulario = document.getElementById("formulario-container");

  if (selectSede) {
    selectSede.addEventListener("change", () => {
      const sedeId = selectSede.value;

      if (!sedeId) {
        contenedorFormulario.innerHTML = "";
        return;
      }

      fetch(`formulario_base.php?id_sede=${sedeId}`)
        .then((response) => {
          if (!response.ok) throw new Error("Error al cargar el formulario.");
          return response.text();
        })
        .then((html) => {
          contenedorFormulario.innerHTML = html;

          if (sedeId === "1") {
            inicializarLogicaSede1();
          } else if (sedeId === "2") {
            inicializarLogicaSede2();
          }
        })
        .catch((error) => {
          contenedorFormulario.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
        });
    });
  }

  function inicializarLogicaSede1() {
    const selectHorario = $("#horariosDisponibles");
    const selectPrograma = $("#programaEstudio");
    const radiosBeca = $("input[name='porcentajeBeca']");
    const formulario = $("#formulario");
    const inputNombre = $("#nombre");
    const inputDocumento = $("#numeroDocumento");

    const programasDisponibles = {
      50: {
        "6:45 AM - 12:15 PM - Semipresencial-Domingo": [
          "AUXILIAR EN SERVICIOS FARMACÉUTICOS",
          "AUXILIAR EN ENFERMERÍA",
          "AUXILIAR EN SALUD ORAL",
          "AUXILIAR ADMINISTRATIVO EN SALUD",
          "AUXILIAR CONTABLE Y FINANCIERO",
          "AUXILIAR EN EDUCACIÓN PARA LA PRIMERA INFANCIA",
          "AUXILIAR EN LOGÍSTICA EMPRESARIAL",
          "AUXILIAR EN SISTEMAS",
          "AUXILIAR EN MECÁNICA DENTAL",
          "AUXILIAR EN VETERINARIA",
        ],
        "4:15 PM - 6:30 PM - Presencial": [
          "AUXILIAR EN SERVICIOS FARMACÉUTICOS",
          "AUXILIAR EN ENFERMERÍA",
          "AUXILIAR ADMINISTRATIVO EN SALUD",
          "AUXILIAR EN SALUD ORAL",
          "AUXILIAR EN EDUCACIÓN PARA LA PRIMERA INFANCIA",
          "AUXILIAR EN SISTEMAS",
          "AUXILIAR EN VETERINARIA",
        ],
        "7:00 PM - 9:00 PM - Nocturna": [
          "AUXILIAR EN SERVICIOS FARMACÉUTICOS",
          "AUXILIAR EN ENFERMERÍA",
          "AUXILIAR EN SALUD ORAL",
          "AUXILIAR EN EDUCACIÓN PARA LA PRIMERA INFANCIA",
          "AUXILIAR EN SISTEMAS",
        ],
      },
      40: {
        "4:15 PM - 6:30 PM - Presencial": [
          "AUXILIAR EN SERVICIOS FARMACÉUTICOS",
          "AUXILIAR EN ENFERMERÍA",
          "AUXILIAR ADMINISTRATIVO EN SALUD",
          "AUXILIAR EN SALUD ORAL",
          "AUXILIAR EN EDUCACIÓN PARA LA PRIMERA INFANCIA",
          "AUXILIAR EN SISTEMAS",
          "AUXILIAR EN VETERINARIA",
        ],
        "7:00 PM - 9:00 PM - Nocturna": [
          "AUXILIAR EN SERVICIOS FARMACÉUTICOS",
          "AUXILIAR EN ENFERMERÍA",
          "AUXILIAR EN SALUD ORAL",
          "AUXILIAR EN EDUCACIÓN PARA LA PRIMERA INFANCIA",
          "AUXILIAR EN SISTEMAS",
        ],
      },
    };

    const horariosDisponibles = Object.fromEntries(
      Object.entries(programasDisponibles).map(([beca, horarios]) => [
        beca,
        Object.keys(horarios),
      ])
    );

    function actualizarHorarios() {
      const porcentajeSeleccionado = radiosBeca.filter(":checked").val();
      selectHorario.html('<option value="" disabled selected>Seleccionar...</option>');

      if (horariosDisponibles[porcentajeSeleccionado]) {
        horariosDisponibles[porcentajeSeleccionado].forEach((horario) => {
          selectHorario.append(`<option value="${horario}">${horario}</option>`);
        });
      }

      selectPrograma.html('<option value="" disabled selected>Seleccionar...</option>');
    }

    function actualizarProgramas() {
      const porcentajeSeleccionado = radiosBeca.filter(":checked").val();
      const horarioSeleccionado = selectHorario.val();

      selectPrograma.html('<option value="" disabled selected>Seleccionar...</option>');

      if (programasDisponibles[porcentajeSeleccionado]?.[horarioSeleccionado]) {
        programasDisponibles[porcentajeSeleccionado][horarioSeleccionado].forEach(
          (programa) => {
            selectPrograma.append(`<option value="${programa}">${programa}</option>`);
          }
        );
      }
    }

    $("#formulario-inscripcion").on("submit", async function (event) {
  event.preventDefault();

  Swal.fire({
    title: "Enviando inscripción...",
    text: "Por favor, espere un momento.",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => Swal.showLoading(),
  });

  try {
    const formData = new FormData(this);
    const idSede = document.getElementById("id_sede").value;
    formData.append("id_sede", idSede);

    const respuesta = await fetch("crear_inscripcion.php", {
      method: "POST",
      body: formData,
    });

    const text = await respuesta.text();
    let resultado;

    try {
      resultado = JSON.parse(text);
    } catch (jsonError) {
      console.error("⚠️ Error al parsear JSON:", text);
      throw new Error("Respuesta inválida del servidor");
    }

    setTimeout(() => {
      Swal.close();
      Swal.fire({
        icon: resultado.success
          ? "success"
          : resultado.message.includes("ya está registrado")
          ? "warning"
          : "error",
        title: resultado.success
          ? "¡Inscripción exitosa!"
          : resultado.message.includes("ya está registrado")
          ? "Usuario ya registrado"
          : "Error en la inscripción",
        text: resultado.message,
        confirmButtonText: "OK",
      }).then(() => {
        if (resultado.success || resultado.message.includes("ya está registrado")) {
          window.location.href = "index.php";
        }
      });
    }, 500);
  } catch (error) {
    setTimeout(() => {
      Swal.close();
      Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar con el servidor. Inténtelo de nuevo más tarde.\n\n" + error.message,
        confirmButtonText: "Reintentar",
      });
    }, 500);
  }
});

    inputNombre.on("input", function () {
      $(this).val($(this).val().replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g, ""));
    });

    radiosBeca.on("change", actualizarHorarios);
    selectHorario.on("change", actualizarProgramas);
  }

  function inicializarLogicaSede2() {
    const selectCurso = $("#curso");
    const selectPrograma = $("#programa");
    const radiosTipoEstudio = $("input[name='tipoEstudio']");
    const divCurso = $("#opcionesCurso");
    const divPrograma = $("#opcionesPrograma");
    const formulario = $("#formulario");
    const inputNombre = $("#nombre");
    const inputDocumento = $("#numeroDocumento");

    const cursosDisponibles = [
      "CURSO DE PRIMEROS AUXILIOS",
      "CURSO DE MANIPULACIÓN DE ALIMENTOS",
      "CURSO DE SALUD OCUPACIONAL",
    ];

    const programasDisponibles = [
      "AUXILIAR EN ENFERMERÍA",
      "AUXILIAR EN SISTEMAS",
      "AUXILIAR EN SALUD ORAL",
      "AUXILIAR ADMINISTRATIVO EN SALUD",
    ];

    function actualizarOpciones() {
      const tipoSeleccionado = radiosTipoEstudio.filter(":checked").val();

      if (tipoSeleccionado === "curso") {
        divCurso.removeClass("d-none");
        divPrograma.addClass("d-none");
        selectCurso.html('<option value="">Seleccionar...</option>');
        cursosDisponibles.forEach((curso) => {
          selectCurso.append(`<option value="${curso}">${curso}</option>`);
        });
      } else if (tipoSeleccionado === "programa") {
        divPrograma.removeClass("d-none");
        divCurso.addClass("d-none");
        selectPrograma.html('<option value="">Seleccionar...</option>');
        programasDisponibles.forEach((programa) => {
          selectPrograma.append(`<option value="${programa}">${programa}</option>`);
        });
      }
    }

    formulario.submit(async function (event) {
      event.preventDefault();

      Swal.fire({
        title: "Enviando inscripción...",
        text: "Por favor, espere un momento.",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading(),
      });

      try {
        const formData = new FormData(this);
        const idSede = document.getElementById("id_sede").value;
        formData.append("id_sede", idSede);

        const respuesta = await fetch("crear_inscripcion.php", {
          method: "POST",
          body: formData,
        });

        const text = await respuesta.text();
        let resultado;

        try {
          resultado = JSON.parse(text);
        } catch (jsonError) {
          console.error("⚠️ Error al parsear JSON:", text);
          throw new Error("Respuesta inválida del servidor");
        }

        setTimeout(() => {
          Swal.close();
          if (resultado.success || resultado.message.includes("ya está registrado")) {
            Swal.fire({
              icon: resultado.success ? "success" : "warning",
              title: resultado.success ? "¡Inscripción exitosa!" : "Usuario ya registrado",
              text: resultado.message,
              timer: 3000,
              timerProgressBar: true,
              showConfirmButton: false,
              willClose: () => {
                window.location.href = "index.php";
              },
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Error en la inscripción",
              text: resultado.message,
              confirmButtonText: "OK",
            });
          }
        }, 500);
      } catch (error) {
        setTimeout(() => {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor. Inténtelo de nuevo más tarde.\n\n" + error.message,
            confirmButtonText: "Reintentar",
          });
        }, 500);
      }
    });

    inputNombre.on("input", function () {
      $(this).val($(this).val().replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g, ""));
    });

    radiosTipoEstudio.on("change", actualizarOpciones);
    actualizarOpciones();
  }
});





  
