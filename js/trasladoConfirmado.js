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
            url: "../ajax/trasladoConfirmado.php?op=" + url,
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
        "iDisplayLength": 15,//Por cada 10 registros hace una paginación
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
    if (od_esfera.trim() === "" || oi_esfera.trim() === "") {
        Toast.fire({ icon: 'error', title: 'Por favor, ingresar un valor en la Rx esferas' }); return 0;
    }
    if (od_cilindros.trim() === "" || oi_cilindros.trim() === "") {
        Toast.fire({ icon: 'error', title: 'Por favor, ingresar un valor en la Rx cilindro' }); return 0;
    }
    //Busqueda por ojo derecho
    let busqFull = '';
    if (checkOD.checked && !checkOI.checked) {
        let ojoRXF = checkOD.value
        busqFull = 'Si';
        let datosODRXFinal = { od_esfera, od_cilindros, od_adicion, tipoBusqueda, ojoRXF, busqFull }
        listar_ordenes('dt_traslado_confirmados', 'getRXFinalOrden', datosODRXFinal)
    }
    ///Busqueda por ojo izquierdo
    if (checkOI.checked && !checkOD.checked) {
        busqFull = 'No';
        let ojoRXF = checkOI.value
        let datosOIRXFinal = { oi_esfera, oi_cilindros, oi_adicion, tipoBusqueda, ojoRXF, busqFull }
        listar_ordenes('dt_traslado_confirmados', 'getRXFinalOrden', datosOIRXFinal)
    }
    //Busqueda en ambos ojos
    if (checkOI.checked && checkOD.checked) {
        busqFull = 'Si';
        let ojoRXF = checkOD.value
        let graduacionFull = { od_esfera, od_cilindros, od_adicion, tipoBusqueda, ojoRXF, oi_esfera, oi_cilindros, oi_adicion, busqFull }
        listar_ordenes('dt_traslado_confirmados', 'getRXFinalOrden', graduacionFull)
    }
    //Validacion RX
    valRXFinalBusqueda((result) => {
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
document.addEventListener('DOMContentLoaded', () => selectedTipoOrden()) //Start load)
function selectedTipoOrden() {
    let getTipoLenteSelect = document.getElementById('tipo_lente').value;
    let objBusqueda = {
        'tipo_lente': getTipoLenteSelect,
        'tipoBusqueda': 'tipo_lente'
    }
    listar_ordenes('dt_traslado_confirmados', 'getRXFinalOrden', objBusqueda);

}

/* ///////////////MOSTRAR DATOS DE RX Y ACTUALIZACION RX////////////// */
var dataSivetAct = [];
function showDataRxL1(dui) {
    loadDataPac(dui)
}

function loadDataPac(dui) {
    $.ajax({
        url: "../ajax/trasladoConfirmado.php?op=get_data_rx_l1",
        method: "POST",
        cache: false,
        data: { dui },
        dataType: "json",
        success: function (data) {
            console.log(data)
            $('#modal_upd_rx_orden').modal();
            //Opciones seleccionadas por default
            let vs = document.getElementById('VisionSencilla');
            let flaptop = document.getElementById('Flaptop');
            let progresive = document.getElementById('Progresive');
            if(data[0].tipo_lente === "Flaptop"){
                flaptop.checked = true;
            }else if(data[0].tipo_lente === "Visión Sencilla"){
                vs.checked = true;
            }else if(data[0].tipo_lente === "Visión Sencilla"){
                progresive.checked = true;
            }

            document.getElementById('pac_act').innerHTML = data[0].paciente;           
            document.getElementById('dui_rx_act').value = data[0].dui;
            document.getElementById('dui-trasl-confirm').innerHTML = dui; 
            document.getElementById('od_esferash').value = data[0].od_esferas;
            document.getElementById('od_cilindrosh').value = data[0].od_cilindros;
            document.getElementById('od_ejesh').value = data[0].od_eje;
            document.getElementById('od_addsh').value = data[0].od_adicion;
            document.getElementById('oi_esferash').value = data[0].oi_esferas;
            document.getElementById('oi_cilindrosh').value = data[0].oi_cilindros;
            document.getElementById('oi_ejesh').value = data[0].oi_eje;
            document.getElementById('oi_addsh').value = data[0].oi_adicion;
            document.getElementById('l1aromodelt').value = data[0].modelo_aro;
            document.getElementById('l1aromarcat').value = data[0].marca_aro;
            document.getElementById('l1arocolort').value = data[0].color_frente;
            document.getElementById('horizontalt').value = data[0].horizontal_aro;
            document.getElementById('verticalt').value = data[0].vertical_aro;

            document.getElementById('dpodt').value = data[0].pupilar_od;
            document.getElementById('dpoit').value = data[0].pupilar_oi;
            document.getElementById('aodt').value = data[0].lente_od;
            document.getElementById('aodi').value = data[0].lente_oi;
            //Validacion de Alto indice
            validaAltoIndice();
        }
    });
}

function addRXtraslado() {
    valRXFinalBusqueda();
    let selectedTratamiento = document.querySelector('input[name="tratamiento"]:checked');
    let selectedTipoLente = document.querySelector('input[name="tipo_lente"]:checked');
    if(selectedTratamiento === null || selectedTipoLente === null){
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Tratamiento y tipo de lente son obligatorio!!',
            showConfirmButton: true,
            timer: 9500
          });return 0;
    }
    selectedTratamiento = selectedTratamiento.value;
    selectedTipoLente = selectedTipoLente.value;
    //Rx OD final
    let odEsfera = document.getElementById('od_esferash').value;
    let odCilindro = document.getElementById('od_cilindrosh').value;
    let odEje = document.getElementById('od_ejesh').value;
    let odAdd = document.getElementById('od_addsh').value;
    //Rx OI final
    let oiEsfera = document.getElementById('oi_esferash').value;
    let oiCilindro = document.getElementById('oi_cilindrosh').value;
    let oiEje = document.getElementById('oi_ejesh').value;
    let oiAdd = document.getElementById('oi_addsh').value;
    //Aro
    let modeloAro = document.getElementById('l1aromodel').value;
    let marcaAro = document.getElementById('l1aromarca').value;
    let colorAro = document.getElementById('l1arocolor').value;
    //Get value alto indice
    let altoIndice = document.getElementById('alto-indice');
    if(altoIndice !== "" && altoIndice !== null && altoIndice.checked){
        altoIndice = "Si";
    }else{
        altoIndice = 'No';
    }
    //Obje para enviar 
    console.log('En desarrollo...')
    $.ajax({
        url: '',//"../ajax/trasladoConfirmado.php?op=crear_orden_traslado",
        method: "POST",
        data: {},
        cache: false,
        dataType: "json",
        success: function (data) {
            if (data.msj == 'insert') {
                Toast.fire({ icon: 'success', title: 'Cita agendada exitosamente' });
                $("#dt_traslado_confirmados").DataTable().ajax.reload(null, false);
            } else if (data.msj == 'update') {
                Toast.fire({ icon: 'info', title: 'Cita actualizada exitosamente' });
                $("#dt_traslado_confirmados").DataTable().ajax.reload(null, false);
            } else if (data.msj == 'error') {
                Toast.fire({ icon: 'error', title: 'No se ha podido agendar la cita' });
            }

        }
    });
    $('#modal_upd_rx_orden').modal('hide');
}

function valRXFinalBusqueda() {
    let esferasOdInputs = document.querySelectorAll('.rx_final_val');
    esferasOdInputs.forEach((input) => {
        let valor_act = parseFloat(input.value);
        //console.log(valor_act);
        if (isNaN(valor_act)) {
            let regex = /^(NEUTRO|PL|PLANO|N|BALANCE|NO HACER|NO TOCAR|neutro|pl|plano|n|balance|no hacer|no tocar)?$/;
            if (!regex.test(input.value)) {
                input.style.borderBottom = "1px solid orange";
                Toast.fire({ icon: 'error', title: 'Valores incorrectos en Rx final...' });
                throw new Error('Valores incorrectos en Rx final...');
            } else {
                input.style.borderBottom = "1px solid #2E8B57";
            }
        } else {
            if (valor_act == 0) {
                //console.log(valor_act, input.value)
                let regex = /^0\.00$/;
                if (!regex.test(input.value)) {
                    input.style.borderBottom = "1px solid orange";
                    Toast.fire({ icon: 'error', title: 'Valores incorrectos en Rx final...' });
                    throw new Error('Valores incorrectos en Rx final...');
                } else {
                    input.style.borderBottom = "1px solid #2E8B57";
                }
            } else if (valor_act > 0) {
                let regex = /^\+\d{1,2}(\.25|\.50|\.75|\.00)$/;
                if (!regex.test(input.value)) {
                    input.style.borderBottom = "1px solid orange";
                    Toast.fire({ icon: 'error', title: 'Valores incorrectos en Rx final...' });
                    throw new Error('Valores incorrectos en Rx final...');
                } else {
                    input.style.borderBottom = "1px solid #2E8B57";
                }
            } else if (valor_act < 0) {
                let regex = /^\-\d{1,2}(\.25|\.50|\.75|\.00)$/;
                if (!regex.test(input.value)) {
                    input.style.borderBottom = "1px solid orange";
                    Toast.fire({ icon: 'error', title: 'Valores incorrectos en Rx final...' });
                    throw new Error('Valores incorrectos en Rx final...');
                } else {
                    input.style.borderBottom = "1px solid #2E8B57";
                }
            }
        }
    });
}
//Validacion alto indice
function validaAltoIndice() {
    let esfera_od = Number(document.getElementById("od_esferash").value);
    let cilindros_od = Number(document.getElementById("od_cilindrosh").value);
  
    let esferas_oi = Number(document.getElementById("oi_esferash").value);
    let cilindros_oi = Number(document.getElementById("oi_cilindrosh").value);
  
    if (esfera_od > 4 || cilindros_od > 4 || esferas_oi > 4 || cilindros_oi > 4 || esfera_od < -4 || cilindros_od < -4 || esferas_oi < -4 || cilindros_oi < -4) {
      document.getElementById("alto-indice").checked = true;
      document.getElementById("alto-indice").disabled = false;
      document.querySelector(".label-index").style.color = "green";
    } else {
      document.getElementById("alto-indice").checked = false;
      document.getElementById("alto-indice").disabled = false;
      document.querySelector(".label-index").style.color = "gray";
    }
  }

  var dataLocaL = [];

  function crearOrdenTraslado(){
    let tipoLenteSeleccionado = document.querySelector('input[name="tipo_lente"]:checked').value;
    let tratamientoRadioButtons = document.querySelectorAll('input[name="tratamiento"]');
    let tratamientoSeleccionado = Array.from(tratamientoRadioButtons).find(radio => radio.checked);
    let tratamiento = '';
    if (tratamientoSeleccionado) {
        tratamiento = tratamientoSeleccionado.value;        
    } else {
        Swal.fire({ icon: 'warning', title: 'Es obligatorio seleccionar tratamiento' });
        return false;
    }
    let tipo_orden = '';
    let t_orden = document.querySelectorAll('input[name="lab-traslado"]');
    let tipo_ordenSel = Array.from(t_orden).find(radio => radio.checked);
    if (tipo_ordenSel) {
        tipo_orden = tipo_ordenSel.value;
    } else {
        Swal.fire({icon: 'warning', title: 'Es obligatorio seleccionar tipo orden'}); return false        
    }

    let radioAltoIndice = document.getElementById("alto-indice");
    let altoIndice = radioAltoIndice.checked ? 'Si' : 'No';

    let od_esferash = document.getElementById('od_esferash').value;
    let od_cilindrosh = document.getElementById('od_cilindrosh').value;
    let od_ejesh = document.getElementById('od_ejesh').value;
    let od_addsh = document.getElementById('od_addsh').value;

    let oi_esferash = document.getElementById('oi_esferash').value;
    let oi_cilindrosh = document.getElementById('oi_cilindrosh').value;
    let oi_ejesh = document.getElementById('oi_ejesh').value;
    let oi_addsh = document.getElementById('oi_addsh').value;
    let dui_traslado = document.getElementById('dui-trasl-confirm').innerHTML;
   
    let modelo_aro = document.getElementById("l1aromodelt").value; 
    let marca_aro = document.getElementById("l1aromarcat").value; 
    let color_aro = document.getElementById("l1arocolort").value; 
    $.ajax({
        url: "../ajax/trasladoConfirmado.php?op=crear_orden",
        method: "POST",
        data: {tipoLenteSeleccionado,tratamiento,tipo_orden,altoIndice,od_esferash,od_cilindrosh,od_ejesh,od_addsh,oi_esferash,oi_cilindrosh,oi_ejesh,oi_addsh,dui_traslado,modelo_aro,marca_aro,color_aro},
        cache: false,
        dataType: "json",
        success: function (data) {
            console.log(data)
            if(data.msj==='InsertOrder'){
                Swal.fire({icon: 'success',title: 'Orden creada exitosamente', showConfirmButton: true}); 
                let storedData = JSON.parse(localStorage.getItem('dui-traslados')) || [];
                let newData = { dui: data.dui, paciente: data.paciente };
                storedData.push(newData);
                localStorage.setItem('dui-traslados', JSON.stringify(storedData));
                countOrdersTras();
                $("#dt_traslado_confirmados").DataTable().ajax.reload(null, false);          
            }else{
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    title: 'DUI ya ha sido registrado en ordenes o DUI no posee cita',
                    showConfirmButton: true,
                    timer: 2500
                  });
            }
       }
    });

  }

function   countOrdersTras(){
    let storedData = localStorage.getItem('dui-traslados');
    storedData = JSON.parse(storedData); 
    document.getElementById("counter-print-traslados-conf").innerHTML=storedData.length;
   
    if(storedData.length>=6){
        printLabelTraslado()
    }
}

function  printLabelTraslado(){
    let storedData = localStorage.getItem('dui-traslados');
    storedData = JSON.parse(storedData); 
    let id_usuario = document.getElementById('id_usuario').value;
    let sucursal = 'Bodega';
    let usuario = document.getElementById('usuario').value;
    let tipo_desp = 'expedientes';
    $.ajax({
        url:"../ajax/despachos.php?op=registrar_despacho_lab",
        method:"POST",
        data:{'ordenes_desp':JSON.stringify(storedData),'sucursal':sucursal,'id_usuario':id_usuario,'usuario':usuario},
        cache: false,
        dataType:"json", 
        success:function(data){           
            printDespacho(data.correlativo,tipo_desp,sucursal);
            localStorage.removeItem("dui-traslados");                
        }
    });//fin ajax
}