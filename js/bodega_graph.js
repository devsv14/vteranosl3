
var itemsOrders = [] //ITEMS PARA GUARDAR ORDEN DIGITAL
//TOAST ALERT
const toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
/**
 * Funcion para generar alertas
 * @param {*} type 
 * @param {*} message 
 */
function Toast_alert(type, message) {
  toast.fire({
    icon: type,
    title: message
  })
}

function dt_template(id_html, url, data) {
  $('#' + id_html).DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/bodega_av_plus.php?op=" + url,
      type: "POST",
      dataType: "json",
      data: data,
      error: function (e) {
        console.log(e.responseText);
      },
    },

    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 40,//Por cada 10 registros hace una paginación
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
 * CODE DE GRAFICOS EN MODULO BODEGA
 * 
*/
//Data para mostrar en el datable
$(document).ready(() => {
  dt_template('dtable_cantidad_orden_mes', 'listar_cant_orden_mes')
})

$(document).ready(() => {
  dt_template('dtable_order_month', 'get_count_order_month')
})
/**
*Funcion para mostrar grafico por mes
*/
function show_graph_mes(fecha) {
  $("#modal_graph").modal('show')
  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=generate_graph_month",
    method: "POST",
    data: { fecha: fecha },
    cache: false,
    dataType: "json",
    success: function (data) {
      let ordenesRecibidas = data.ordenesRecibidas;
      let ordenesDespachadas = data.ordenesDespachadas;
      //Tratamiento de fechas
      let dataFechasUno = ordenesRecibidas.map(orden => orden.fecha)
      let dataFechasDos = ordenesDespachadas.map(orden => orden.fecha)
      let data1 = ordenesRecibidas.map(orden => parseInt(orden.cantidad))
      let data2 = ordenesDespachadas.map(orden => parseInt(orden.cantidad))
      //
      let arrayFecha = fecha.split('-');
      document.getElementById('stringMes').innerText = get_string_month(parseInt(arrayFecha[1])) + " de "
      document.getElementById('year').innerText = arrayFecha[0]
      //Generamos los graficos
      generate_graph('order-grafico1','PRODUCCIONES DE LENTES AV PLUS (INGRESOS)',dataFechasUno,data1,'#7cb5ec')
      generate_graph('order-grafico2','PRODUCCIONES DE LENTES AV PLUS (DESPACHO)',dataFechasDos,data2,'#28a745')

    }//Fin success
  });
}
/**
 * 
 * mostrar grafico por semana
 */
function show_grafico_week(mes) {
  $("#mdal_show_grafico").modal('show')
  $.ajax({
    url: "../ajax/bodega_av_plus.php?op=generate_graph_week",
    method: "POST",
    data: { mes: mes },
    cache: false,
    dataType: "json",
    success: function (data) {
      console.log(data)
      document.getElementById('stringMes').innerHTML = ''
      document.getElementById('year').innerHTML = ''
      let ordenesRecibidas = data.ordenesRecibidas;
      let ordenesDespachadas = data.ordenesDespachadas;
      let semana = ordenesRecibidas.map(orden => "Semana " + orden.semana)
      //Cantidad de ordenes recibidas
      let data1 = ordenesRecibidas.map(orden => orden.cantidad)
      //Cantidad de ordenes despachadas
      let data2 = ordenesDespachadas.map(orden => orden.cantidad)
      //Genera el grafico
      //generate_graph('ordenes_graph', 'Cantidad de ordenes', semana, data1, data2, 'bar')

    }//Fin success
  });
}
/**
 * @Autor Jose Deodanes
 * Funcion para generar graficos
 * Importante declarar la variable let myChart antes de la llamada de la funcion
 */
let myChart;
function generate_graph(id,title,categorias,data1, color) {
  Highcharts.chart(id, {
    chart: {
      type: 'column'
    },
    title: {
      text: title
    },
    xAxis: {
      categories: categorias
    },
    yAxis: {
      title: {
        text: 'Cantidad'
      }
    },
    series: [{
      name: 'INGRESOS',
      data: data1,
      color: color,
      tooltip: {
        pointFormatter: function () {
          return '<span style="color:' + this.color + '">\u25CF</span> ' + this.series.name + ': <b>' + this.y + '</b><br/>' + 'Fecha: ' + this.category;
        }
      },
      dataLabels: {
        enabled: true,
        align: 'center',
        color: '#202020',
        style: {
          fontSize: '15px',
          fontWeight: 'bold'
        },
        formatter: function () {
          return this.y;
        }
      }
    }]
  });
}
/**
 * @Autor Jose Deodanes
 * @Description La funcion retorna el mes en string 
 */
function get_string_month(num_month) {
  let arry_month = { 01: 'Enero', 02: 'Febrero', 03: 'Marzo', 04: 'Abril', 05: 'Mayo', 06: 'Junio', 07: 'Julio', 08: 'Agosto', 09: 'Septiembre', 10: 'Octubre', 11: 'Noviembre', 12: 'Diciembre' };
  return arry_month[num_month];
}
/**
 * METHODO para cargar la cantidad de ordenes ingresadas en departamento
 */

try {
  function cantidad_ordenes_por_dia() {
    $.ajax({
      url: "../ajax/bodega_av_plus.php?op=number_ordenes_diarios",
      method: "POST",
      cache: false,
      data: {},
      dataType: "json",
      success: function (data) {
        document.getElementById('n_order_recibidas').textContent = data.cantOrdenesRecibidas
        document.getElementById('n_order_despachadas').textContent = data.cantOrdenesDespachadas
      }//Fin success
    });
  }
  setInterval(cantidad_ordenes_por_dia, 1000)
} catch (err) {
  console.log('Error: ' + err)
}