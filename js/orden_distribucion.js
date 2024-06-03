var arrayDataActas = [];
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
////Listar Actas
dtActasFirmadas("dt_distribucion_orden","getOrdenes","0")

function dtActasFirmadas(table,route,...Args) {
  console.log(Args)
  tabla = $('#'+table).DataTable({

    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    deferRender: true,
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/orden_distribucion.php?op="+route,
      type: "POST",
      dataType: "json",
      data:{Args:Args}
    },

    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 24,//Por cada 10 registros hace una paginación
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

function loadActaAmpo(id_acc) {
  let scan_acta = document.getElementById('id_dui_scan').value;

  if (scan_acta === "") {
    Swal.fire({
      position: 'center', icon: 'error', title: 'Se esperaba un DUI o Id Acta!!!', showConfirmButton: true,
      timer: 2500
    });
    return 0;
  }
  //clear_space
  scan_acta = scan_acta.trim();
  scan_acta = scan_acta.replace("'",'-');
  $.ajax({
    url: "../ajax/orden_distribucion.php?op=get_acta_dui_idActa",
    method: "POST",
    data: { busqueda: scan_acta },
    cache: false,
    dataType: "json",
    success: function (data) {
      if(data.length === 0){ 
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
      focusInput('id_dui_scan')
      let id_acta = data.acta[0].id_acta;
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
      let idActaArray = actas.map(function (item) {
        return item.id_acta;
      });
      let indice = idActaArray.findIndex(item => parseInt(item) === parseInt(id_acta));
      let bloque = Math.floor(indice / 125) + 1;
      ampo = `AMPO-${bloque}`;
      let objActa = {
        id_acta: id_acta,
        paciente: paciente,
        dui: dui,
        sucursal: sucursal,
        ampo: ampo
      }
      arrayDataActas.push(objActa);
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
    for (var i = 0; i < data.length; i++) {
      filas = filas + "<tr id='fila" + i + "'>" +
        "<td '>" + data[i].id_acta + "</td>" +
        "<td '>" + data[i].paciente + "</td>" +
        "<td '>" + data[i].ampo + "</td>" +
        "<td '>" + data[i].dui + "</td>" +
        "<td '>" + data[i].sucursal + "</td>" +
        "</tr>";
    }
    $("#actas-ampos").html(filas);
  }
}

function focusInput(id){
  document.getElementById(id).value = '';
  document.getElementById(id).focus();
}

/**
 * Clear table
 */

function clearTable(){
  arrayDataActas = [];
  $("#actas-ampos").html('');
}

var vinetas_ad = [];
function getRandomFechaSinFinesDeSemana(){
  let factura = document.getElementById("factura-update").value;
  console.log(factura)
  const fechasPorFactura = {
    1: ['2022-11-15', '2022-11-30'],
    2: ['2022-12-01', '2022-12-15'],
    3: ['2022-12-16', '2022-12-31'],
    4: ['2023-01-01', '2023-01-15'],
    5: ['2023-01-16', '2023-01-31'],
    6: ['2023-02-01', '2023-02-15'],
    7: ['2023-02-16', '2023-02-24'],
};

let fechas = fechasPorFactura[factura];
let unDia = 24 * 60 * 60 * 1000; 
let fechaInicioMs = new Date(fechas[0]).getTime(); 
let fechaFinMs = new Date(fechas[1]).getTime();
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

function checkvinetaAud(element = ""){
  let check = document.getElementById(element.id);
  let dui_paciente = check.dataset.dui;
  let nueva_fecha = getRandomFechaSinFinesDeSemana();
  //let sucursal = document.getElementById("suc-traslado").value;
  //console.log(nueva_fecha); return false;

  if (check.checked) {
    let objv = {
       dui : dui_paciente,
       new_date : nueva_fecha
    }
    vinetas_ad.push(objv)
   // console.log(vinetas_ad)
  } else {
      console.log("")
  }
}

function selected_all_ordenaud(){
  
  let check_all = document.getElementById('check_allad').checked
  let check_selecteds = document.getElementsByClassName('check_selectedaud');
  if (check_all) {
    for (let i = 0; i < check_selecteds.length; i++) {
      let dui_paciente = check_selecteds[i].dataset.dui
      document.getElementById(check_selecteds[i].id).checked = true;
      let nueva_fecha = getRandomFechaSinFinesDeSemana();
      let objv = {
        dui : dui_paciente,
        new_date : nueva_fecha
     }
     vinetas_ad.push(objv)
    }
    document.getElementById("cont-aud").innerHTML= vinetas_ad.length
  }else{
    for (let i = 0; i < check_selecteds.length; i++) {
      document.getElementById(check_selecteds[i].id).checked = false;
      vinetas_ad = [];
      document.getElementById("cont-aud").innerHTML= vinetas_ad.length
    }
  }
}


function destroyOrders(){
  if(vinetas_ad.length > 0){
    $.ajax({
      url:"../ajax/orden_distribucion.php?op=eliminar_ordenesad",
      method:"POST",
      data:{'arrayDist':JSON.stringify(vinetas_ad)},
      cache:false,
      dataType:"json",
      success:function(data){
        console.log(data)
        if(data.msj === "ok"){
          
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: ' se ha generado una orden de impresion!',
            showConfirmButton: true,
            timer: 1500
          });
          
          generarPDFNV();
          $("#dt_distribucion_orden").DataTable().ajax.reload(null, false);
        }

      }
    })
  }else{
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

function generarPDFNV(){
  let sucursal = document.getElementById("suc-traslado").value;
  var form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  form.action = "reimp_vinetas_aud.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "data";
  input.value = JSON.stringify(vinetas_ad);
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "sucursal";
  input.value = sucursal;
  form.appendChild(input);

  document.body.appendChild(form)
  form.submit();
  document.body.removeChild(form);
  vinetas_ad = [];
  console.log('*')

}

function getRessumenFactAtend(){
    
  $.ajax({
    url:"../ajax/orden_distribucion.php?op=get_resumen_fact_atend",
    method:"POST",
   // data:{sucursal,factura},
    cache:false,
    //dataType:"json",
    success:function(data)
    {
      $("#resumen-facturas-atend").html(data);
 
    }
  })

}

function getExpedientesExcedeFechas(){
  ordenes_upd_exp_fact = []
  let tipo_lente = document.getElementById("lente-act-update").innerHTML;
  let color = document.getElementById("color-act-update").innerHTML;
  let indice = document.getElementById("alto-indice-value").value;
  dtActasFirmadas("dt-upd-orders","get_ordenes_update",tipo_lente,color, indice)
}

getRessumenFactAtend()
var diferencia_act = 0;
function getdataActualization(tipo_lente,color, indice,diferencia,factura){
  document.getElementById("table-acciones-act").innerHTML =""
  uncheckedOptionsUpdate()
  ordenes_upd_exp_fact = []
  document.getElementById("count-expedientesf").innerHTML = ordenes_upd_exp_fact.length;
  $("#modal-actualization").modal();
  document.getElementById('factura-update').value = factura;
  diferencia_act = diferencia;
  document.getElementById("dif-expedientesf").innerHTML = diferencia_act;
  let ai = '';
  if(indice=='Si'){
    ai='(mayor a +/- 4.00 dioptrías)';
  }else{
    ai ='(hasta +/-4.00 dioptrías)';
  }
  document.getElementById("lente-act-update").innerHTML = tipo_lente;
  document.getElementById("color-act-update").innerHTML = color;
  document.getElementById("aindex-act-update").innerHTML = ai;
  document.getElementById("alto-indice-value").value = indice;
 // 

}
var ordenes_upd_exp_fact =  [];
function addOrderUpd(check_box){
  let factura = document.getElementById("factura-update").value;
  if(factura==0){
    Swal.fire({
      position: 'center', icon: 'error', title: 'Debe seleccionar factura !!!', showConfirmButton: true,
      timer: 2500
    });
    check_box.checked=false;
    return 0;
  }
  let dui_paciente = check_box.dataset.dui;
  let fecha_ant = check_box.dataset.fecha;
  if (check_box.checked) {
    let nueva_fecha = getRandomFechaSinFinesDeSemana();
   
    diferencia_act++;
    if(diferencia_act==10){
      Swal.fire({
        position: 'center', icon: 'error', title: 'No puede seleccionar mas de la diferencia', showConfirmButton: true,
        timer: 2500
      });
      check_box.checked= false;
      document.getElementById("dif-expedientesf").innerHTML = 0;
      return false;
    }
    let objExp = {
      dui : dui_paciente,
      fecha_correcta : fecha_ant,
      new_date : nueva_fecha
   }
   ordenes_upd_exp_fact.push(objExp);
   document.getElementById("count-expedientesf").innerHTML = ordenes_upd_exp_fact.length;
   document.getElementById("dif-expedientesf").innerHTML = diferencia_act;
  }else{
    let indice = ordenes_upd_exp_fact.findIndex(objeto => objeto.dui === dui_paciente);
    if (indice !== -1) {
      ordenes_upd_exp_fact.splice(indice, 1);
    }
    document.getElementById("count-expedientesf").innerHTML = ordenes_upd_exp_fact.length;
    diferencia_act--; document.getElementById("dif-expedientesf").innerHTML = diferencia_act;
  }
 
  console.log(ordenes_upd_exp_fact)
}

function ActualizacionesExpedientes(){
   
  var radios = document.getElementsByName("radiosUpdate");
  var selected = Array.from(radios).find(radio => radio.checked);
  let factura = document.getElementById('factura-update');
  let selFact = factura.selectedOptions[0];
  let tipo_lente = document.getElementById("lente-act-update").innerHTML;
  let color = document.getElementById("color-act-update").innerHTML;
  let indice = document.getElementById("alto-indice-value").value;
  document.getElementById("lente-list").innerHTML = tipo_lente;
  document.getElementById("color-list").innerHTML = color;
  document.getElementById("ai-list").innerHTML = indice;
  const divElemento = document.getElementById('btn-act-update'); // Reemplaza 'miDiv' con el ID real de tu div
  const btnUpdates = document.getElementById("btnActions");
 
  if(ordenes_upd_exp_fact.length==0){
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'No expedientes seleccionados!',
      showConfirmButton: true,
      timer: 1500
    });
    return false;
  }
 if(selected.value=='importar'){  
   $("#modal-import-update").modal();  
   document.getElementById('desc-import1').innerHTML = ` Se importaran a<b> ${selFact.textContent}</b><br> <b>${ordenes_upd_exp_fact.length}</b> ordenes con las siguientes caracteriticas:<br>`;
   btnUpdates.textContent = 'Importar Expedientes';
 }else if (selected.value=='actualizar'){
   $("#modal-import-update").modal();
  document.getElementById('desc-import1').innerHTML = ` Se actualizara a <b> ${selFact.textContent}</b><br> <b>${ordenes_upd_exp_fact.length}</b> ordenes con las siguientes caracteriticas:<br>`;
  btnUpdates.textContent = 'Actualizar Fechas';
 }else if(selected.value=='exportar'){
 
  $("#modal-export-update").modal();
  document.getElementById('desc-export1').innerHTML = ` Se exportaran <b>${ordenes_upd_exp_fact.length} expedientes</b> con las siguientes caracteriticas:<br>`;
  document.getElementById("lente-list-e").innerHTML = tipo_lente;
  document.getElementById("color-list-e").innerHTML = color;
  document.getElementById("ai-list-e").innerHTML = indice;
  
 }
}


function actualizarOrdenesFechaAnt(){

  if(ordenes_upd_exp_fact.length==0){

      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Debe seleccionar pacientes!',
        showConfirmButton: true,
        timer: 1500
      });
      return false;
  }

  var radios = document.getElementsByName("radiosUpdate");
  var selected = Array.from(radios).find(radio => radio.checked);
  let accion  = selected.value
  let tipo_lente = document.getElementById("lente-act-update").innerHTML;
  let color = document.getElementById("color-act-update").innerHTML;
  let indice = document.getElementById("alto-indice-value").value;
 
 $('#loader').show();
 let factura = document.getElementById("factura-update").value;
  $.ajax({
    url:"../ajax/orden_distribucion.php?op=actualizar_ordenes_fecha_ant",
    method:"POST",
    data:{'arrayOrdenesUpdFactura':JSON.stringify(ordenes_upd_exp_fact),'factura': factura,'accion':accion,tipo_lente,color,indice},
    cache:false,
    dataType:"json",
    success:function(data){
      
      if(data.msj=='ok'){
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Expedientes se han actualizado exitosamente!',
          showConfirmButton: true,
          timer: 1500
        });
        ordenes_upd_exp_fact = []
        getRessumenFactAtend();
        $("#dt-upd-orders").DataTable().ajax.reload(null, false);
      }
      $('#loader').hide();
      $("#modal-actualization").modal('hide');
      document.getElementById("count-expedientesf").innerHTML = ordenes_upd_exp_fact.length;
      document.getElementById("check-order-facts").checked=false
    }
  })

}


function selectActFacturas(checkbox){
  let chck_select = document.getElementsByClassName('ord-facts');
  let diferencia_act =   document.getElementById("dif-expedientesf").innerHTML;
  if(checkbox.checked){
    for (let i = 0; i < chck_select.length; i++) {

      if(diferencia_act==0){
        Swal.fire({
          position: 'center', icon: 'error', title: 'No puede seleccionar mas de la diferencia', showConfirmButton: true,
          timer: 2500
        });
        chck_select.checked= false;
        document.getElementById("dif-expedientesf").innerHTML = 0;
        return false;
    }

    let nueva_fecha = getRandomFechaSinFinesDeSemana();
    let dui_paciente = chck_select[i].dataset.dui;
    let fecha_ant = chck_select[i].dataset.fecha;
    chck_select[i].checked = true;
    let objExp = {
      dui : dui_paciente,
      fecha_correcta : fecha_ant,
      new_date : nueva_fecha
   }
   ordenes_upd_exp_fact.push(objExp);
   diferencia_act++;
   
   document.getElementById("count-expedientesf").innerHTML = ordenes_upd_exp_fact.length;
   document.getElementById("dif-expedientesf").innerHTML = diferencia_act;
  }
  }else{
    let difActual = document.getElementById("dif-expedientesf").innerHTML;
    document.getElementById("dif-expedientesf").innerHTML = parseInt(difActual) - parseInt(ordenes_upd_exp_fact.length);
    for (let i = 0; i < chck_select.length; i++) {
      chck_select[i].checked = false;
      ordenes_upd_exp_fact = [];
      document.getElementById("count-expedientesf").innerHTML = ordenes_upd_exp_fact.length;
    }
  }
  
}

function uncheckedOptionsUpdate(){
  const radioButtons = document.querySelectorAll('input[name="radiosUpdate"]');
  radioButtons.forEach((radio) => {
  radio.checked = false;
  });
}

function importarExpedientes(param){
  ordenes_upd_exp_fact = []
  let tipo_lente = document.getElementById("lente-act-update").innerHTML;
  let color = document.getElementById("color-act-update").innerHTML;
  let indice = document.getElementById("alto-indice-value").value;
  
  if(param=='importar'){
    dtActasFirmadas("dt-upd-orders","import_pacientes_verificados",tipo_lente,color, indice)
  }
}

function exportarExpedientes(){
  ordenes_upd_exp_fact = []
  let diff = document.getElementById("dif-expedientesf").innerHTML;
  /*if(diff==0 || diff < 0){
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'No hay excedentes!',
      showConfirmButton: true,
      timer: 1500
    });
    return false;
  }
*/

  let tipo_lente = document.getElementById("lente-act-update").innerHTML;
  let color = document.getElementById("color-act-update").innerHTML;
  let indice = document.getElementById("alto-indice-value").value;  
  let factura = document.getElementById("factura-update").value;
  dtActasFirmadas("dt-upd-orders","export_pacientes_exced",tipo_lente,color, indice, factura,diff)

}

function exportarOrdenesFechaAnt(){
  /* VALIDAR SELECTS */
  let selectFactura = document.getElementById('factura-export').value;
  let selectLente = document.getElementById('lente-export').value;
  let selectColor = document.getElementById('color-export').value;
  let selectAInd = document.getElementById('aind-export').value;

// Validar los valores seleccionados
if (selectFactura === '0' || selectLente === '0' || selectColor === '0' || selectAInd === '0') {
  Swal.fire({
    position: 'center',
    icon: 'error',
    title: 'DEBE ESPECIFICAR DETALLES DE EXPORTACION!',
    showConfirmButton: true,
    timer: 1500
  });
  return false;
}

  ordenes_upd_exp_fact.forEach(objeto => {

  let factura = document.getElementById("factura-export").value;
  console.log(factura)
  const fechasPorFactura = {
    1: ['2022-11-15', '2022-11-30'],
    2: ['2022-12-01', '2022-12-15'],
    3: ['2022-12-16', '2022-12-31'],
    4: ['2023-01-01', '2023-01-15'],
    5: ['2023-01-16', '2023-01-31'],
    6: ['2023-02-01', '2023-02-15'],
    7: ['2023-02-16', '2023-02-24'],
};

let fechas = fechasPorFactura[factura];
let unDia = 24 * 60 * 60 * 1000; 
let fechaInicioMs = new Date(fechas[0]).getTime(); 
let fechaFinMs = new Date(fechas[1]).getTime();
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
console.log(objeto.new_date)
objeto.new_date = fechaFormateada;
});

$.ajax({
  url:"../ajax/orden_distribucion.php?op=exportar_ordenesad",
  method:"POST",
  data:{'arrayexport':JSON.stringify(ordenes_upd_exp_fact),'tipo_lente':selectLente,'color':selectColor,'indice':selectAInd},
  cache:false,
  dataType:"json",
  success:function(data){
    console.log(data)
    if(data.msj === "ok"){
      
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Ordenes Exportadas',
        showConfirmButton: true,
        timer: 1500
      });
      ordenes_upd_exp_fact = []
      getRessumenFactAtend();
      $("#dt-upd-orders").DataTable().ajax.reload(null, false);

    }

  }
})


}

