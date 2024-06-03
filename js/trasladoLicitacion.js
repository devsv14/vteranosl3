const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

function listar_ordenes(id, url, data = {}) {
    $('#' + id).DataTable({
        "searchDelay": 500,
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        deferRender: true,
        buttons: [
            'excelHtml5',
        ],

        "ajax": {
            url: "../ajax/trasladoLicitacion.php?op=" + url,
            type: "POST",
            data: data,
            dataType: "json",
            cache: false,
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
        "iDisplayLength": 12,//Por cada 10 registros hace una paginación
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

/**
 * Busqueda de orden por RX FINAL
 */
function showBusquedaRXFinal() {
    $("#busqueda_rx_final").modal('show');
    const clear_check = document.querySelectorAll('.clear_check');
    const clear_input_rx = document.querySelectorAll('.clear_orden_i');
    clear_check.forEach((input) => input.checked = true);
    clear_input_rx.forEach((input) => input.value = '');
}

function buscarOrdenRXFinal() {
    let tipoBusqueda = 'RXFinal'; //Variable para condicionar en el back

    const checkOD = document.getElementById('checkOD')
    const checkOI = document.getElementById('checkOI')

    if (!checkOD.checked && !checkOI.checked) {
        Toast.fire({ icon: 'warning', title: 'Por favor, especificar un Ojo!' }); return 0;
    }
    //RX FINAL para Ojo Derecho
    let od_esfera = document.getElementById('od_esfera').value
    let od_cilindros = document.getElementById('od_cilindro').value
    let od_adicion = document.getElementById('od_adicion').value
    //RF Final para OJO Izquierdo
    let oi_esfera = document.getElementById('oi_esfera').value;
    let oi_cilindros = document.getElementById('oi_cilindros').value;
    let oi_adicion = document.getElementById('oi_adicion').value;
    if(od_esfera.trim() === "" || oi_esfera.trim() === ""){
        Toast.fire({ icon: 'error', title: 'Por favor, ingresar un valor en la Rx esferas' });return 0;
    }
    if(od_cilindros.trim() === "" || oi_cilindros.trim() === ""){
        Toast.fire({ icon: 'error', title: 'Por favor, ingresar un valor en la Rx cilindro' });return 0;
    }
    //Busqueda por ojo derecho
    let busqFull = ''; 
    if (checkOD.checked && !checkOI.checked) {
        let ojoRXF = checkOD.value
        busqFull = 'Si';
        let datosODRXFinal = { od_esfera, od_cilindros, od_adicion, tipoBusqueda, ojoRXF,busqFull }
        listar_ordenes('datatable_ordenes', 'getRXFinalOrden', datosODRXFinal)
    }
    ///Busqueda por ojo izquierdo
    if (checkOI.checked && !checkOD.checked) {
        busqFull = 'No';
        let ojoRXF = checkOI.value
        let datosOIRXFinal = { oi_esfera, oi_cilindros, oi_adicion, tipoBusqueda, ojoRXF,busqFull }
        listar_ordenes('datatable_ordenes', 'getRXFinalOrden', datosOIRXFinal)
    }
    //Busqueda en ambos ojos
    if (checkOI.checked && checkOD.checked) {
        busqFull = 'Si';
        let ojoRXF = checkOD.value
        let graduacionFull = { od_esfera, od_cilindros, od_adicion, tipoBusqueda, ojoRXF, oi_esfera, oi_cilindros, oi_adicion,busqFull }
        listar_ordenes('datatable_ordenes', 'getRXFinalOrden', graduacionFull)
    }
    //Validacion RX
    valRXFinalBusqueda((result)=>{
        Toast.fire({ icon: 'error', title: 'Valores incorrectos en Rx final...' });
        throw new Error('¡Ups, rx final invalidos!')
    });
    $("#busqueda_rx_final").modal('hide');
}
/**
 * Validaciones de RX Final para busqueda
 */
function valRXFinalBusqueda(callback) {
    let esferasOdInputs = document.querySelectorAll('.rx_final_val');
    esferasOdInputs.forEach((input) => {
        let valor_act = parseFloat(input.value);
        //console.log(valor_act);
        if (isNaN(valor_act)) {
            let regex = /^(NEUTRO|PL|PLANO|N|BALANCE|NO HACER|NO TOCAR|neutro|pl|plano|n|balance|no hacer|no tocar)?$/;
            if (!regex.test(input.value)) {
                input.style.borderBottom = "1px solid orange";
                callback(true);
            } else {
                input.style.borderBottom = "1px solid #2E8B57";
            }
        } else {
            if (valor_act == 0) {
                //console.log(valor_act, input.value)
                let regex = /^0\.00$/;
                if (!regex.test(input.value)) {
                    input.style.borderBottom = "1px solid orange";
                    callback(true);
                } else {
                    input.style.borderBottom = "1px solid #2E8B57";
                }
            } else if (valor_act > 0) {
                let regex = /^\+\d{1,2}(\.25|\.50|\.75|\.00)$/;
                if (!regex.test(input.value)) {
                    input.style.borderBottom = "1px solid orange";
                    callback(true);
                } else {
                    input.style.borderBottom = "1px solid #2E8B57";
                }
            } else if (valor_act < 0) {
                let regex = /^\-\d{1,2}(\.25|\.50|\.75|\.00)$/;
                if (!regex.test(input.value)) {
                    input.style.borderBottom = "1px solid orange";
                    callback(true);
                } else {
                    input.style.borderBottom = "1px solid #2E8B57";
                }
            }
        }
    });
}
//Agregar DATA LIST A INPUT
const inputs_rx_f = document.querySelectorAll('.rx_final_values');
if (inputs_rx_f.length > 0) {

  inputs_rx_f.forEach(input => {
    input.addEventListener('keyup', () => {

      if (input.value.length === 1 && input.value !== '0' && input.value !== '+' && input.value !== '-') {

        // Comprobar si el input ya tiene un datalist
        if (!input.list) {
          let options = ['PLANO', 'NEUTRO', 'BALANCE', 'NO TOCAR', 'NO HACER'];
          let datalist = document.createElement('datalist');
          datalist.id = input.id + '-datalist'; // Asignar un ID único al datalist
          options.forEach(option => {
            let optionElement = document.createElement('option');
            optionElement.value = option;
            datalist.appendChild(optionElement);
          });
          //console.log(datalist);
          input.setAttribute('list', datalist.id); // Asignar el datalist al input
          document.body.appendChild(datalist); // Agregar el datalist al DOM
        }

      } else if (input.value.length > 3 || input.value == "") {
        // Si no se cumplen las condiciones, eliminar el datalist si ya existe
        if (input.list) {
          input.removeAttribute('list');
          let datalist = document.querySelector(`#${input.id}-datalist`);
          datalist.remove();
        }

      }
    });
  });
}

//Codigo para select buscador
document.addEventListener('DOMContentLoaded', ()=>selectedTipoOrden()) //Start load)
function selectedTipoOrden(){
    let getTipoLenteSelect = document.getElementById('tipo_lente').value;
    let objBusqueda = {
        'tipo_lente': getTipoLenteSelect,
        'tipoBusqueda': 'tipo_lente'
    }
    listar_ordenes('datatable_ordenes', 'getRXFinalOrden', objBusqueda);

}

/* ///////////////MOSTRAR DATOS DE RX Y ACTUALIZACION RX////////////// */
var dataSivetAct = [];
function showDataRxL1(dui,id){
    dataSivetAct = [];
    document.getElementById(id).disabled= true;
    const data = { "busqueda": dui };
    document.getElementById("clasificar-trasladado").value=0;
    document.getElementById("spinnner").style.display = "flex"; 
    fetch('https://apis.inabve.sv/v1/api_sivetweb/registros/veteranos/info', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then((response) => response.json())
    .then((data) => {
        let dataSivet = data.data;
        if (dataSivet.length > 0) {
            loadDataPac(dataSivet,dui)
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    })
    .finally(() => {
        document.getElementById("spinnner").style.display = "none"; 
        document.getElementById(id).disabled= false;
    }); 
}

function loadDataPac(dataSivet,dui){

    $.ajax({
        url: "../ajax/trasladoLicitacion.php?op=get_data_rx_l1",
        method: "POST",
        cache: false,
        data: {dui},
        dataType: "json",
        success: function (data) {
            $('#modal_rx_traslados').modal();
            document.getElementById('pac_act').innerHTML = data[0].paciente;
             document.getElementById('telefono_trasl').value= data[0].telefono;
            document.getElementById('dui_rx_act').value = data[0].dui;
            document.getElementById('od_esferash').innerHTML = data[0].od_esferas;
            document.getElementById('od_cilindrosh').innerHTML = data[0].od_cilindros;
            document.getElementById('od_ejesh').innerHTML = data[0].od_eje;
            document.getElementById('od_addsh').innerHTML = data[0].od_adicion;
            document.getElementById('oi_esferash').innerHTML = data[0].oi_esferas;
            document.getElementById('oi_cilindrosh').innerHTML = data[0].oi_cilindros;
            document.getElementById('oi_ejesh').innerHTML = data[0].oi_eje;
            document.getElementById('oi_addsh').innerHTML = data[0].oi_adicion;
            document.getElementById('l1aromodel').value = data[0].modelo_aro;
            document.getElementById('l1aromarca').value = data[0].marca_aro;
            document.getElementById('l1arocolor').value = data[0].color_frente;         
            document.getElementById('depto-traslado').innerHTML = data[0].depto;          
            document.getElementById('mun-trasl').innerHTML = data[0].municipio;                      
            dataSivetAct = dataSivet;
            
        }
      });
}

function clasificarOrden(){
    $("#modal_add_traslados").modal();
}

function addRXtraslado(){
    console.log(dataSivetAct)
    let clasificacion = document.getElementById("clasificar-trasladado").value;
    let sucursal = document.getElementById("sucursal-trasladado").value;    
    let dui_act = document.getElementById("dui_rx_act").value;
    let tel_upd = document.getElementById('telefono_trasl').value;
    if(clasificacion=='contesta' && sucursal=='0'){
        Swal.fire({icon: 'warning', title: 'Sucursal es un campo requerido'}); 
    }
    $.ajax({
        url: "../ajax/trasladoLicitacion.php?op=crear_orden_traslado",
        method: "POST",
        data: { clasificacion, dui_act, sucursal, dataSIVET: JSON.stringify(dataSivetAct),tel_upd},
        cache: false,
        dataType: "json",
        success: function (data) {
            console.log(data)
            if(data.msj=='insert'){
                Swal.fire({icon: 'success', title: 'Cita agendada exitosamente'});
                $("#datatable_ordenes").DataTable().ajax.reload(null, false);
            }else if(data.msj=='update'){
                Swal.fire({icon: 'info', title: 'Cita actualizada exitosamente'});              
                $("#datatable_ordenes").DataTable().ajax.reload(null, false);
            }else if(data.msj=='nocontesta'){
                Swal.fire({icon: 'warning', title: 'Paciente no ha contestado, se ha registrado un intento de llamada'}); 
                $("#datatable_ordenes").DataTable().ajax.reload(null, false);
            }else if(data.msj=='cancela'){
                Swal.fire({icon: 'warning', title: 'Paciente descartado rechazo/fallecimiento'}); 
                $("#datatable_ordenes").DataTable().ajax.reload(null, false);
            }else if(data.msj=='error'){
                Toast.fire({ icon: 'error', title: 'No se ha podido agendar la cita' });
            }
            
       }
    });

    $('#modal_rx_traslados').modal('hide');
    $("#modal_add_traslados").modal('hide');
          
}

var duiPrint = [];
function selPrintL1(checkP){
    let check_all = checkP.checked
    let chkPrint = document.getElementsByClassName('check_selected_print');

    if (check_all) {
        duiPrint = [];
        for (let i = 0; i < chkPrint.length; i++) {
            let dui_paciente = chkPrint[i].value
            document.getElementById(chkPrint[i].id).checked = true;
            duiPrint.push(dui_paciente);
          }
          document.getElementById("counter-print-l1").innerHTML= duiPrint.length
    }else{
        for (let i = 0; i < chkPrint.length; i++) {
          document.getElementById(chkPrint[i].id).checked = false;          
        }
        duiPrint = [];
        document.getElementById("counter-print-l1").innerHTML = 0
      }    
}

function addUpdatePrintL1(chekbox){
    let dui = chekbox.value;
    if(chekbox.checked){
        if (duiPrint.includes(dui)) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'DUI ya existe!',
                showConfirmButton: true,
                timer: 2500
            });
            return false
        }else {
            duiPrint.push(dui);
            document.getElementById("counter-print-l1").innerHTML= duiPrint.length
        }
    }else{
        let indice = duiPrint.indexOf(dui);
        if (indice !== -1) {
            duiPrint.splice(indice, 1);
            document.getElementById("counter-print-l1").innerHTML = duiPrint.length
          } else {
            console.log(valorBuscado + ' no existe en el array dui.');
          }
    }
}


function imprimirLabelL1_copy(){
  console.log(duiPrint)
    $.ajax({
        url:"../ajax/trasladoLicitacion.php?op=update_print_ordenl1",
        method:"POST",
        data:{duiPrint:duiPrint},
        cache:false,
        dataType:"json",
        success:function(data){
          console.log(data);
          if(data.msj == "ok"){            
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: ' se ha generado una orden de impresion!',
              showConfirmButton: true,
              timer: 1500
            });
            $("#dt_reporte_lente").DataTable().ajax.reload(null, false);
            $("#dt-print-l1").DataTable().ajax.reload(null, false);
           
            generarPDFNV(data.duiPrinter);
          }
  
        }
      })
}

function imprimirLabelL1(){
    let sucursal = 'Bodega';
    var form = document.createElement("form");
    form.target = "_blank";
    form.method = "POST";
    form.action = "reimp_vinetas_aud.php";
  
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "data";
    //input.value = duiPrint;
    input.value = JSON.stringify(duiPrint);
    form.appendChild(input);
  
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "sucursal";
    input.value = sucursal;
    form.appendChild(input);
  
    document.body.appendChild(form)
    form.submit();
    document.body.removeChild(form);
    duiPrint = [];
     $("#dt-print-l1").DataTable().ajax.reload(null, false);
  
  
  }