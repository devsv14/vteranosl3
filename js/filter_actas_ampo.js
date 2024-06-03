function filter_ampo_acta(element){
    let sucursal = element.value; 
    $.ajax({
        url:"../ajax/filter_actas.php?op=filter_acta",
        method:"POST",
        cache:false,
        data : {sucursal:sucursal},
        dataType:"json",
        success:function(data){
            let contador = parseInt(data);
            let series = [];
            let init_serie = 0;
            let end_serie = 0;
            document.getElementById('ampos-order').innerHTML='';
            for (let i = 0; i < contador; i ++) {
               end_serie +=125;              
               let current_serie = `${init_serie}-125`;
               series.push(current_serie);
               init_serie = end_serie;               
               let divElement = document.createElement('div');

               divElement.className = 'col-sm-3';
               divElement.innerHTML = `<a class="btn btn-app" onclick="getActasRango('${current_serie}','${sucursal}',${i+1})"><i class="far fa-folder-open"></i> Ampo ${i+1}</a>`;
           
               document.getElementById('ampos-order').appendChild(divElement);
                
            }
            
        }
      });

}

function getActasRango(serie,sucursal,ampo){
    $("#actas-por-ampo").modal('show');
    document.getElementById("title-ampo-act").innerHTML= `AMPO ${ampo} - SUCUSAL ${sucursal}`
    dtActasAmpo(serie,sucursal)
}


function dtActasAmpo(serie,sucursal) {
      console.log(serie)
      $('#detalle-actas-ampo').DataTable({
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Brtip',//Definimos los elementos del control de tabla
      buttons: ['excelHtml5'],
      "ajax": {
        url: "../ajax/filter_actas.php?op=get_actas_por_ampo",
        type: "POST",
        data:{serie,sucursal},
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      "bDestroy": true,
      "responsive": true,
      "bInfo": true,
      "iDisplayLength": 200,//Por cada 10 registros hace una paginación
      "order": [[0, "desc"]],//Ordenar (columna,orden
      "language": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",       
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero", "sLast": "Último", "sNext": "Siguiente", "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }, //cerrando language
    });
  }

  function buscarActaEnampo(dui){
    const elementos = document.querySelectorAll('.all-actas-ampos');
    let idspan = 'estado' + dui;
    let encontrada = false;

    elementos.forEach(elemento => {
        if (elemento.id === idspan) {
            encontrada = true;
        }
    });

    if (encontrada) {
        Swal.fire({position: 'top-center',icon: 'success',title: 'ENCONTRADO: '+dui,
        timer: 3500});
        let span_act = document.getElementById("estado"+dui);
        span_act.innerHTML = 'Encontrado';span_act.style.color='green'
    } else {
        Swal.fire({position: 'top-center',icon: 'error',title: 'NO ENCONTRADO: '+dui,
        timer: 9500});
    }
    let inputSearch =  document.getElementById("search-acta-ampo");
    inputSearch.value=''; inputSearch.focus();
  }