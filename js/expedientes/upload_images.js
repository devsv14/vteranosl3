var object_subir_orden = {};
var array_subir_ordenes = [];

function DataTable(table, route, data) {
    //console.log(Args) //ARGUMENTOS
    $('#' + table).DataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "deferRender": true,
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [
            'excelHtml5',
        ],

        "ajax": {
            url: "../ajax/ordenes.php?op=" + route,
            type: "POST",
            data: data,
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            },
        },

        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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

function uploadImgRef() {
    $("#modal-folder-ref").modal('show');
}

function agregar_imagen() {
    let paciente_selected = document.getElementById('ref-id-paciente');
    if (paciente_selected.value.trim() !== "") {
        document.getElementById('file_imagen').click();
    } else {
        Swal.fire({
            title: "Aviso",
            text: "Por favor, seleccione un paciente antes de continuar.",
            icon: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: "#007bff",
            backdrop: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'swal-wide',
                confirmButton: 'swal-confirm'
            }
        });
    }
}

try {
    let btnSubirOrden = document.getElementById('btn-subir-orden');
    if (btnSubirOrden) {
        btnSubirOrden.addEventListener('click', () => {
            object_subir_orden = {};
            $("#modal-subir-imagen").modal('show');
            document.getElementById('form-data-foto').reset();
            document.getElementById('imagePreview').innerHTML = `
                <div class="images-content" style="padding: 10px;">
                    <i class="far fa-file-image" style="font-size: 70px;"></i>
                </div>
            `
        })
    }

    document.getElementById('file_imagen').addEventListener('change', function (event) {
        const file = event.target.files[0];

        object_subir_orden.file = file;
        cargarImagen(file,'imagePreview');
    });

    //Save file imagen
    let formDataFoto = document.getElementById('form-data-foto');
    if (formDataFoto) {
        formDataFoto.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(formDataFoto);
            console.log(object_subir_orden);
            $("#modal-subir-imagen").modal('hide');
            array_subir_ordenes.push(object_subir_orden);
            show_folder_ordenes();
        })
    }

} catch (err) {
    console.log(err)
}

function listarPacientesRef() {
    $("#modal-pacientes-ref").modal('show');
    DataTable("datatable_pacientes_ref", "listar_pacientes_citados", {})
}

function selectPaciente(element) {
    $("#modal-pacientes-ref").modal('hide');
    let id_ref = element.dataset.id_ref;
    let paciente = element.dataset.paciente;
    let sucursal = element.dataset.sucursal;
    let dui = element.dataset.dui;

    object_subir_orden.id_ref = id_ref;
    object_subir_orden.paciente = paciente;
    object_subir_orden.sucursal = sucursal;
    object_subir_orden.dui = dui;

    document.getElementById('ref-id-paciente').value = "#Ref. " + id_ref + " : Paciente: " + paciente;
}

function show_folder_ordenes() {
    let rows_folder = document.getElementById("rows-folder");
    rows_folder.innerHTML = '';

    let counter = array_subir_ordenes.length;

    if (counter > 0) {
        let new_data = array_subir_ordenes.reduce((acc, paciente) => {
            const sucursalIndex = acc.findIndex(item => item.sucursal === paciente.sucursal);

            if (sucursalIndex >= 0) {
                acc[sucursalIndex].cantidad += 1;
                acc[sucursalIndex].data.push(paciente);
            } else {
                acc.push({
                    sucursal: paciente.sucursal,
                    cantidad: 1,
                    data: [paciente]
                });
            }
            return acc;
        }, []);

        new_data.forEach((item) => {
            let row = `
                <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1">
                    <a onclick="showItemsFolder(this)" data-sucursal="${item.sucursal}" class="btn btn-app bg-info" style="height:100%">
                        <span class="badge bg-danger">${item.cantidad}</span>
                        <i class="far fa-folder" style="font-size:30px"></i> ${item.sucursal}
                    </a>
                </div>
            `;
            rows_folder.innerHTML += row;
        })
    }
}

function showItemsFolder(element) {
    let sucursal = element.dataset.sucursal;
    
    let array_ordenes_sucursal = array_subir_ordenes.filter((item)=>item.sucursal === sucursal);
    console.log(array_ordenes_sucursal);

    let row_items_folder = document.getElementById('ordenes-items-folder');
    row_items_folder.innerHTML = '';

    array_ordenes_sucursal.forEach((orden)=>{
        let row = `
            <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                <div class="card p-1">
                    <div class="card-body p-1">
                        <div class="card-content-img" id="preview-img-${orden.id_ref}">

                        </div>
                    </div>
                    <div class="card-footer p-1">
                        <p class="text-card mb-0"><b>PACIENTE:</b> ${orden.paciente}</p>
                        <p class="text-card mb-0"><b>REF:</b> ${orden.id_ref}</p>
                        <p class="text-card mb-0"><b>DUI:</b> ${orden.dui}</p>
                    </div>
                </div>
            </div>
        `;
        row_items_folder.innerHTML += row;
        cargarImagen(orden.file,"preview-img-" + orden.id_ref);
    })

    
    $("#modal-items-folder").modal('show');

}

function cargarImagen(file,id_html) {
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            const imagePreview = document.getElementById(id_html);
            imagePreview.innerHTML = ''; // Clear any existing images
            imagePreview.appendChild(img);
        };
        reader.readAsDataURL(file);
    } else {
        Swal.fire({
            title: "Error",
            text: "Por favor, seleccione un archivo de imagen válido.",
            icon: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#007bff",
            backdrop: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'swal-wide',
                confirmButton: 'swal-confirm'
            }
        });
    }
}