const Toast = Swal.mixin({
  toast: true,
  position: 'center',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
function showPreviewImage(inputId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(`${inputId}-preview`);

  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.addEventListener('load', function () {
      preview.src = reader.result;
      preview.style.display = 'block';
    });

    reader.readAsDataURL(input.files[0]);
  }
}

/* $(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
}); */

document.getElementById('submitBtnImg').addEventListener('click', () => {

  const form = document.getElementById('imageForm');
  const formData = new FormData(form);
  /* let corrInabve = document.getElementById('corrInabve').value;
  if (corrInabve.trim() === "") {
    Swal.fire({
      position: 'top-center', icon: 'error', title: 'El correlativo del ampo es obligatorio', showConfirmButton: true,
      timer: 2500
    })
    return false;
  } */

  const fileInputs = document.getElementsByClassName('custom-file-input');
  // Verificar si todos los campos tienen un archivo seleccionado
  for (let i = 0; i < fileInputs.length; i++) {
    const fileInput = fileInputs[i];
    const file = fileInput.files[0];
    if (!file) {
      Swal.fire({
        position: 'top-center', icon: 'error', title: 'Cargar todos los archivos', showConfirmButton: true,
        timer: 2500
      })
      return false;
    }
  }

  let submitBtnImg = document.getElementById('submitBtnImg');
  submitBtnImg.classList.add('loading');
  submitBtnImg.textContent = 'GENERANDO PDF...';
  submitBtnImg.style.background = '#f2c332'; submitBtnImg.style.color = "white";

  document.getElementById('submitBtnImg').disabled = true;

  fetch('../ajax/scan.php?op=upload_images_drive', {
    method: 'POST',
    body: formData,
  })
    .then(response => {
      if (response.ok) {
        return response.text();
      } else {
        throw new Error('Error al subir las imágenes');
      }
    })
    .then(responseText => {
      $("#modal-upload-actas").modal("hide");
      document.getElementById('submitBtnImg').disabled = false;

      if (responseText === 'insertadas') {
        submitBtnImg.classList.remove('loading');
        submitBtnImg.textContent = 'CREAR PDF'; submitBtnImg.style.background = '#23272b';
        //document.getElementById('corrInabve').value = '';
        clearInputsFile();
        Swal.fire({
          position: 'top-center', icon: 'success', title: 'Los archivos han sido creados existoamente. <br><b style="color:blue">GUARDAR EN ' + ampo + '</b>', showConfirmButton: true,
          timer: 109500
        });

      } else {
        console.log('Error al insertar imágenes');
      }
      $("#dt-actas-firmadas").DataTable().ajax.reload(null, false);
    })
    .catch(error => {
      // Error en la subida
      console.error(error);
    });
});

function clearInputsFile() {
  const fileInputs = document.querySelectorAll('.custom-file-input');
  fileInputs.forEach((input) => {
    input.value = "";
  });

  const imagePreviews = document.querySelectorAll('img.img-ampos');

  imagePreviews.forEach((img) => {
    img.src = '';
    img.style.display = 'none';
  });
}

dtActasFirmadas()
////Listar Actas
function dtActasFirmadas() {

  tabla = $('#dt-actas-firmadas').DataTable({

    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    deferRender: true,
    buttons: [
      {
        extend: 'excelHtml5',
        customizeData: function (data) {
          // Obtener los valores de los inputs en cada fila
          var filas = tabla.rows().data();
          for (var i = 0; i < filas.length; i++) {
            var valorInput = $('#' + filas[i][0]).val();
            data.body[i][3] = valorInput;
          }
        }
      }
    ],

    "ajax": {
      url: "../ajax/scan.php?op=get_actas",
      type: "POST",
      dataType: "json"
    },

    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 100,//Por cada 10 registros hace una paginación
    "order": [[0, "asc"]],//Ordenar (columna,orden)
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }, //cerrando language
  });


}


var ampo = ''; var sucursal_ampo = '';
var dui_ampo; var id_acta_ampo;
function cargarActa(id_acta, dui, beneficiario, fecha, sucursal) {
  //document.getElementById('corrInabve').value = '';
  clearInputsFile();

  $.ajax({
    url: "../ajax/scan.php?op=get_ampo_acta",
    method: "POST",
    data: { sucursal, id_acta, dui }, //New params : id_acta,dui //16-05-2023
    cache: false,
    dataType: "json",
    success: function (data) {
      //Validacion existencia y mostrar mensaje
      document.getElementById('submitBtnImg').disabled = false;
      submitBtnImg.classList.remove('loading');
      submitBtnImg.textContent = 'CREAR PDF'; submitBtnImg.style.background = '#23272b';
      if (data === 'exists') {
        Swal.fire({
          position: 'center', icon: 'error', title: 'Los expedientes ya se encuentran registrado!!!', showConfirmButton: true,
          timer: 2500
        });
        return 0;
      }
      $("#modal-upload-actas").modal(); //Mostrar el modal para cargar las imagenes
      let idActaArray = data.map(function (item) {
        return item.id_acta;
      });

      let indice = idActaArray.findIndex(item => parseInt(item) === id_acta);
      let bloque = Math.floor(indice / 125) + 1;
      document.getElementById("acta-data").innerHTML = `${dui} - ${beneficiario}<br><strong>AMPO-${bloque} ${sucursal}</strong>`;
      ampo = `AMPO-${bloque}`; dui_ampo = dui; id_acta_ampo = id_acta;
      sucursal_ampo = `${sucursal}`; //console.log(ampo,sucursal_ampo)
      document.getElementById("ampo_acta").value = ampo;
      document.getElementById("dui_acta").value = dui_ampo;
      document.getElementById("sucursal_acta").value = sucursal;
      document.getElementById("id_acta_ampo").value = id_acta;

    }
  });////Fin Ajax 



}
/**
 * Function para generar pdf
 */
//Id ACTA VAR global
var id_acta_pdf = 0;
function showPDFUploadActa(id_acta, sucursal = '', paciente, dui_acta) {
  id_acta_pdf = id_acta
  document.getElementById('sucursal-acta').textContent = sucursal;
  document.getElementById('paciente_acta').textContent = paciente;
  document.getElementById('duiActa').textContent = dui_acta;
  var arrayTipoDoc = ['VIÑETA LABORATORIO', 'RECETA OPTÓMETRA', 'ACTA FIRMADA', 'HOJA DE IDENTIFICACIÓN'];
  var usuarioScaneo = '';
  $.ajax({
    url: "../ajax/scan.php?op=getScanActasUpload",
    method: "POST",
    data: { id_acta },
    cache: false,
    dataType: "json",
    success: function (data) {
      //Validaciones
      if (data.length > 0) {
        $("#pdfModal").modal('show');
      } else {
        Swal.fire({
          position: 'center', icon: 'error', title: 'No se han cargado expedientes!', showConfirmButton: true,
          timer: 1500
        });
        return 0;
      }
      let contentImgHtml = document.getElementById('pdf-content');
      contentImgHtml.innerHTML = '';
      let html = ``;
      data.forEach((element, index) => {
        usuarioScaneo = element.usuario //Asigned value var
        let array_url_img = element.url_expediente.split('=');
        let URL_BASE_GOOGLE = 'https://lh3.googleusercontent.com/d/';
        let imagen_id = array_url_img[1];
        let url_img = URL_BASE_GOOGLE + imagen_id;
        html += `<div class="card p-1 px-2 my-3 card-img">
        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.1);text-align:center;margin-bottom: 5px"><b class="text-center" style="font-size: 15px;">${arrayTipoDoc[index]} - ${element.paciente} - ${element.dui_paciente} - # ${element.ampo}</b></div>
        <img style="border-radius: 5px;" src="${url_img}" width="100%" alt="imagen-inabve" referrerpolicy="no-referrer" />
        <div class="card-foot">
          <p style="font-size: 13px; margin: 2px">FECHA ESCANEO: ${element.fecha_scan} - ${element.sucursal}</p>
        </div>
      </div>`
      });
      document.getElementById('usuario_system').textContent = usuarioScaneo; //Muestra en el encabezado de la modal el usuario
      contentImgHtml.innerHTML = html;
      //impPDFUploadActa(id_acta)
    }
  });//Fin Ajax
}

function impPDFUploadActa(id_acta_param = '') {
  id_acta = id_acta_pdf;
  var form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  form.action = "imprimir_scan_actas.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "id_acta";
  input.value = id_acta;
  form.appendChild(input);
  document.body.appendChild(form);//"width=600,height=500"

  form.submit();
  document.body.removeChild(form);
}
/**
 * Function para eliminar registros de la acta subida a drive
 */
function confirmDelUploadActa(id_acta, sucursal, paciente, dui_acta) {
  let permisoOk = names_permisos.includes("del_acta_firmada");
  if (!permisoOk) {
    Swal.fire({
      position: 'center', icon: 'error', title: 'Permisos insuficientes para realizar esta acción!!!', showConfirmButton: true,
      timer: 1500
    }); return false;
  }
  $.ajax({
    url: "../ajax/scan.php?op=getScanActasUpload",
    method: "POST",
    data: { id_acta },
    cache: false,
    dataType: "json",
    success: function (data) {
      if (data.length !== 0) {
        Swal.fire({
          title: '¿Quieres eliminar los expedientes?',
          html: "<p style='font-size: 14px'><b>PACIENTE:</b> " + paciente + "</p><p style='font-size: 14px'><b>DUI: " + dui_acta + " </b></p>",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            removeExpedienteActa(id_acta)
          }
        })
      } else {
        Swal.fire({
          position: 'center', icon: 'error', title: 'No se han cargado expedientes!', showConfirmButton: true,
          timer: 1500
        });

      }
    }
  });//Fin Ajax
}

function removeExpedienteActa(id_acta) {
  $.ajax({
    url: "../ajax/scan.php?op=removeUploadActa",
    method: "POST",
    data: { id_acta },
    cache: false,
    dataType: "json",
    success: function (data) {
      console.log(data)
      if (data === 'success') {
        Swal.fire({
          position: 'center', icon: 'success', title: 'Los expedientes fueron removidos!', showConfirmButton: true,
          timer: 1500
        });
        $("#dt-actas-firmadas").DataTable().ajax.reload(null, false);
      } else {
        Swal.fire({
          position: 'center', icon: 'error', title: 'Error al eliminar expedientes!', showConfirmButton: true,
          timer: 1500
        });
      }
    }
  });//Fin Ajax
}

//Validacion de espacio en Correlativo INABVE
/* try {
  let corrInabve = document.getElementById('corrInabve');
  corrInabve.addEventListener('keyup', () => {
    let value = corrInabve.value;
    let formatValue = value.replace(/\s+/g, "")
    corrInabve.value = formatValue;
  })
} catch (err) {

} */

function editCorrAmpo(element) {
  let value = element.value;
  let id_acta = element.id;
  if (!element.hasOwnProperty('readonly')) {
    $.ajax({
      url: "../ajax/scan.php?op=updCorrAmpo",
      method: "POST",
      data: { id_acta: id_acta, valor: value },
      cache: false,
      dataType: "json",
      success: function (data) {
        if (data === 'success') {
          Toast.fire({
            icon: 'success',
            title: 'Correlativo actualizado exitosamente!!'
          })
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Ha ocurrido un error al procesar la solicitud!!'
          })
        }
      }
    });//Fin Ajax
  }
}
var boolDisabledInput = 0;
function toggleInputCorr(element, id_acta) {
  let input = document.getElementById(id_acta);
  if (boolDisabledInput === 0) {
    input.removeAttribute('readonly');
    boolDisabledInput = 1;
  } else {
    input.setAttribute('readonly', 'true')
    boolDisabledInput = 0
  }
}