document.querySelectorAll(".acc-acciones-opt").forEach(i => i.addEventListener("click", e => {
    ordenes_ingresar = [];
    $("#items-ordenes-registrar").html('');
    let accion = i.dataset.acciones;
    console.log(accion)
    $("#acc-optica").val(accion);
    if(accion=='ingreso_orden_optica'){
        $('#modal_acciones_veteranos').on('shown.bs.modal', function() {
            $("#get_data_orden").val("");
            $('#get_data_orden').focus();
        });
    }
}));

/* function alerts(){
    Swal.fire(msj
        'Orden editada!!',
        'Existosamente',
        'success'
    )
} */


var ordenes_ingresar = [];

function getOrdenAct() {
    let paciente_dui = $("#get_data_orden").val();
    paciente_dui = paciente_dui.replace("'","-");
    paciente_dui = document.getElementById('get_data_orden').value = paciente_dui;
    paciente_dui = paciente_dui.toUpperCase();
    console.log(paciente_dui)
    let tipo_accion = $("#acc-optica").val();
    let validaInput = paciente_dui.slice(0,3);
    console.log(tipo_accion);
    if(validaInput != 'DSP'){
    $.ajax({
      url: "../ajax/acciones_optica.php?op=get_data_orden_barcode",
      method: "POST",
      data: { paciente_dui: paciente_dui, tipo_accion: tipo_accion },
      cache: false,
      dataType: "json",
      success: function (data) {
      console.log(data.msj)
      if(data.msj=="ok"){
        let indice = ordenes_ingresar.findIndex((objeto, indice, ordenes_ingresar) => {
            return objeto.dui == data.datos.dui;
          });
         if(indice<0){
            let obj = {
                dui: data.datos.dui,
                paciente: data.datos.paciente,
                sucursal: data.datos.sucursal,
                fecha: data.datos.fecha,
                accion: tipo_accion
             }
            ordenes_ingresar.push(obj);
            $("#get_data_orden").val("");
            $('#get_data_orden').focus();
            listar_ordenes_registrar();
         }else{
            Swal.fire('Orden existe en la lista!!','Advertencia','warning');
            $("#get_data_orden").val("");
            $('#get_data_orden').focus();
         }
         
        }else if(data.msj=="vacio"){
            Swal.fire('Codigo invalido!!','Advertencia','error');
            $("#get_data_orden").val("");
            $('#get_data_orden').focus();
        }else if(data.msj=="error"){
            
            Swal.fire({
                title: 'Orden ya ha sido Ingresada',
                text: "Cofirmar que se trata de rectificación!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ingresar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.isConfirmed) {
                    let indice = ordenes_ingresar.findIndex((objeto, indice, ordenes_ingresar) => {
                        return objeto.dui == data.datos.dui;
                      });
                    if(indice<0){
                    let obj = {
                        dui: data.datos.dui,
                        paciente: data.datos.paciente,
                        sucursal: data.datos.sucursal,
                        fecha: data.datos.fecha,
                        accion: 'ingreso_rectificacion'
                     }
                    ordenes_ingresar.push(obj);
                    $("#get_data_orden").val("");
                    $('#get_data_orden').focus(); 
                    listar_ordenes_registrar()
                   }else{
                    Swal.fire('Orden existe en la lista!!','Advertencia','warning')
                 }
              }
              })
        }
        
        
  
      }//Fin success
    });//Fin Ajax
  }else{
    getDataCodDespacho(paciente_dui);
  }

  }
  ////Registrar ingresos p[or codigo de despacho
  function getDataCodDespacho(cod_despacho){
    let sucursal = document.getElementById('sucursal').value;
    
    ordenes_ingresar = []
    $.ajax({
      url:"../ajax/acciones_optica.php?op=get_data_despacho_lab",
      method:"POST",
      data:{cod_despacho:cod_despacho},
      cache: false,
      dataType:"json",
      success:function(data){
        data.forEach(el => {
          if(el.msj=='ok'){
            let obj = {
              dui: el.datos.dui,
              paciente: el.datos.paciente,
              sucursal,
              fecha: el.datos.fecha,
              accion: 'ingreso_orden_optica'
           }
          ordenes_ingresar.push(obj);
          $("#get_data_orden").val("");
          $('#get_data_orden').focus();
          listar_ordenes_registrar();
           
          }else if(el.msj=='error'){
            
            Swal.fire({
              title: 'SE TRATA DE UNA RECTIFICACION?',
              text: "Orden ya ha sido ingresada!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Aceptar!'
            }).then((result) => {
              if (result.isConfirmed) {
                console.log(el.datos.paciente,el.datos.dui)
               
              }
            })
          }
         
        });
      }
    });///fin ajax
  }

  function listar_ordenes_registrar() {

    $("#items-ordenes-registrar").html('');
  
    let filas = "";
    let length_array = parseInt(ordenes_ingresar.length) - 1;
    for (let i = length_array; i >= 0; i--) {
  
      filas = filas +
        "<tr style='text-align:center' id='item_t" + i + "'>" +
        "<td>" + (parseInt(i)+1) + "</td>" +
        "<td>" + ordenes_ingresar[i].dui + "</td>" +
        "<td>" + ordenes_ingresar[i].fecha + "</td>" +
        "<td>" + ordenes_ingresar[i].paciente + "</td>" +
        "<td>" + ordenes_ingresar[i].sucursal + "</td>" +
        "<td>" + "<button type='button'  class='btn btn-sm bg-light' onClick='eliminarItemBarcodeOpt(" + i + ")'><i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i></button>" + "</td>" +
        "</tr>";
    }
  
    $("#items-ordenes-registrar").html(filas);
  
  }

  function eliminarItemBarcodeOpt(index) {
    $("#item_t" + index).remove();
    ordenes_ingresar.splice(index, 1);
    $('#reg_ingresos_barcode').focus();
    listar_ordenes_registrar()    
  }

function registrarIngresoOrdenOpt(){
    $("#btn-acc-opt").attr('disabled',true);
    let tam_array = ordenes_ingresar.length;
    if(tam_array==0){Swal.fire('Lista vacia!!','Agregar ordenes de ingreso','warning'); return false}
    $("#modal_acciones_veteranos").modal('hide');
    
    $.ajax({
      url: "../ajax/acciones_optica.php?op=registrar_accion",
      method: "POST",
      data: { 'arrayOrdenesAccOpt': JSON.stringify(ordenes_ingresar)},
      cache: false,
      dataType: "json",
      success: function (data) {
        console.log(data)
        if(data.msj=="success-act"){
          $("#btn-acc-opt").attr('disabled',false);
          ordenes_ingresar=[];
          $("#items-ordenes-registrar").html('');
          Swal.fire('Exito!!','Se ha realizdo un ingreso','success');
          $("#ordenes_recibidas_data").DataTable().ajax.reload(null, false);
        }else{Swal.fire('Ha ocurrido un error!!','No ha sido posible registrar el ingreso','warning')}
      }//Fin success
    });

}

function getOrdenesIngresadas(){
  dtTemplateAccOpticas('ordenes_recibidas_data','get_ordenes_ing',0)
}

function dtTemplateAccOpticas(table,route,...Args){
    console.log(Args) //ARGUMENTOS
    tabla = $('#'+table).DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [     
      'excelHtml5',
    ],

    "ajax":{
      url:"../ajax/acciones_optica.php?op="+ route,
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
getOrdenesIngresadas()