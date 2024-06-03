var items_barcode = []
$(document).ready(() => {
  get_ordenes_bodegas();
  listar_ordenes_procesando_lab()
  get_dt_ordenes_finalizadas_lab()
  get_dt_ordenes_enviadas()
  listar_ordenes_enviadas()
  listar_ordenes_reenvio()
})
//Cambio de encabezado en modal

$(document).on('click',"#btn-procesando", ()=>{
  $("#c_accion").html('Finalizar ordenes')
})
$(document).on('click','#btn-enviar-ordenes', ()=>{
  $("#c_accion").html('Enviar ordenes a laboratorio LENTI')
})
function show_modal_recibir_orden() {
  $("#moda_recibir_ordenes").modal('show')
  $("#c_accion").html('Recibir ordenes del laboratorio LENTI');
  //FOCUS input
  $("#cod_reenvio").focus();
  items_barcode = []
  show_items_barcode_lab()
}
/**
 * PLANTILLAS
 * 
*/
//template para datatable
function alert_message(message, type, timer = 2500) {
  Swal.fire({
    position: 'top-center',
    icon: type,
    title: message,
    showConfirmButton: true,
    timer: timer
  });
}
function dt_template(id_html, url, data = {},cant_paginacion=25,sumColumn=0) {
  $('#' + id_html).DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/bodega_av_plus.php?op=" + url,
      type: "POST",
      dataType: "json",
      data: data,
      error: function (e) {
        console.log(e.responseText);
      },
    },
    drawCallback: function(){
      var api = this.api();
      let sumTotal = api.column(sumColumn).data().sum()
      let sumCurrentPage = api.column(sumColumn,{page:'current'}).data().sum()
      $(api.column().footer()).html(`Total:  ${sumCurrentPage} de ${sumTotal} ordenes`)
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": cant_paginacion,//Por cada 10 registros hace una paginación
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
function getOrdenRecibir(dui = "") {
  let cod_reenvio = $("#cod_reenvio").val();
  cod_reenvio = cod_reenvio.replace("'", "-");
  cod_reenvio = document.getElementById('cod_reenvio').value = cod_reenvio

  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=get_ordenes_pendientes",
    method: "POST",
    data: { cod_reenvio: cod_reenvio },
    cache: false,
    dataType: "json",
    success: function (data) {
      //console.log(data)
      let resultados = typeof data;
      $("#reg_ingresos_barcode").focus()
      if (resultados == 'object') {
        getDataOrdenes(resultados, data);
      }
    }
  });//Fin Ajax 

}

function getDataOrdenes(resultados, data) {
  items_barcode = data;
  let count = data.length
  if (count == 0) {
    alert_message('¡Las ordenes ya fueron recibidas!', 'warning')
  }
  input_focus_clearb();
  //Lista las ordenes
  show_items_barcode_lab()
}


function input_focus_clearb() {
  $("#cod_reenvio").val("");
  $("#reg_ingresos_barcode").val('')

  $('#cod_reenvio').focus();
  $("#reg_ingresos_barcode").focus()
}


function show_items_barcode_lab() {

  $("#items-ordenes-barcode").html('');

  let filas = "";
  let length_array = parseInt(items_barcode.length) - 1;
  let count = items_barcode.length + 1
  //console.log(items_barcode)
  for (let i = length_array; i >= 0; i--) {
    count -= 1
    filas = filas +
      "<tr style='text-align:center' id='item_t" + i + "'>" +
      "<td>" + count + "</td>" +
      "<td>" + items_barcode[i].codigo + "</td>" +
      "<td>" + items_barcode[i].fecha + "</td>" +
      "<td>" + items_barcode[i].dui + "</td>" +
      "<td>" + items_barcode[i].paciente + "</td>" +
      "<td>" + items_barcode[i].cod_envio + "</td>" +
      "<td>" + items_barcode[i].sucursal + "</td>" +
      "<td>" + "<button type='button'  class='btn btn-sm bg-light' onClick='eliminarItemBarcodeLab(" + i + ")'><i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i></button>" + "</td>" +
      "</tr>";
  }

  $("#items-ordenes-barcode").html(filas);

}

function eliminarItemBarcodeLab(index) {
  $("#item_t" + index).remove();
  drop_index(index);
}

function drop_index(position_element) {
  items_barcode.splice(position_element, 1);
  $('#reg_ingresos_barcode').focus();
  show_items_barcode_lab()
}
//========EN DESARROLLO============
function registrarRecibido() {
  let tipo_accion = $("#tipo_accion").val();
  let n_ordenes = items_barcode.length;

  //Disabled button enviar
  $("#btn_recibir").attr('disabled', true);
  if (n_ordenes == 0) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Lista vacia',
      showConfirmButton: true,
      timer: 1500
    });
    $("#btn_recibir").attr('disabled', false);
    return false;
  }

  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=procesar_orden",
    method: "POST",
    data: { arrayData: items_barcode, tipo_accion: tipo_accion },
    cache: false,
    dataType: "json",
    success: function (data) {
      //console.log(data)
      if (data == "ok") {
        alert_message('Ordenes recibidas', 'success');
        $("#btn_recibir").attr('disabled', false);
      }
      items_barcode = [];
      show_items_barcode_lab()
      $("#dt_ordenes_recibidas").DataTable().ajax.reload();

    }//Fin success
  });
}

function get_ordenes_bodegas(sucursal = '') {
  dt_template('dt_ordenes_recibidas', 'get_ordenes_bodega', { sucursal: '' })
}

//FUNCIONES PARA PROCESANDO EN BODEGA AV PLUS

function listar_ordenes_procesando_lab(sucursal = "") {
  if (sucursal == "") {
    dt_template('ordenes_procesando_lab', 'get_ordenes_procesando_lab', { sucursal: "" })
  } else {
    dt_template('ordenes_procesando_lab', 'get_ordenes_procesando_lab', { sucursal: sucursal })
  }
}
function filter_sucursal() {
  $("#filter_sucursal option:selected").each(function () {
    let sucursal = $(this).val();
    listar_ordenes_procesando_lab(sucursal)
    get_ordenes_bodegas(sucursal)
  });

}

$("#btn_proceso").on('click', () => {
  $("#modal_procesando").modal('show')
})

function getOrdenBarcode(dui = "") {
  let paciente_dui = $("#reg_ingresos_barcode").val();
  paciente_dui = paciente_dui.replace("'", "-");
  paciente_dui = document.getElementById('reg_ingresos_barcode').value = paciente_dui
  let tipo_accion = $("#tipo_accion").val();
  let search_id = ""
  if (document.getElementById('check_ingreso_id').checked) {
    search_id = "search_id"
  }

  if (paciente_dui == "") {
    paciente_dui = dui
  }

  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=get_data_orden_barcode",
    method: "POST",
    data: { paciente_dui: paciente_dui, tipo_accion: tipo_accion, search_id: search_id },
    cache: false,
    dataType: "json",
    success: function (data) {
      //console.log(data)
      if (data.estado == "rectificacion") {
        Swal.fire({
          title: "¿Se trata de una rectificación?",
          text: 'El trabajo ya ha sido ingresado anteriormente',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire(
              '¡El expediente se actualizará!',
            )
            let resultados = typeof data;
            $("#reg_ingresos_barcode").focus()
            if (resultados == 'object') {
              get_data_ordenes(resultados, data);
            }
          } else {
            $("#reg_ingresos_barcode").val('')
            $("#reg_ingresos_barcode").focus()
          }
        })
      } else {
        let resultados = typeof data;
        $("#reg_ingresos_barcode").focus()
        if (resultados == 'object') {
          get_data_ordenes(resultados, data);
        }
      }


    }//Fin success
  });//Fin Ajax 

}
function get_data_ordenes(resultados, data) {

  let longitudObject = data.length;

  if (resultados != "string" && longitudObject != 0) {
    let codigo = data.codigo;
    let indice = items_barcode.findIndex((objeto, indice, items_tallado) => {
      return objeto.n_orden == codigo;
    });

    if (indice >= 0) {
      Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'Orden ya existe en la lista',
        showConfirmButton: true,
        timer: 1000
      });
      input_focus_clearb();
    } else {
      let items_ingresos = {
        n_orden: data.codigo,
        codigo: data.codigo,
        dui: data.dui,
        paciente: data.paciente,
        fecha: data.fecha,
        id_orden: data.id_orden,
        cod_envio: data.cod_envio,
        estado: data.estado,
        sucursal: data.sucursal,
      }
      items_barcode.push(items_ingresos)
      show_items_barcode_lab();
      input_focus_clearb();
    }
  } else {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'No existe el paciente',
      showConfirmButton: true,
      timer: 1000
    });
    input_focus_clearb();
  }
}
function registrarBarcodeOrdenes() {

  let tipo_accion = $("#tipo_accion").val();
  var ubicacion_orden = ''
  let usuario = $("#usuario").val();
  let correlativo_accion = $("#correlativo_acc_vet").val();
  let n_ordenes = items_barcode.length;
  //Disabled button enviar
  $("#btn_estado").attr('disabled', true);
  if (n_ordenes == 0) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Lista vacia',
      showConfirmButton: true,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=procesar_ordenes_estado",
    method: "POST",
    data: { 'arrayOrdenesBarcode': JSON.stringify(items_barcode), 'usuario': usuario, 'tipo_accion': tipo_accion, 'ubicacion_orden': ubicacion_orden, 'correlativo_accion': correlativo_accion },
    cache: false,
    dataType: "json",
    success: function (data) {
      if (tipo_accion === "proceso" && data == "Ok") {
        alert_message('Ordenes finalizadas', 'success')
        $("#ordenes_procesando_lab").DataTable().ajax.reload();
      } else if (tipo_accion == "finalizadas" && data.message == "Ok") {
        alert_message('Ordenes enviadas a ópticas', 'success')
        $("#ordenes_finalizadas_lab").DataTable().ajax.reload();
        //Imprimir PDF
        imprimir_detalle_ordenes_envio(data.cod_envios)//
      } else if (tipo_accion == "reenvio" && data == "Ok") {
        $("#ordenes-reenvio").DataTable().ajax.reload();
        alert_message('Ordenes reenviadas a laboratorio lenti', 'success')
      } else {

      }
      $("#btn_estado").attr('disabled', false);
      $("#items-ordenes-barcode").html('')
      items_barcode = [];
    }//Fin success
  });
}
//GENERA EL PDF PARA ENVIOS
function imprimir_detalle_ordenes_envio(data) {

  var form = document.createElement("form");
  form.target = "blank";
  form.method = "POST";
  form.action = "imprimir_ordenes_enviadas_avplus.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "data";
  input.value = JSON.stringify(data);
  form.appendChild(input);
  document.body.appendChild(form);//"width=600,height=500"

  form.submit();
  document.body.removeChild(form);

}


function get_dt_ordenes_finalizadas_lab() {
  dt_template('ordenes_finalizadas_lab', 'get_ordenes_finalizadas_lab', { sucursal: '' },500)
}

function get_dt_ordenes_enviadas() {
  dt_template('ordenes_enviadas_lab', 'get_ordenes_enviadas_lab', { sucursal: '' })
}

function listar_ordenes_enviadas() {
  dt_template('ordenes-de-envio', 'listar_ordenes_de_envio',{},25,4);
}

function show_ordenes_enviadas(codigo_bodega,sucursal) {
  //Para generar el pdf
  $("#cod_envio").val(codigo_bodega)
  $("#sucursal").val(sucursal)
  $("#listarOrdenesEnviadas").modal('show')
  dt_template('dt_ordenes_enviadas', 'listar_ordenes_enviadas', { codigo_bodega: codigo_bodega })
}

function generar_pdf_ordenes_envios() {
  let cod_envio = $("#cod_envio").val() //carga el value del input del reporte
  let sucursal = $("#sucursal").val()
  //Structura para generar PDF
  let data = [
    {
      codigo_envio: cod_envio,
      sucursal: sucursal
    }
  ]
  imprimir_detalle_ordenes_envio(data)
}

function listar_ordenes_reenvio() {
  dt_template('ordenes-reenvio', 'listar_cantidad_ordenes_reenviadas',{},25,4)
}

function show_ordenes_reenviadas(cod_bodega) {
  $("#cod_reenvio").val(cod_bodega)
  $("#listarOrdenesReenviadas").modal('show');
  dt_template('dt_ordenes_reenviadas', 'get_ordenes_reenviadas_reporte', { cod_bodega: cod_bodega })
}

function generar_pdf_ordenes_reenvios() {
  let code_reenvio = $("#cod_reenvio").val()
  imprimirEnviosLabPDF(code_reenvio,'imprimirReenviosLenti.php')
}

//Imprimi
function imprimirEnviosLabPDF(code_reenvio,file_path) {

  var form = document.createElement("form");
  form.target = "blank";
  form.method = "POST";
  form.action = file_path;

  //Codigo de reenvio
  var input_code = document.createElement("input")
  input_code.type = "hidden"
  input_code.name = "code_reenvio"
  input_code.value = code_reenvio
  //Agregamos al padre el input
  form.appendChild(input_code)
  document.body.appendChild(form);//"width=600,height=500"

  form.submit();
  document.body.removeChild(form);

}
//SELECCIONAR TODAS LAS ORDENES
function selected_all_orden() {
  let check_all = document.getElementById('check_all').checked
  let check_selecteds = document.getElementsByClassName('check_selected');
  if (check_all) {
    for (let i = 0; i < check_selecteds.length; i++) {
      let dui_paciente = check_selecteds[i].dataset.dui
      getOrdenBarcode(dui_paciente);
      let input_check = document.getElementById(check_selecteds[i].id).checked = true;
    }
  } else {
    for (let i = 0; i < check_selecteds.length; i++) {
      let input_check = document.getElementById(check_selecteds[i].id).checked = false;
    }
    let length_array = parseInt(items_barcode.length) - 1;
    for (let i = length_array; i >= 0; i--) {
      eliminarItemBarcodeLab(i)
    }
    $("#items-ordenes-barcode").html(filas);

  }
}

function verOrdenLaboratorio(dui) {
  $("#nueva_orden_lab").modal('show');
  $(".collapse").collapse('hide'); //DataTables De acciones
  $.ajax({
    url: "../ajax/laboratorios.php?op=get_data_orden",
    method: "POST",
    cache: false,
    data: { dui: dui },
    dataType: "json",
    success: function (data) {
        //Ocultar BTN para ingreso manual y edicion espec...
      document.getElementById('btn_edit_especial').style.display = "none";
      document.getElementById('radio_button_orden').style.display = "none";
      //Ocultar btn para guardar y rectificaciones
      document.getElementById('order_create_edit').style.display = "none";
      document.getElementById('btn_rectificar').style.display = "none";
      //Ocultar buscar citado
      document.getElementById('btnBuscarCitado').style.display = "none";
      //$("#fecha_creacion").val(data.fecha);
      $("#odesferasf").val(data.od_esferas);
      $("#odcilindrosf").val(data.od_cilindros);
      $("#odejesf").val(data.od_eje);
      $("#oddicionf").val(data.od_adicion);
      $("#oiesferasf").val(data.oi_esferas);
      $("#oicilindrosf").val(data.oi_cilindros);
      $("#oiejesf").val(data.oi_eje);
      $("#oiadicionf").val(data.oi_adicion);
      $("#od_pupilar").val(data.pupilar_od);
      $("#oipupilar").val(data.pupilar_oi);
      $("#odlente").val(data.lente_od);
      $("#oilente").val(data.lente_oi);
      $("#marca_aro_orden").val(data.marca);
      $("#modelo_aro_orden").val(data.modelo)
      $("#horizontal_aro_orden").val(data.horizontal_aro);
      $("#material_aro_orden").val(data.material);
      $("#observaciones_orden").val(data.puente_aro);
      $("#observaciones_orden").val(data.observaciones);
      $("#color_aro_orden").val(data.color);
      $("#categoria_lente").val(data.categoria);
      $("#destino_orden_lente").val(data.laboratorio);

      $("#usuario_pac").val(data.usuario_lente);
      $("#avsc").val(data.avsc);
      $("#avfinal").val(data.avfinal);
      $("#avsc_oi").val(data.avsc_oi);
      $("#avfinal_oi").val(data.avfinal_oi);

      $("#patologias-ord").val(data.patologias)

      $("#codigo_correlativo").val(data.codigo)
      $("#id_cita_ord").val(data.id_cita)
      $("#id_aro").val(data.id_aro)
      material_aro_orden
      $("#laboratorio").val(data.laboratorio)

      if (data.colorTratamiento == "Blanco") {
        document.getElementById("blanco").checked = true;
      }
      if (data.colorTratamiento == "Photocromatico") {
        document.getElementById("photo").checked = true;
      }
      $("#customSwitch1").prop('checked', true) // default en true
      let valueSwitch = $("input[name='customSwitch1']:checked").val();

      //Validation por cita
      if (data.id_cita != 0 || data.id_cita != "") {
        $("#customSwitch1").prop("checked", false);
        document.getElementById('show_form_manual').style.display = "none"
        document.getElementById('tables_cita').style.display = "block"
        $("#paciente_t").html(data.paciente);
        $("#dui_pac_t").html(data.dui);
        $("#edad_pac_t").html(data.edad);
        $("#correlativo_op").html("ORDEN:" + data.codigo);
        $("#telef_pac_t").html(data.telefono);
        $("#genero_pac_t").html(data.genero);
        $("#ocupacion_pac_t").html(data.ocupacion);
        $("#departamento_pac_t").html(data.depto);
        $("#munic_pac_data_t").html(data.municipio);
        $("#instit_t").html(data.institucion);

        $("#titular").val('');
        $("#dui_titular").val('')
        $("#titular_id").val('')

      } else {
        $("#customSwitch1").prop("checked", false);
        document.getElementById('tables_cita').style.display = "block"
        document.getElementById('id_cita_ord').value = 0
        document.getElementById('show_form_manual').style.display = "block"

        $("#paciente_t").html('');
        $("#dui_pac_t").html('');
        $("#edad_pac_t").html('');
        $("#correlativo_op").html("ORDEN:" + data.codigo);
        $("#telef_pac_t").html('');
        $("#genero_pac_t").html('');
        $("#ocupacion_pac_t").html('');
        $("#departamento_pac_t").html('');
        $("#munic_pac_data_t").html('');
        $("#instit_t").html('');

      }

      if (valueSwitch != null) {
        //Validacion si es por ingreso manual
        if (valueSwitch == "on") {
          paciente = $("#paciente").val(data.paciente)
          dui = $("#dui_pac").val(data.dui)
          edad = $("#edad_pac").val(data.edad)
          telefono = $("#telef_pac").val(data.telefono)
          genero = $("#genero_pac").val(data.genero)
          ocupacion = $("#ocupacion_pac").val(data.ocupacion)
          depto = $("#depto_pac").html(data.depto)
          municipio = $("#muni_pac_label").html(data.municipio)
          instit = $("#instit").val(data.institucion)
          $("#sucursal_optica").val(data.sucursal);
          //new
          $("#titular").val(data.titular);
          $("#dui_titular").val(data.dui_titular)
          $("#titular_id").val(data.id_titulares)
        }
      }
      if (data.institucion == "CONYUGE") {
        document.getElementById('titular_form').style.display = "block"
        $("#paciente_t").html(data.paciente);
        $("#dui_pac_t").html(data.dui);
        $("#edad_pac_t").html(data.edad);
        $("#correlativo_op").html("ORDEN:" + data.codigo);
        $("#telef_pac_t").html(data.telefono);
        $("#genero_pac_t").html(data.genero);
        $("#ocupacion_pac_t").html(data.ocupacion);
        $("#departamento_pac_t").html(data.depto);
        $("#munic_pac_data_t").html(data.municipio);
        $("#instit_t").html(data.institucion);
      } else {
        document.getElementById('titular_form').style.display = "none"
      }


      let tipo_lente = data.tipo_lente;
      const acentos = { 'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u', 'Á': 'A', 'É': 'E', 'Í': 'I', 'Ó': 'O', 'Ú': 'U' };
      let lente = tipo_lente.split('').map(letra => acentos[letra] || letra).join('').toString();
      let cadena = lente.replace(/ /g, "");

      document.getElementById(cadena).checked = true;

      let imagen = data.img;
      //console.log(imagen);
      document.getElementById("imagen_aro").src = "images/" + imagen;



    }
  });
}


/**
 * CODE PARA MODAL INGRESO MANUAL EN BODEGA
 */

function show_modal_ingreso_manual(){
  $("#modal_recibir_manual_ordenes").modal('show');
  //input para condicionar el ingreso manual
  $("#recib_manual_orden").val('recibir_manual')
  $("#dui").val('')
  document.getElementById('dui').focus();
  //Clear data
  items_barcode = [];
  show_ordenes_manual()
}
function get_recibir_manual() {
  let dui = $("#dui").val();
  dui = dui.replace("'", "-");
  dui = document.getElementById('dui').value = dui
  //Formater dui
  dui = dui.trim()
  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=get_recibir_orden_manual",
    method: "POST",
    data: { dui:dui },
    cache: false,
    dataType: "json",
    success: function (data) {
      if(data == "existe"){
        alert_message('¡Esta orden ya fue recibida!')
        clearFocus()
        return 0;
      } 
      if(data.length == 0){
        alert_message('¡Esta orden no existe!')
        clearFocus()
        return 0;
      }
      //Para verificar data duplicada
      let codigo = data.codigo;
      let indice = items_barcode.findIndex((objeto, indice, items_tallado) => {
        return objeto.n_orden == codigo;
      });
      if (indice >= 0) {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Orden ya existe en la lista',
          showConfirmButton: true,
          timer: 1000
        });
        clearFocus()
        return 0;
      }
      let items_recibir = {
        n_orden: data.codigo,
        codigo: data.codigo,
        dui: data.dui,
        paciente: data.paciente,
        fecha: data.fecha,
        cod_envio: data.cod_envio,
        sucursal: data.sucursal,
      }
      items_barcode.push(items_recibir)
      show_ordenes_manual();
      //focus()
      clearFocus()
    }
  });//Fin Ajax 

}
function clearFocus(){
  $("#dui").val('')
  document.getElementById('dui').focus();
}
function show_ordenes_manual() {
  $("#items-recibir-ordenes").html('');
  let filas = "";
  let length_array = parseInt(items_barcode.length) - 1;
  let count = items_barcode.length + 1
  for (let i = length_array; i >= 0; i--) {
    count -= 1
    filas = filas +
      "<tr style='text-align:center' id='item_t" + i + "'>" +
      "<td>" + count + "</td>" +
      "<td>" + items_barcode[i].codigo + "</td>" +
      "<td>" + items_barcode[i].fecha + "</td>" +
      "<td>" + items_barcode[i].dui + "</td>" +
      "<td>" + items_barcode[i].paciente + "</td>" +
      "<td>" + items_barcode[i].cod_envio + "</td>" +
      "<td>" + items_barcode[i].sucursal + "</td>" +
      "<td>" + "<button type='button'  class='btn btn-sm bg-light' onClick='eliminarItemBarcodeLab(" + i + ")'><i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i></button>" + "</td>" +
      "</tr>";
  }

  $("#items-recibir-ordenes").html(filas);

}

function registrar_recib_orden() {
  //Cantidad en array
  let n_ordenes = items_barcode.length
  //Disabled button enviar
  $("#btn-recib-manual").attr('disabled', true);
  $("#btn-recib-manual").html('Procesando...')
  if (n_ordenes == 0) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Lista vacia',
      showConfirmButton: true,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=procesar_recib_manual",
    method: "POST",
    data: { arrayData: items_barcode},
    cache: false,
    dataType: "json",
    success: function (data) {
      if (data == "ok") {
        alert_message('Ordenes recibidas', 'success');
        $("#btn-recib-manual").attr('disabled', false);
        $("#btn-recib-manual").html('Recibir')
      }
      items_barcode = [];
      show_ordenes_manual()
      $("#dt_ordenes_recibidas").DataTable().ajax.reload();

    }//Fin success
  });
}

