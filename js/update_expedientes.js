//Variable para almacenar los datos a actualizar de los expedientes
var data_update_expedientes = []; //Array de datos

function getDataFacturaSucursales(sucursal,factura){
    
    $.ajax({
      url:"../ajax/update_expedientes.php?op=get_data_update",
      method:"POST",
      data:{sucursal,factura},
      cache:false,
      //dataType:"json",
      success:function(data)
      {
        $("#table-update-exp").html(data);
   
      }
    })

}

function getDataFactura(){
   let sucursal = document.getElementById("sucursal-update").value;
   let factura = document.getElementById("factura-update").value;
   getDataFacturaSucursales(sucursal,factura)
}


function showOrdenesExced(tipo_lente,alto_indice,color,categoria,atendidos,facturados,diferencia){ 
    console.log(tipo_lente,alto_indice,color,categoria,atendidos,facturados,diferencia)
    let sucursal = document.getElementById("sucursal-update").value;
    $('#modal-expediente').modal();
    document.getElementById('head-modal-update-exp').innerHTML=categoria;
    dtTemplateUpdate('table-listar-exced','get_exced_fechas',sucursal,tipo_lente,alto_indice,color,categoria)
  //New code cantid dif //New code
  data_update_expedientes = [];
  document.getElementById('difExp').textContent = diferencia;
  document.getElementById('countExpUpd').textContent = '0';
}

function dtTemplateUpdate(table,route,...Args){
    //console.log(Args) //ARGUMENTOS
    tabla = $('#'+table).DataTable({      
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      "deferRender": true,
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [     
        'excelHtml5',
      ],
  
      "ajax":{
        url:"../ajax/update_expedientes.php?op="+ route,
        type : "POST",
        data: {Args},
        dataType : "json",         
        error: function(e){
        console.log(e.responseText);
      },      
    },
  
      "bDestroy": true,
      "responsive": true,
      "bInfo":true,
      "iDisplayLength": 12,//Por cada 10 registros hace una paginación
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
//var arrUpdateExp = [];
function addUpdate(idFila,dui){

     let factura = document.getElementById("factura-update").value;

    let newDate = getRandomFechaSinFinesDeSemana(factura)
    document.getElementById(dui).innerHTML=newDate;
    //Update expdi
    //Validaciones
    let diferencia = parseInt(document.getElementById('difExp').textContent);
    let check_selected = document.getElementById(idFila);
    let cantUpdExpediente = document.getElementById('countExpUpd');
    let cantidad_upd_exp = data_update_expedientes.length;
    /* if(diferencia <= cantidad_upd_exp){
      console.log('Cantidad superada')
      return 0;
    } */
    //Validacion limite
    if(data_update_expedientes.length >= 48){
      Swal.fire({
        position: 'center',
        icon: 'warning',
        title: 'Se ha superando el limite de agregar!',
        showConfirmButton: true,
        timer: 9500
      });
      check_selected.checked = false;
      return 0;
    }
    if(check_selected.checked){
      addObjExpediente(dui);
      cantUpdExpediente.textContent = data_update_expedientes.length;
      let restaDiferencia = diferencia + 1;
      if(restaDiferencia === 0){
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'No hay expedientes a actualizar!',
          showConfirmButton: true,
          timer: 9500
        });
        check_selected.checked = false;
        return 0;
      }
    }else{
      deleteObjExpediente(dui);
      cantUpdExpediente.textContent = data_update_expedientes.length;
    }
}

function getRandomFechaSinFinesDeSemana (factura){
    const fechasPorFactura = {
        1: ['2022-11-15', '2022-11-30'],
        2: ['2022-12-01', '2022-12-15'],
        3: ['2022-12-16', '2022-12-31'],
        4: ['2023-01-01', '2023-01-15'],
        5: ['2023-01-16', '2023-01-31'],
        6: ['2023-02-01', '2023-02-15'],
        7: ['2023-02-16', '2023-02-24'],
  };
  
  let fechas = fechasPorFactura[factura];
  let unDia = 24 * 60 * 60 * 1000; 
  let fechaInicioMs = new Date(fechas[0]).getTime(); 
  let fechaFinMs = new Date(fechas[1]).getTime();
  let rangoMs = fechaFinMs - fechaInicioMs; 

  let fechaAleatoriaMs = Math.random() * rangoMs + fechaInicioMs; 
  let fechaAleatoria = new Date(fechaAleatoriaMs);
 
  while (fechaAleatoria.getDay() === 0 || fechaAleatoria.getDay() === 6) {
    fechaAleatoriaMs += unDia;
    fechaAleatoria = new Date(fechaAleatoriaMs);
  } 
  let year = fechaAleatoria.getFullYear();
  let mes = String(fechaAleatoria.getMonth() + 1).padStart(2, '0');
  let dia = String(fechaAleatoria.getDate()).padStart(2, '0');
  let fechaFormateada = `${dia}-${mes}-${year}`;

  return fechaFormateada;
} 

/**
 * Update expedientes
 */
//Agrega datos al objeto
function addObjExpediente(dui){
  //let dui
  let fecha_act = document.getElementById(dui).textContent;
  let objPaciExpediente = {
    dui: dui,
    fecha_act
  };
  data_update_expedientes.push(objPaciExpediente)
  //Data persistente
  sessionStorage.setItem('updExpedientes',JSON.stringify(data_update_expedientes))
}
//Elimina datos al objeto
function deleteObjExpediente(dui){
  ///code delete data duplicado
  let newDataUpdExpedientes = data_update_expedientes.filter((item,index) => item.dui !== dui )
  data_update_expedientes = newDataUpdExpedientes;
  //Datos persistentes
  sessionStorage.setItem('updExpedientes',JSON.stringify(data_update_expedientes))
}

//Update
function procesarOrdExp(){
  let data = data_update_expedientes;
  if(data.length > 0){
    $.ajax({
      url:"../ajax/update_expedientes.php?op=updateOrdExpediente",
      method:"POST",
      data:{data},
      cache:false,
      dataType:"json",
      success:function(data)
      {
        if(data === "msgUpdOk"){
          let cantidaExp = data_update_expedientes.length;
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: cantidaExp + ' expedientes se han actualizado exitosamente!',
            showConfirmButton: true,
            timer: 9500
          });
          generarPDF(data_update_expedientes);
          data_update_expedientes = [];
          $("#table-listar-exced").DataTable().ajax.reload(null, false);
        }
        $('#modal-expediente').modal('hide');
   
      }
    })
  }else{
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

//GENERAR PDF
function generarPDF(data){

  var form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  form.action = "imprimir_new_expedientes.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "data";
  input.value = JSON.stringify(data);
  form.appendChild(input);

  document.body.appendChild(form)
  form.submit();
  document.body.removeChild(form);

}