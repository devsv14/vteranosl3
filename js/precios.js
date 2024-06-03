dt_template('dt-precio-ordenes','dt_get_sucursal_montos');

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
        url: "../ajax/precios.php?op=" + url,
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