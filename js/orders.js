function init(){
 listar_ordenes();
}

 function listar_ordenes(){
  $("#datatable_ordenes_china").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      dom: 'Bfrti',
      buttons: ['excelHtml5'],
      "searching": true,
      "ajax":
        {
          url: '../ajax/orders.php?op=get_ordenes',
          type : "post",
          dataType : "json",        
          error: function(e){
            console.log(e.responseText);  
          }
        },
    "language": {
      "sSearch": "Buscar:"
    }, 
    "iDisplayLength": 100,
    }).buttons().container().appendTo('#datatable_ordenes_wrapper .col-md-6:eq(0)');

 }

 function buscarRangoAros(){
  let inicio = $("#desde_env_aros").val();
  let hasta = $("#hasta_env_aros").val();
  table_enviados = $('#datatable_ordenes_china').DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax":{
      url:"../ajax/orders.php?op=get_ordenes_aros_enviar",
      type : "POST",
      data :{inicio:inicio,hasta:hasta},
      dataType : "json",
      error: function(e){
      console.log(e.responseText);
    },},
    "bDestroy": true,
    "responsive": true,
    "bInfo":true,
    "iDisplayLength":50,//Por cada 10 registros hace una paginación
    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
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
      "sFirst":"Primero","sLast":"Último","sNext":"Siguiente","sPrevious": "Anterior"       
      },      
      "oAria": {       
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",       
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"       
      }
    }, //cerrando language
  });
}

function verImagen(img,modelo,horizontal,vertical,puente){
  $('#imagen_aro_order').modal('show');
  document.getElementById("imagen_aro_ord").src="images/"+img;
  $("#modelo_send").html(modelo);
  $("#horizontal_send").val(horizontal);
  $("#vertical_send").val(vertical);
  $("#puente_send").val(puente);  
}


/**************************
ARREGLO ORDENES ENVIAR ARO
***************************/
var ordenes_enviar = [];
$(document).on('click', '.ordenes_enviar', function(){
  let id_orden = $(this).attr("value");
  let paciente = $(this).attr("name");
  let id_item = $(this).attr("id");
  let checkbox = document.getElementById(id_item);
  let check_state = checkbox.checked;

  if (check_state) {
    let obj = {
      id_orden : id_orden,
      paciente : paciente,
      id_item  : id_item
    }
    ordenes_enviar.push(obj);
  }else{
     let indice = ordenes_enviar.findIndex((objeto, indice, ordenes_enviar) =>{
      return objeto.id_orden == id_orden
    });
    ordenes_enviar.splice(indice,1)
  }
  console.log(ordenes_enviar)
});

/*****************************
        ENVIAR AROS
******************************/
//xls = [];
function send_aros(){

  let modelo = $("#modelo_send").html();
  let horizontal = $("#horizontal_send").val();
  let vertical = $("#vertical_send").val();
  let puente = $("#puente_send").val();
  let cantidad = $("#cant_enviar").val();
  let dest_aro = $("#dest_send_aro").val();
  $.ajax({
      url:"../ajax/orders.php?op=send_aros",
      method:"POST",
      cache:false,
      data: {modelo:modelo,horizontal:horizontal,vertical:vertical,puente:puente,cantidad:cantidad,dest_aro:dest_aro},
      dataType:"json",
      success:function(data){
        console.log(data);
       for(let i in data){
        console.log(data[i].codigo);
        console.log(data[i].paciente);
       }
      }

});
}


init();