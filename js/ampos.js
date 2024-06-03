
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
////Listar Actas
dtActasFirmadas()
function dtActasFirmadas() {

  tabla = $('#dt-actas-ampo').DataTable({

    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    deferRender: true,
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/scan.php?op=get_actas_ampo",
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
/**
 * 
 * @param {button} id_acc 
 */

function openModal() {
  $("#listActasAmpos").modal('show');
  focusInput('id_dui_scan');
}


function getRandomFechaSinFinesDeSemana(id_acta) {
  let fecha_inicio = '';
  let fecha_fin = '';
  if (id_acta == "S/A") {
    fecha_inicio = '2023-01-05';
    fecha_fin = '2023-02-24';
  } else {
    fecha_inicio = '2022-11-15';
    fecha_fin = '2023-02-15';
  }



  let unDia = 24 * 60 * 60 * 1000;
  let fechaInicioMs = new Date(fecha_inicio).getTime();
  let fechaFinMs = new Date(fecha_fin).getTime();
  let rangoMs = fechaFinMs - fechaInicioMs;

  let fechaAleatoriaMs = Math.random() * rangoMs + fechaInicioMs;
  let fechaAleatoria = new Date(fechaAleatoriaMs);

  while (fechaAleatoria.getDay() === 0 || fechaAleatoria.getDay() === 6) {
    fechaAleatoriaMs += unDia;
    fechaAleatoria = new Date(fechaAleatoriaMs);
  }
  let year = fechaAleatoria.getFullYear();
  let mes = String(fechaAleatoria.getMonth() + 1).padStart(2, '0');
  let dia = String(fechaAleatoria.getDate()).padStart(2, '0');
  let fechaFormateada = `${year}-${mes}-${dia}`;

  return fechaFormateada;
}
var arrayDataActas = [];
var orden_fecha_update = [];
/* function trasladarOrdenes(id_acc) {
  let id_dui_scan = document.getElementById('id_dui_scan').value;
  if (id_dui_scan === "") {
    Swal.fire({
      position: 'center', icon: 'error', title: 'Se esperaba un DUI o Id Acta!!!', showConfirmButton: true,
      timer: 2500
    });
    return 0;
  }
  //clear_space
  id_dui_scan = id_dui_scan.trim();
  id_dui_scan = id_dui_scan.replace("'", '-');
  $.ajax({
    url: "../ajax/scan.php?op=get_acta_dui_idActa",
    method: "POST",
    data: { busqueda: id_dui_scan },
    cache: false,
    dataType: "json",
    success: function (data) {
      document.getElementById('paciente_gui').textContent = data.acta[0].beneficiario;
      document.getElementById('dui_gui').textContent = data.acta[0].dui_acta;
      let actas = data.dataActas;
      let paciente = data.acta[0].beneficiario;
      let dui = data.acta[0].dui_acta;
      let sucursal = data.acta[0].sucursal;
      //Validacion de existencia
      let index = arrayDataActas.findIndex((objeto) => {
        return objeto.dui == dui;
      });
      if (index >= 0) {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Acta ya existe en la lista',
          showConfirmButton: true,
          timer: 1000
        });
        focusInput('id_dui_scan');
        return 0;
      }
      $("#modalUpdFecha").modal('show'); //Modal para actualizar la fecha
      $("#date").val('')
      const btnUpdFecha = document.getElementById('btnUpdFecha');
      btnUpdFecha.removeEventListener('click', (e) => console.log())
      btnUpdFecha.addEventListener('click', (e) => {

        let date = document.getElementById('date').value; //Fecha de formulario
        if (date === "") {
          Toast.fire({
            icon: 'error',
            title: ' Por favor, ingrese una fecha!!'
          })
          return 0;
        }
        let id_acta = data.acta[0].id_acta;

        let data_ord = data.dataOrd;      // Fecha dada
        //let fecha_orden = data_ord[0].fecha;
        let fecha_orden = date;
        //let codigo_ord = data_ord[0].codigo;
        //let fechaOrden = new Date(fecha_orden);
        let fechaOrden = new Date(date);
        const fechaEspecifica = new Date('2023-02-24');


        if (fechaOrden < fechaEspecifica) {

          //let nueva_fecha = getRandomFechaSinFinesDeSemana(id_acta);
          let dui_act = data_ord[0].dui;
          let objUpdate = {
            fecha_act: date, dui: dui_act
          }

          orden_fecha_update.push(objUpdate)
          objUpdate = null;
          var hash = {};
          orden_fecha_update = orden_fecha_update.filter(function (current) {
            var exists = !hash[current.dui];
            hash[current.dui] = true;
            return exists;
          });

          document.getElementById("count-print").innerHTML = orden_fecha_update.length

        } else {
          Toast.fire({
            icon: 'error',
            title: 'La fecha excede el limite!!'
          })
          document.getElementById('date').value = '';
          document.getElementById('date').focus();
          return;
        }
        $("#modalUpdFecha").modal('hide');

        if (data.length === 0) {
          Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Id Acta o DUI invalidos',
            showConfirmButton: true,
            timer: 1000
          });
          focusInput('id_dui_scan');
          return;
        }
        //Clear input
        focusInput('id_dui_scan');

        let idActaArray = actas.map(function (item) {
          return item.id_acta;
        });
        let indice = idActaArray.findIndex(item => parseInt(item) === parseInt(id_acta));
        let bloque = Math.floor(indice / 125) + 1;
        ampo = `AMPO-${bloque}`;
        let arrFecha = date.split('-');
        let day = arrFecha[2];
        let month = arrFecha[1];
        let year = arrFecha[0];
        date = day + '-' + month + '-' + year;
        let objActa = {
          id_acta: id_acta,
          paciente: paciente,
          dui: dui,
          sucursal: sucursal,
          ampo: ampo, fecha_orden: date
        }
        arrayDataActas.push(objActa);
        objActa = null;
        var hash1 = {};
        arrayDataActas = arrayDataActas.filter(function (current) {
          var exists = !hash1[current.dui];
          hash1[current.dui] = true;
          return exists;
        });
        //Generar el dataTable
        generarRowTable(arrayDataActas);
      })
    }
  });////Fin Ajax 

} */
function trasladarOrdenes(id_acc) {
  let id_dui_scan = document.getElementById('id_dui_scan').value;
  if (id_dui_scan === "") {
    Swal.fire({
      position: 'center', icon: 'error', title: 'Se esperaba un DUI o Id Acta!!!', showConfirmButton: true,
      timer: 2500
    });
    return 0;
  }
  //clear_space
  id_dui_scan = id_dui_scan.trim();
  id_dui_scan = id_dui_scan.replace("'", '-');
  $.ajax({
    url: "../ajax/scan.php?op=get_acta_dui_idActa",
    method: "POST",
    data: { busqueda: id_dui_scan },
    cache: false,
    dataType: "json",
    success: function (data) {
      document.getElementById('paciente_gui').textContent = data.acta[0].beneficiario;
      document.getElementById('dui_gui').textContent = data.acta[0].dui_acta;
      let actas = data.dataActas;
      let paciente = data.acta[0].beneficiario;
      let dui = data.acta[0].dui_acta;
      let sucursal = data.acta[0].sucursal;
      //Validacion de existencia
      let index = arrayDataActas.findIndex((objeto) => {
        return objeto.dui == dui;
      });
      if (index >= 0) {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Acta ya existe en la lista',
          showConfirmButton: true,
          timer: 1000
        });
        focusInput('id_dui_scan');
        return 0;
      }
        let id_acta = data.acta[0].id_acta;

        let data_ord = data.dataOrd;      // Fecha dada
        //let fecha_orden = data_ord[0].fecha;
        let fecha_orden = date;
        //let codigo_ord = data_ord[0].codigo;
        //let fechaOrden = new Date(fecha_orden);
        let fechaOrden = new Date(date);
        const fechaEspecifica = new Date('2023-02-24');

        if (data.length === 0) {
          Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Id Acta o DUI invalidos',
            showConfirmButton: true,
            timer: 1000
          });
          focusInput('id_dui_scan');
          return;
        }
        //Clear input
        focusInput('id_dui_scan');

        let idActaArray = actas.map(function (item) {
          return item.id_acta;
        });
        let indice = idActaArray.findIndex(item => parseInt(item) === parseInt(id_acta));
        let bloque = Math.floor(indice / 125) + 1;
        ampo = `AMPO-${bloque}`;
        date = '01' + '-' + '01' + '-' + '2023';
        let objActa = {
          id_acta: id_acta,
          paciente: paciente,
          dui: dui,
          sucursal: sucursal,
          ampo: ampo, fecha_orden: date
        }
        arrayDataActas.push(objActa);
        objActa = null;
        var hash1 = {};
        arrayDataActas = arrayDataActas.filter(function (current) {
          var exists = !hash1[current.dui];
          hash1[current.dui] = true;
          return exists;
        });
        //Generar el dataTable
        generarRowTable(arrayDataActas);
    }
  });////Fin Ajax 

}

function generarRowTable(data) {
  Toast.fire({
      icon: 'success',
      title: 'Acta agregada!!'
    })
  $("#actas-ampos").html('');
  let filas = '';
  if (data.length > 0) {
    for (var i = data.length - 1; i >= 0; i--) {
      let date_orden = new Date(data[i].fecha_orden);
      let dia = date_orden.getDate();
      let mes = date_orden.getMonth() + 1;
      let anio = date_orden.getFullYear();

      let f_orden = `${dia}-${mes}-${anio}`;
      filas = filas + "<tr id='fila" + i + "'>" +
        "<td '>" + data[i].id_acta + "</td>" +
        "<td '>" + data[i].paciente + "</td>" +
        "<td '>" + data[i].ampo + "</td>" +
        "<td '>" + data[i].dui + "</td>" +
        "<td '>" + data[i].fecha_orden + "</td>" +
        "<td '>" + data[i].sucursal + "</td>" +
        "<td '><i class='fas fa-times-circle' style='color:red;cursor:pointer' onClick='delActaList(" + i + ", \"" + data[i].dui + "\")'></i></td>" +
        "</tr>";
    }
    $("#actas-ampos").html(filas);
  }
}

function delActaList(index, dui) {
  arrayDataActas.splice(index, 1);
  $('#id_dui_scan').focus();
  let index_del = orden_fecha_update.findIndex(objeto => objeto.dui == dui);
  orden_fecha_update.splice(index, 1);
  if (index_del !== -1) {
    document.getElementById("count-print").innerHTML = orden_fecha_update.length
  }
  generarRowTable(arrayDataActas)
}

function focusInput(id) {
  document.getElementById(id).value = '';
  document.getElementById(id).focus();
}

/**
 * Clear table
 */

function clearTable() {
  arrayDataActas = [];
  $("#actas-ampos").html('');
}

function printOrdenes() {
  let data = orden_fecha_update;
  console.log(data)
  console.log(arrayDataActas)
  if (data.length > 0) {
    let sucursal = document.getElementById('sucursal_expedientes');
    if (sucursal.value === null || sucursal.value === undefined || sucursal.value === "") {
      Toast.fire({
        icon: 'warning',
        title: 'Por favor, seleccionar una sucursal!!'
      })
      return 0;
    }
    sucursal = sucursal.value; //value
    $.ajax({
      url: "../ajax/update_expedientes.php?op=updateOrdExpediente",
      method: "POST",
      data: { data, sucursal },
      cache: false,
      dataType: "json",
      success: function (data) {
        if (data === "msgUpdOk") {
          let cantidaExp = orden_fecha_update.length;
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: cantidaExp + ' expedientes se han actualizado exitosamente!',
            showConfirmButton: true,
            timer: 9500
          });
          //generarPDF(orden_fecha_update);
          orden_fecha_update = [];
          arrayDataActas = [];
          $("#actas-ampos").html('');
          document.getElementById('sucursal_expedientes').value = '';
          // $("#dt_ordenes_enviadas").DataTable().ajax.reload(null, false);
        }
        $('#actas-ampos').html('');
        $('#count-print').html('');
      }
    })
  } else {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Por favor, agregar ordenes a actualizar!',
      showConfirmButton: true,
      timer: 9500
    });
    return 0;
  }
}

function deleteOrdenesUp() {
  arrayDataActas = [];
  orden_fecha_update = [];
  document.getElementById("count-print").innerHTML = orden_fecha_update.length
  generarRowTable(arrayDataActas)
}

function generarPDF(data) {

  arrayDataActas = [];
  orden_fecha_update = [];

  var form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  form.action = "imprimir_new_expedientes.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "data";
  input.value = JSON.stringify(data);
  form.appendChild(input);

  document.body.appendChild(form)
  form.submit();
  document.body.removeChild(form);

}
/**
 * 
 * NEW CODE TRASLADO DE ORDENES
 */
//Orignal function
function trasladarOrdenes_original() {
  let id_dui_scan = document.getElementById('id_dui_scan').value;
  if (id_dui_scan === "") {
    Swal.fire({
      position: 'center', icon: 'error', title: 'Se esperaba un DUI o Id Acta!!!', showConfirmButton: true,
      timer: 2500
    });
    return 0;
  }
  //clear_space
  id_dui_scan = id_dui_scan.trim();
  id_dui_scan = id_dui_scan.replace("'", '-');
  document.getElementById('id_dui_scan').value = id_dui_scan;
  $.ajax({
    url: "../ajax/scan.php?op=get_acta_dui_idActa",
    method: "POST",
    data: { busqueda: id_dui_scan },
    cache: false,
    dataType: "json",
    success: function (data) {
      //console.log(data)
      document.getElementById('paciente_gui').textContent = data.acta[0].beneficiario;
      document.getElementById('dui_gui').textContent = data.acta[0].dui_acta;
      let actas = data.dataActas;
      let paciente = data.acta[0].beneficiario;
      let dui = data.acta[0].dui_acta;
      let sucursal = data.acta[0].sucursal;
      let id_acta = data.acta[0].id_acta;
      //Validacion de existencia
      let index = arrayDataActas.findIndex((objeto) => {
        return objeto.dui == dui;
      });
      if (index >= 0) {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Acta ya existe en la lista',
          showConfirmButton: true,
          timer: 1000
        });
        focusInput('id_dui_scan');
        return 0;
      }
      $("#modalUpdFecha").modal('show'); //Modal para actualizar la fecha
      $("#date").val('')
      document.getElementById('date').focus();
      const btnUpdFecha = document.getElementById('btnUpdFecha');
      // Crear una copia del botón sin los eventos asociados
      const btnUpdFechaClone = btnUpdFecha.cloneNode(true);

      // Remover el botón original
      btnUpdFecha.parentNode.replaceChild(btnUpdFechaClone, btnUpdFecha);

      // Agregar el evento click actualizado al nuevo botón
      btnUpdFechaClone.addEventListener('click', handleClick);

      function handleClick(e) {
        // ... tu código actual para el evento click ...
        let date = document.getElementById('date').value; //Fecha de formulario
        if (date === "") {
          Toast.fire({
            icon: 'error',
            title: ' Por favor, ingrese una fecha!!'
          })
          return 0;
        }
        let fechaOrden = new Date(date);
        const fechaEspecifica = new Date('2023-02-24');
        if (fechaOrden < fechaEspecifica) {
          let dui_act = data.dataOrd[0].dui;
          let objUpdate = {
            fecha_act: date, dui: dui_act
          }

          orden_fecha_update.push(objUpdate)
          var hash = {};
          orden_fecha_update = orden_fecha_update.filter(function (current) {
            var exists = !hash[current.dui];
            hash[current.dui] = true;
            return exists;
          });

          document.getElementById("count-print").innerHTML = orden_fecha_update.length

        } else {
          Toast.fire({
            icon: 'error',
            title: 'La fecha excede el limite!!'
          })
          document.getElementById('date').value = '';
          document.getElementById('date').focus();
          return;
        }
        $("#modalUpdFecha").modal('hide');

        if (data.length === 0) {
          Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Id Acta o DUI invalidos',
            showConfirmButton: true,
            timer: 1000
          });
          focusInput('id_dui_scan');
          return;
        }
        //Clear input
        focusInput('id_dui_scan');
        let arrFecha = date.split('-');
        let day = arrFecha[2];
        let month = arrFecha[1];
        let year = arrFecha[0];
        date = day + '-' + month + '-' + year;
        let objActa = {
          id_acta: id_acta,
          paciente: paciente,
          dui: dui,
          sucursal: sucursal,
          ampo: '---', fecha_orden: date
        }
        arrayDataActas.push(objActa);
        var hash1 = {};
        arrayDataActas = arrayDataActas.filter(function (current) {
          var exists = !hash1[current.dui];
          hash1[current.dui] = true;
          return exists;
        });
        //Generar el dataTable
        generarRowTable(arrayDataActas);
      }
      /* btnUpdFecha.addEventListener('click', (e) => {
        
      }) */
    }
  });////Fin Ajax 
}
