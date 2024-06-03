var itemsActas = []; //Para guardar los items de actas
const receptores_section = document.getElementById("receptores-section");
document.querySelectorAll(".chk-recept").forEach(i => i.addEventListener("click", e => {
    let receptor = $("input[type='radio'][name='receptor-acta']:checked").val();
    receptor=="tercero" ? receptores_section.style.display="flex" : receptores_section.style.display="none";
    if(receptor=='tercero'){
       $("#receptor-acta").val('')
       $("#receptor-dui").val('')
    }
  }));
  
  function modalImprimirActa(codigo,paciente,dui){
    $("#modal-actas").modal()
    document.getElementById("codigo-recep-orden").value=codigo;
    document.getElementById("pac-recep-orden").value=paciente;
    document.getElementById("dui-acta").value=dui;
    $(':checkbox').each(function () {
      $('input[type="radio"]').prop('checked', false);
    });
    $("#receptor-acta").val('');
    $("#receptor-dui").val('');
    receptores_section.style.display="none";

  }
try{
  let btn_print = document.getElementById('btn-print-acta');
  
  btn_print.addEventListener("click", function() {
    let receptor = $("input[type='radio'][name='receptor-acta']:checked").val();
    let nombre_receptor = document.getElementById('receptor-acta').value;
    let dui_receptor = document.getElementById('receptor-dui').value;
    let dui_acta = document.getElementById('dui-acta').value;
    let titular = document.getElementById('pac-recep-orden').value;
    let codigo_orden = document.getElementById('codigo-recep-orden').value;
    let sucursal = document.getElementById('sucursal').value;
    let id_usuario = document.getElementById('id_usuario').value;
    //console.log(receptor,nombre_receptor,dui_receptor,dui_acta,titular,codigo_orden,sucursal,id_usuario); return 0;
    if(receptor==undefined){
      Swal.fire({position: 'top-center',icon: 'error',title: 'Especificar tipo de receptor',showConfirmButton: true,
        timer: 1500
    });
      return false;
    }

    if(receptor !="tercero"){nombre_receptor=titular}
  
    if(receptor=='tercero' && (nombre_receptor=='' || dui_receptor=='')){
      Swal.fire({position: 'top-center',icon: 'error',title: 'DUI y nombre de receptor son obligatorios',showConfirmButton: true,
        timer: 1500
      });
      return false;
    }
    
   
    $.ajax({
    url: "../ajax/actas.php?op=crear_acta",
    method: "POST",
    data: {codigo_orden:codigo_orden,titular:titular,nombre_receptor:nombre_receptor,receptor:receptor,sucursal:sucursal,id_usuario:id_usuario,dui_acta:dui_acta,dui_receptor},
    dataType: "json",
    success: function (data) {
     $("#modal-actas").modal('hide');
     let id_acta = data.id;
     let correlativo_suc = data.correlativo_sucursal;
     imprimirActa(nombre_receptor,dui_receptor,titular,codigo_orden,receptor,id_acta,correlativo_suc,dui_acta);
     $("#ordenes_recibidas_data").DataTable().ajax.reload();
    }
  });//Fin Ajax
  });
  
} catch(err) {
  if (err instanceof ReferenceError) {
    console.log('File not found in this js or is under development')
  }
}
  
  function imprimirActa(nombre_receptor,dui_receptor,paciente,codigo,tipo_receptor,id_acta,correlativo_suc,dui_acta,reimpresion="") {
    
    let form = document.createElement("form");
    form.target = "blank";
    form.method = "POST";
    if(reimpresion == "Si"){
      form.action = "reimpresion_acta.php";
    }else{
      form.action = "imprimir_acta.php";
    }

    let objData = {
      nombre_receptor,dui_receptor,paciente,codigo,tipo_receptor,id_acta,correlativo_suc,dui_acta
    }    

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "actData";
    input.value = JSON.stringify(objData);
    form.appendChild(input);   
  
    document.body.appendChild(form);//"width=600,height=500"
    form.submit();
    document.body.removeChild(form);
  }
  
  
/**
 * CODE PARA DATATABLE DE ACTAS
*/
//template para datatable
function dt_template(id_html, url, data = {}, paginacion = 50,sumColumn=0) {
  $('#' + id_html).DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/actas.php?op=" + url,
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
      $(api.column().footer()).html(`Total:  ${sumCurrentPage} de ${sumTotal} actas`)
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": paginacion,//Por cada 10 registros hace una paginación
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
//Validacion de input
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
//Template Message alert
function alert_message(message, type, timer = 2500) {
  Swal.fire({
    position: 'center',
    icon: type,
    title: message,
    showConfirmButton: true,
    timer: timer
  });
}

//Load datatable
document.addEventListener('DOMContentLoaded', () => {
  //Si incluye el permiso mostrar todos los datos
  let listado_general = names_permisos.includes('listado_general_actas') ? "Ok" : ""
  dt_template('dtable_actas','get_actas_generadas',{listado_general:listado_general})
})
function filter_sucursal_acta(){
  let sucursal = document.getElementById('filter_sub').value
  dt_template('dtable_actas','get_actas_generadas',{sucursal:sucursal})
}

function acta_edit(dui_paciente){
  allowEditInput('id_acta')//ReadOnly input
  $("#title_modal_acc").html('Acta - Información')
  $("#modal_acta_show_edit").modal('show')
  $.ajax({
    url: "../ajax/actas.php?op=get_acta_id",
    method: "POST",
    data: {dui_paciente: dui_paciente} ,
    cache: false,
    dataType: "json",
    success: function (data) {
      $("#id_cita").val(data.id_cita)
      $("#id_acta").val(data.id_acta)
      $("#cod_orden").val(data.codigo_orden)
      $("#paciente-benef").val(data.beneficiario)
      $("#dui-vet").val(data.dui_acta)
      $("#tipo-pac").val(data.tipo_paciente)
      $("#sector-pac").val(data.sector)
      $("#name-receptor").val(data.receptor)
      let contenedorDUI = document.getElementById('contentInput')
      let contentReceptor = document.getElementById('content-Receptor')
      if(data.tipo_receptor === 'tercero'){
        contenedorDUI.classList.add('d-block')
        contentReceptor.classList.replace('col-md-5','col-md-4')
        console.log('hello')
      }else{
        contenedorDUI.classList.replace('d-block','d-none')
        contentReceptor.classList.replace('col-md-4','col-md-5')
        console.log('hello2')
      }
      $("#dui_receptor").val(data.dui_receptor)
      $("#fecha_impresion").val(data.fecha_impresion)
      if(data.tipo_paciente == "Designado" || data.tipo_paciente == "Conyuge" || data.sector == "CONYUGE"){
        $("#vet_titular").val(data.vet_titular)
        $("#dui_titular").val(data.dui_titular)
        document.getElementById('vet_titular').classList.add('oblig')
        document.getElementById('dui_titular').classList.add('oblig')
        document.getElementById('tipo-pac').classList.remove('oblig')
        //disabled true input
        $("#vet_titular").removeAttr('readonly')
        $("#dui_titular").removeAttr('readonly')
      }else{
        //Disabled input
        $("#vet_titular").attr('readonly','true')
        $("#dui_titular").attr('readonly','true')
        //clear input
        $("#vet_titular").val('')
        $("#dui_titular").val('')
        document.getElementById('vet_titular').classList.remove('oblig')
        document.getElementById('dui_titular').classList.remove('oblig')
        document.getElementById('tipo-pac').classList.remove('oblig')
      }
      //Limpiar el is-invalid en input
      input_oblig()
    }
  });
}

//Editar acta
try{
  function update_acta(){
    //Validacion de permisos
    let edicionActa = names_permisos.includes('editar_acta') //Verificar permiso
    if(!edicionActa){
      alert_message('Permisos insuficientes para realizar esta acción')
      return 1; //Salida forzada
    }
    let id_cita = $("#id_cita").val()
    let id_acta = $("#id_acta").val()
    let cod_orden = $("#cod_orden").val()
    let dui = $("#dui-vet").val()
    dui = dui.trim()
    let beneficiario_ac = $("#paciente-benef").val()
    beneficiario_ac = beneficiario_ac.trim()
    let receptor_ac = $("#name-receptor").val()
    receptor_ac = receptor_ac.trim()
    //Datos de cita
    let dui_titular = $("#dui_titular").val()
    let vet_titular = $("#vet_titular").val()
    //Fecha  de impresion de acta
    let fecha_impresion = $("#fecha_impresion").val()
    //BTN DISABLED
    $("#edit_acta").attr('disabled',true)
    $("#edit_acta").html('Procesando...')
    //Validacion de input
    if(input_oblig()){
      alert_message('Existen campos vacios','warning')
      $("#edit_acta").attr('disabled',false)
      $("#edit_acta").html('Editar')
      return 1; //Salida forzada
    }
    //Update acta
    let dui_receptor = document.getElementById('dui_receptor').value;

    $.ajax({
      url: "../ajax/actas.php?op=update_acta",
      method: "POST",
      data: {id_cita:id_cita, id_acta: id_acta,cod_orden:cod_orden,dui:dui,beneficiario: beneficiario_ac,receptor: receptor_ac,dui_titular: dui_titular,vet_titular: vet_titular,fecha_impresion:fecha_impresion,dui_receptor},
      cache: false,
      dataType: "json",
      success: function (data) {
        if(data == "update"){
          alert_message('¡Datos actualizados con exito!','success',2500)
        }else{
          alert_message('Upps, error al actualizar los datos!','error',2500)
        }
        $("#edit_acta").html('Editar')
        $("#edit_acta").attr('disabled',false)
        $("#modal_acta_show_edit").modal('hide')
        $("#dtable_actas").DataTable().ajax.reload(null, false);
      }
    });
  }
}catch(err){
  if(err instanceof ReferenceError){
    console.log('ReferenceError')
  }
}
function mayus(e) {
  e.value = e.value.toUpperCase();
}
//Generar PDF ACTA
function genrar_pdf_acta(id_acta){
  $.ajax({
    url: "../ajax/actas.php?op=get_data_acta_pdf",
    method: "POST",
    data: {id_acta: id_acta},
    cache: false,
    dataType: "json",
    success: function (data) {
      //Geneara el pdf para actas
      let permImprimirActa = names_permisos.includes('imprimir_acta') //Verificar permiso
      if(permImprimirActa){
        imprimirActa(data.receptor,'00000000-0',data.beneficiario,data.codigo_orden,data.receptor,data.id_acta,data.correlativo_sucursal,data.dui_acta,"Si");
      }else{
        alert_message('Permisos insuficientes para realizar esta acción')
      }
    }
  });
}
/**
 * END ACTAS
*/

/**
 * CODE PARA CONTROL DE ACTAS
 * 
*/
//dtable_control_actas
document.addEventListener('DOMContentLoaded', ()=>{
  let permi_listado_general = names_permisos.includes('listado_general_actas') ? "Ok" : '';
  dt_template('dtable_control_actas','getNumberOfActas',{permi_listado_general: permi_listado_general},25,4)
})
try{
  let btn_entregar_acta = document.getElementById('add_ent_acta')
  btn_entregar_acta.addEventListener('click',view_modal_entrega_acta)
}catch(err){
  if(err){
    console.log(err)
  }
}

function view_modal_entrega_acta(){
  $("#modal_show_entrega").modal('show')
  $("#id_acta").val("")
  document.getElementById('id_acta').focus()
  //Vaciar el datatable
  itemsActas = []
  show_items_actas();
}
function getActaOrden() {
  let id_acta = $("#id_acta").val();
  //get session user
  let sucursal = $("#sucursal").val()
  $.ajax({
    url: "../ajax/actas.php?op=get_acta_orden",
    method: "POST",
    data: { id_acta: id_acta,sucursal:sucursal },
    cache: false,
    dataType: "json",
    success: function (data) {
      if(data.length == 0){
        alert_message('¡No existe la acta!','error',2500)
        $("#id_acta").val("")
        document.getElementById('id_acta').focus()
        return 0//Ouput 0
      }else if(data == "existe"){
        alert_message('¡Esta acta ya fue entregada!','warning',2500)
        $("#id_acta").val("")
        document.getElementById('id_acta').focus()
        return 0;
      }
      comprobarItemsDuplicados(data)
    }
  });//Fin Ajax 

}
function comprobarItemsDuplicados(data){
  let id_acta = data.id_acta;
  let indice = itemsActas.findIndex((objeto, indice, items_tallado) => {
    return objeto.id_acta == id_acta;
  });

  if (indice >= 0) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Orden ya existe en la lista',
      showConfirmButton: true,
      timer: 1000
    });
    $("#id_acta").val("")
    document.getElementById('id_acta').focus()
    return 0 // output
  }
  let itemOrden = {
    id_acta: data.id_acta,
    fecha: data.fecha,
    paciente: data.paciente,
    dui: data.dui,
    tipo_paciente: data.tipo_paciente,
    sector: data.sector,
    sucursal: data.sucursal
  }
  itemsActas.push(itemOrden)
  $("#id_acta").val("")
  document.getElementById('id_acta').focus()
  show_items_actas();
}
function show_items_actas() {

  $("#items-ordenes-barcode").html('');

  let filas = "";
  let length_array = parseInt(itemsActas.length) - 1;
  let count = itemsActas.length + 1
  for (let i = length_array; i >= 0; i--) {
    count -= 1
    filas = filas +
      "<tr style='text-align:center' id='item_t" + i + "'>" +
      "<td>" + count + "</td>" +
      "<td>" + itemsActas[i].id_acta + "</td>" +
      "<td>" + itemsActas[i].fecha + "</td>" +
      "<td>" + itemsActas[i].dui + "</td>" +
      "<td>" + itemsActas[i].paciente + "</td>" +
      "<td>" + itemsActas[i].tipo_paciente + "</td>" +
      "<td>" + itemsActas[i].sector + "</td>" +
      "<td>" + itemsActas[i].sucursal + "</td>" +
      "<td>" + "<button type='button'  class='btn btn-sm bg-light' onClick='eliminarItemBarcodeLab(" + i + ")'><i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i></button>" + "</td>" +
      "</tr>";
  }

  $("#items-entregas-actas").html(filas);

}

function eliminarItemBarcodeLab(index) {
  $("#item_t" + index).remove();
  drop_index(index);
}

function drop_index(position_element) {
  itemsActas.splice(position_element, 1);
  $('#reg_ingresos_barcode').focus();
  show_items_actas()
}
function show_modal_emisor(){
  if(itemsActas.length == 0){
    alert_message('¡Por favor, Ingresar las actas a entregar!','error',2500)
    return 0;
  }
  $("#modal-actas-entregas").modal('show')
  $("#fullname-emisor").focus()
}
function registrarEntregasActas(){
  //Session id user
  if(input_oblig()){
    alert_message('¡Por favor, rellenar el formulario!','error',2500)
    return 0;
  }
  let id_usuario = $("#id_usuario").val()
  let fullNameEmisor = $("#fullname-emisor").val()
  let fullNameReceptor = $("#fullname-receptor").val()
  $.ajax({
    url: "../ajax/actas.php?op=save_entregas_actas",
    method: "POST",
    data: { data: itemsActas,id_usuario:id_usuario,fullNameEmisor:fullNameEmisor,fullNameReceptor:fullNameReceptor },
    cache: false,
    dataType: "json",
    success: function (data) {
      let cod_entrega = data.cod_entrega
      if(data.message == "exito"){
        alert_message('¡Las actas se entregaron correctamente!','success',2500)
        document.getElementById('id_acta').focus()
        //Generar PDF
        let objData = {
          codigo_entrega : cod_entrega
        }
        imprimir_pdf(objData);
        //value vacios
        $("#id_acta").val("")
        $("#fullname-emisor").val("")
        $("#fullname-receptor").val("")
        //modal hide
        $("#modal-actas-entregas").modal('hide')
        itemsActas = []
        show_items_actas();
      }
      $("#dtable_control_actas").DataTable().ajax.reload();
    }
  });//Fin Ajax 
}

function imprimir_pdf(data) {

  let form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  form.action = "imprimir_entregas_actas.php";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "actData";
  input.value = JSON.stringify(data);
  form.appendChild(input);

  document.body.appendChild(form);//"width=600,height=500"
  form.submit();
  document.body.removeChild(form);
}

//Ver informacion de las actas en modal
function showActasEntregadas(cod_entrega_actas){
  $("#modal_show_actas_entregadas").modal('show');
  //dtable_reemprimir_actas
  dt_template('dtable_reemprimir_actas','get_control_actas_all',{code: cod_entrega_actas})
  $("#codeEntregaActas").val(cod_entrega_actas)
}

function print_entrega_actas(){
  let code_entrega_actas = $("#codeEntregaActas").val()
  //Reemprimir entregas de actas
  let objData = {
    codigo_entrega : code_entrega_actas
  }
  imprimir_pdf(objData);
}



function dtTemplateResActas(table,route,...Args){
 
  tabla = $('#'+table).DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [     
      'excelHtml5',
    ],

    "ajax":{
      url:"../ajax/actas.php?op="+ route,
      type : "POST",
      data: {Args:Args},
      dataType : "json",
      beforeSend: function() {
        $('#loader').show();
      },
      complete: function() {
        $('#loader').hide();
      },
      error: function(e){
      console.log(e.responseText);
    },      
  },

    "bDestroy": true,
    "responsive": true,
    "bInfo":true,
    "iDisplayLength": 100,//Por cada 10 registros hace una paginación
      "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
      "language": { 
      "sProcessing":     "Procesando...",       
      "sLengthMenu":     "Mostrar _MENU_ registros",       
      "sZeroRecords":    "No se encontraron resultados",       
      "sEmptyTable":     "Ningún dato disponible en esta tabla",       
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",       
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",       
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",    
      "sInfoPostFix":    "",       
      "sSearch":         "Buscar:",       
      "sUrl":            "",       
      "sInfoThousands":  ",",       
      "sLoadingRecords": "Cargando...",       
      "oPaginate": {       
          "sFirst":    "Primero",       
          "sLast":     "Último",       
          "sNext":     "Siguiente",       
          "sPrevious": "Anterior"       
      },   
      "oAria": {       
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",       
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"   
      }}, //cerrando language
  });

   
}
/**
 *END CODE CONTROL DE ACTAS 
*/

function filtrarActasResumen(){
  let desde = document.getElementById('f_desde_act').value;
  let hasta = document.getElementById('f_hasta_act').value;
  let param = '';
  if(desde=='' && hasta==''){
    $('#loader').show();
    dtTemplateResActas('dtable_actas_resumen','get_resumen_actas','0',desde,hasta)
  }else if(desde != '' && hasta ==''){
    $('#loader').hide();
    alert_message('¡Rangos invalidos!','warning',2500)
  }else if(desde == '' && hasta !=''){
    $('#loader').hide();
    alert_message('¡Rangos invalidos!','warning',2500)
  }else if(desde != '' && hasta !=''){
    $('#loader').show();
    dtTemplateResActas('dtable_actas_resumen','get_resumen_actas','1',desde,hasta)
  } 

}

/**
 * Habilitar la opcion de editar id_acta
 */
let stateInput = false;
function allowEditInput(id){
  let input = document.getElementById(id)
  if(stateInput){
    input.removeAttribute('readonly')
    stateInput = false
  }else{
    input.setAttribute('readonly',true)
    stateInput = true
  }
}