
//Init
var expedientes = []; //Array de datos
document.addEventListener('DOMContentLoaded', ()=>{
    listar_ordenes('dt-update-fecha','datatableUp',{});
})
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
            //dataType: "json",
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
function openModal() {
    $('#modalUpdFecha').modal('show');
    document.getElementById('dui-vet').focus();
    showItemsTbody();
}

/**
 * Obtener el dui y ejecutar evento de busqueda
 */

try {
    const duiVeteran = document.getElementById('dui-vet');
    duiVeteran.addEventListener('change', (e) => {
        let formaterDUI = duiVeteran.value.replace("'","-");
        duiVeteran.value = formaterDUI.trim();
        searchPaciente(formaterDUI.trim());
    })
    //Button para actualizar fecha
    const btnUpdFecha = document.getElementById('btnUpdFecha');
    btnUpdFecha.addEventListener('click', (e)=>{
        processExpUpdateDate();
    })
} catch (err) {
    console.log(err)
}
function searchPaciente(dui) {
    $.ajax({
        url: "../ajax/trasladoLicitacion.php?op=getPacienteById",
        method: "POST",
        data: { dui },
        cache: false,
        dataType: "json",
        success: function (data) {
            if(data.length > 0){
                let paciente = data[0];
                //Validacion de duplicados
                //clear input
                document.getElementById('dui-vet').value = '';
                document.getElementById('dui-vet').focus();
                let index = expedientes.findIndex((item)=>item.codigo === paciente.codigo);
                if(index !== -1){
                    Toast.fire({ icon: 'error', title: 'El paciente ya existe en la lista!'}); return 0;
                }
                expedientes.push(paciente);
                showItemsTbody();
            }else{
                Toast.fire({ icon: 'error', title: 'Paciente no encontrado,verificar nuevamente!'});
                document.getElementById('dui-vet').value = '';
                document.getElementById('dui-vet').focus();
            }
        }
    })
}

//Update
function processExpUpdateDate() {
    let data = expedientes;
    if (data.length > 0) {
        $.ajax({
            url: "../ajax/trasladoLicitacion.php?op=update_print_ordenl1",
            method: "POST",
            data: { 'data':data },
            cache: false,
            dataType: "json",
            success: function (data) {
                if (data.status === "ok") {
                    expedientes = [];
                    showItemsTbody();
                    document.getElementById('dui-vet').value = '';
                    document.getElementById('dui-vet').focus();
                    let cantidaExp = expedientes.length;
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: cantidaExp + ' expedientes se han actualizado exitosamente!',
                        showConfirmButton: true,
                        timer: 9500
                    });
                    generarPDF(data.duiPrinter);
                    $("#dt-update-fecha").DataTable().ajax.reload(null, false);
                }
                $('#modalUpdFecha').modal('show');

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
function showItemsTbody() {
    $("#items-table-rows").html('');
    let filas = "";
    let length_array = parseInt(expedientes.length) - 1;
    let count = expedientes.length + 1
    for (let i = length_array; i >= 0; i--) {
        count -= 1
        filas = filas +
            "<tr style='text-align:center' id='item_t" + i + "'>" +
            "<td>" + count + "</td>" +
            "<td>" + expedientes[i].codigo + "</td>" +
            "<td>" + expedientes[i].paciente + "</td>" +
            "<td>" + expedientes[i].dui + "</td>" +
            "<td>" + expedientes[i].sucursal + "</td>" +
            "<td>" + "<button type='button'  class='btn btn-sm bg-light' onClick='delItem(" + i + ")'><i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i></button>" + "</td>" +
            "</tr>";
    }
    $("#items-table-rows").html(filas);

}
function delItem(index) {
    $("#item_t" + index).remove();
    drop_index(index);
}

function drop_index(position_element) {
    expedientes.splice(position_element, 1);
    $('#dui-vet').focus();
    showItemsTbody();
}
//GENERAR PDF
function generarPDF(data) {

    var form = document.createElement("form");
    form.target = "_blank";
    form.method = "POST";
    form.action = "reimp_vinetas_aud.php";

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "data";
    input.value = JSON.stringify(data);
    form.appendChild(input);

    document.body.appendChild(form)
    form.submit();
    document.body.removeChild(form);

}