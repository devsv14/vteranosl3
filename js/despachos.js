function initDesp(){
 listarDespachos();
}

function showModalDespachos(){
    $("#fecha-despacho").val("")
    $("#modal-despachos").modal();
    var elements=document.getElementsByClassName('chk-despachos');
    Array.prototype.forEach.call(elements, function(element) {
        element.checked = false;
    });
    $("#body-table-env").html('');
    ordenes_envio_lab = [];
    $("#cant-env").html("");

}

function listarDespachos(){
    let sucursal = document.getElementById("sucursal").value;
    let permiso_listar_gen = names_permisos.includes("despachos_listado_general");   
    dtTemplateDespachos("data_despachos_suc","get_ordenes_despachadas",permiso_listar_gen,sucursal)
}

document.querySelectorAll(".chk-despachos").forEach(i => i.addEventListener("click", e => {    
    let tipo_desp = $("input[type='radio'][name='tipo-desp']:checked").val();
    let sucursal = document.getElementById("sucursal").value;
        dtTemplateDespachos("dt_modal_despachos","get_ordenes_despachar",tipo_desp,sucursal)      

}));

function listarOrdenesDesp(fecha){
    let tipo_desp = $("input[type='radio'][name='tipo-desp']:checked").val();
    let sucursal = document.getElementById("sucursal").value;
    if(tipo_desp != undefined){
        dtTemplateDespachos("dt_modal_despachos","get_ordenes_despachar",tipo_desp,fecha,sucursal)
    }
} 

function dtTemplateDespachos(table,route,...Args){
    console.log(Args)
    tabla = $('#'+table).DataTable({      
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [     
        'excelHtml5',
      ],
  
      "ajax":{
        url:"../ajax/despachos.php?op="+ route,
        type : "POST",
        data: {Args: Args},
        dataType : "json",         
        error: function(e){
        console.log(e.responseText);
      },      
    },
  
      "bDestroy": true,
      "responsive": true,
      "bInfo":true,
      "iDisplayLength": 25,//Por cada 10 registros hace una paginación
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

var ordenes_envio_lab = [];
$(document).on('click', '.enviosuclab', function () {
    let dui = $(this).attr("value");
    let paciente = $(this).attr("name");
    let id =  $(this).attr("id");
    let checkbox = document.getElementById(id);
    let chk_state = checkbox.checked;  
    let idjob = dui+paciente.substr(-4);
    let indice = ordenes_envio_lab.findIndex((objeto, indice, ordenes_envio_lab) => {
        return objeto.id_job == idjob;
    });
    let tam_array_env = ordenes_envio_lab.length;
    if(chk_state){
        if(indice<0){
            let obj = {dui,paciente,id_job:idjob}
            ordenes_envio_lab.push(obj);
            tam_array_env = ordenes_envio_lab.length
            $("#cant-env").html(tam_array_env+" trabajos para enviar")
        }
    }else{
          if(indice >= 0){
            ordenes_envio_lab.splice(indice, 1);
            tam_array_env = ordenes_envio_lab.length
            $("#cant-env").html(tam_array_env+" trabajos para enviar")
          }  
    }

   
    
});

function sendLab(){
    let tam_array = ordenes_envio_lab.length;
    if(tam_array==0){
        Swal.fire({
            position: 'top-center',
            icon: 'warning',
            title: 'Debe seleccionar ordenes a enviar',
            timer: 3500
          });
        return false;
    }else{
        let sucursal= document.getElementById("sucursal").value;
        let id_usuario= document.getElementById("id_usuario").value;
        let usuario= document.getElementById("usuario").value;
        let tipo_desp = $("input[type='radio'][name='tipo-desp']:checked").val();

        $.ajax({
            url:"../ajax/despachos.php?op=registrar_despacho_lab",
            method:"POST",
            data:{'ordenes_desp':JSON.stringify(ordenes_envio_lab),'sucursal':sucursal,'id_usuario':id_usuario,'usuario':usuario},
            cache: false,
            dataType:"json", 
            success:function(data){                
               console.log('ok5')
                printDespacho(data.correlativo,tipo_desp,sucursal);
                ordenes_envio_lab =[];
                $("#modal-despachos").modal('hide');
                document.getElementById("cant-env").innerHTML="";
            }
        });//fin ajax
    }
}

function printDespacho(correlativo,tipo_desp,sucursal){

    var form = document.createElement("form");
    form.target = "_blank";
    form.method = "POST";
    form.action = "imprimir_despacho_lab.php";

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "correlativo";
    input.value = correlativo;
    form.appendChild(input);

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "tipo_desp";
    input.value = tipo_desp;
    form.appendChild(input);

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "sucursal";
    input.value = sucursal;
    form.appendChild(input);

    document.body.appendChild(form)
    form.submit();
    document.body.removeChild(form);

}

initDesp()