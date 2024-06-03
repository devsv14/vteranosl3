//SCRIPT PARA ENTREGAS
/**
 * dev: 2022
*/
$(document).ready(() => {
  get_entregas_ordenes();
})

function dt_datatables(id_html, url, data) {
  $('#' + id_html).DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    deferRender: true,
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/entregas.php?op=" + url,
      type: "POST",
      dataType: "json",
      data: data,
      error: function (e) {
        console.log(e.responseText);
      },
      complete: function () {
        $('.spinner').hide(); // Oculta la animación de carga
      }
    },

    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 25,//Por cada 10 registros hace una paginación
    "order": [[0, "desc"]],//Ordenar (columna,orden)

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
    //"scrollX": true
  });
}

//Function messages
function message_alert(message,type_message,timer = 2500){
  Swal.fire({
    position: 'top-center',
    icon: type_message,
    title: message,
    showConfirmButton: true,
    timer: timer
  });
}
//Validaciones input

function input_oblig(){
  let inputs = document.getElementsByClassName('oblig')

  for(let i= 0; i< inputs.length; i++){
    let input = document.getElementById(inputs[i].id)
    if(input.value == ""){
      input.classList.add('is-invalid')
      return true;
    }else{
      input.classList.remove('is-invalid')
    }
  }
}
//Full data
function get_entregas_ordenes() {
  let list_general_llamadas = names_permisos.includes('listado_general_llamadas');
  let permiso_listar = ''; 
  list_general_llamadas ? permiso_listar = 'Ok' : permiso_listar = 'Not';
  dt_datatables('dt_entregas_ordenes', 'get_entregas_ordenes', {permiso_listar: permiso_listar})
}

//Guardar info de las llamdas

function save_accion_entregas(){
  let estadoLLamada = document.getElementById('estado_llamada').value;
  let accion = document.getElementById('accion').value
  let id_accion_optica = $("#id_accion_optica").val()
  //Let codigo
  let codigo = $("#codigo").val();
  if(accion == ""){
    accion = "-"
  }
  if(input_oblig()){
    message_alert('¡¡Existen campos vacios!!','warning');
    return 0;
  }
  $.ajax({
    url: "../ajax/entregas.php?op=save_accion_entrega",
    method: "POST",
    cache: false,
    data: {estadoLLamada:estadoLLamada,accion: accion,id_accion_optica: id_accion_optica,codigo: codigo},
    dataType: "json",
    success: function (data) {
      if(data == "save"){
        message_alert('¡¡Observación registrada!!','success')
        get_entregas_ordenes();
        $("#accion").val('')
        $('#estado_llamada').val(null).trigger('change');
      }else{
        message_alert('¡¡Error, no se ha registrado la acción!!','error')
      }
      $("#modal_add_phone").modal('show');
      //Carga datos en datable
      dt_acc_entrega(id_accion_optica)
    }//Fin success
  });//Fin ajax
}

function show_modal_add_phone(id_acc,tel,paciente,dui,sucursal,codigo) {
  $("#name_paciente").html(paciente)
  $("#codigo").val(codigo)
  $("#tel_principal").html('<i class="fas fa-phone mr-2"></i> Tel: ' + tel)
  $("#paciente_sucursal").html("SUCURSAL: " + sucursal)
  $("#id_accion_optica").val(id_acc);
  $("#modal_add_phone").modal('show');
  $("#accion").val('')
  $("#accion").focus()
  //Obtener el numero opcional de citas
  get_citas_tel(dui);
  //Carga el datatable
  dt_acc_entrega(id_acc)
}

function dt_acc_entrega(id_acc) {
  $("#dt_acc_phone").html('');
  $.ajax({
    url: "../ajax/entregas.php?op=get_acc_entregas",
    method: "POST",
    cache: false,
    data: {id_acc: id_acc},
    dataType: "json",
    success: function (data) {
      let filas = '';
      if(data.length > 0){
        for (var i = 0; i < data.length; i++) {
          filas = filas + "<tr id='fila" + i + "'>" +
            "<td '>" + data[i].id_acc_entrega + "</td>" +
            "<td '>" + data[i].estado_llamada + "</td>" +
            "<td style='max-width: 250px; overflow: auto;' '>" + data[i].accion + "</td>" +
            "<td '>" + data[i].fecha + "</td>" +
            "<td '>" + data[i].hora + "</td>" +
            "<td '>" + data[i].usuario + "</td>" +
            "</tr>";
        }
      $("#dt_acc_phone").html(filas);
      }
    }//Fin success
  });//Fin ajax
}

function get_citas_tel(dui_paciente){
  $.ajax({
    url: "../ajax/entregas.php?op=get_cita_tel",
    method: "POST",
    cache: false,
    data: {dui_paciente:dui_paciente},
    dataType: "json",
    success: function (data) {
      if(data != 'null'){
        $("#tel_opcional").html('<i class="fas fa-phone mr-2"></i>Tel (opcional): ' + data.tel_opcional)
      }else{
        $("#tel_opcional").html('')
      }
    }//Fin success
  });//Fin ajax
}

//Control de change de input  de estado
/*let estadosLLamadas = ['No contesto','Número equivocado','Mando a buzón de voz','Cambio de número','Ya contestó','Enterado','Otros']
$(document).ready(function () {
  $("#estado_llamada").select2({ 
    data: estadosLLamadas, 
    maximumSelectionLength: 1,
    placeholder: 'Seleccionar...',
  })
});*/