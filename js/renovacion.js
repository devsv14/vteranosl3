dtTemplateCitasL1('dtable_citas_lic1',"citados_licitacion_uno",'0')
function dtTemplateCitasL1(table,route,...Args){
  console.log('Aws')
  tabla = $('#renovacion_lentes_listar').DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [     
      'excelHtml5',
    ],

    "ajax":{
      url:"../ajax/citados.php?op="+ route,
      type : "POST",
      data: {Args:Args},
      dataType : "json",
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