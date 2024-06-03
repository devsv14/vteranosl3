function initProd(){
  dtTemplateProductos("aros_creados","listar_aros",0);
  cargar_marcas();
}


function guardar_aros(){
  let marcas = document.getElementById("marca_aros").value;
  let marca = marcas.toString();
  let modelo = document.getElementById("modelo_aro").value;
  let color = document.getElementById("color_aro").value;
  let material = document.getElementById("materiales_aro").value;

  if( marca=="" || modelo=="" || color=="" || material==""){
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Existen campos vacios',
      showConfirmButton: true,
      timer: 9500
    });
  return false;
  }

  $.ajax({
    url:"../ajax/productos.php?op=crear_aro",
    method:"POST",
    cache:false,
    data :{marca:marca,modelo:modelo,color:color,material:material},
    dataType:"json",
    success:function(data){
      if(data.msj=="ok"){
        Swal.fire({
          position: 'top-center',
          icon: 'success',
          title: 'Aro creado existosamente',
          showConfirmButton: true,
          timer: 9500
        });
        $("#nuevo_aro").modal("hide");
        $("#aros_creados").DataTable().ajax.reload();
      }else{
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Aro ya existe',
          showConfirmButton: true,
          timer: 9500
        });
      return false;
      }
    }
});   

}

function dtTemplateProductos(table,route,...Args){
    
  tabla = $('#'+table).DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [     
      'excelHtml5',
    ],

    "ajax":{
      url:"../ajax/productos.php?op="+ route,
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
    "iDisplayLength": 2000,//Por cada 10 registros hace una paginación
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
var aros_enviar_sucursal = [];
function agregarAroListaBod(id_aro){
  $.ajax({
    url:"../ajax/productos.php?op=buscar_data_aro_id",
    method:"POST",
    data:{id_aro:id_aro},
    cache: false,
    dataType:"json", 
    success:function(data){

      let indice = aros_enviar_sucursal.findIndex((objeto, indice, aros_enviar_sucursal) =>{
        return objeto.id_aro == Number(data.id_aro)
      });

      if(indice>=0){
       let cant = aros_enviar_sucursal[indice].cantidad;
       let n_cant = parseInt(cant)+1
       aros_enviar_sucursal[indice].cantidad=n_cant 
      }else{
        let aro = {
           id_aro,
           modelo:data.modelo,
           marca:data.marca,
           color:data.color,
           material:data.material,
           cantidad : 1
        }
        aros_enviar_sucursal.push(aro);
      }
      listarArosBodega();
    }
  });//fin ajax
}

function listarArosBodega(){
  $("#aros-enviar-bodega").html('');
  let filas = "";
  let length_array = parseInt(aros_enviar_sucursal.length)-1;
  for(let i=length_array;i>=0;i--){

    filas = filas + 
    "<tr id='item_t"+i+"'>"+   
    "<td>"+aros_enviar_sucursal[i].marca+"</td>"+
    "<td>"+aros_enviar_sucursal[i].modelo+"</td>"+
    "<td>"+aros_enviar_sucursal[i].color+"</td>"+
    "<td>"+aros_enviar_sucursal[i].material+"</td>"+
    "<td ><input type='number' class='form-control next-input' id=itemdist"+i+" value="+aros_enviar_sucursal[i].cantidad+" onkeyup='setCantidadAro(event, this, "+(i)+");' onClick='setCantidadAro(event, this, "+(i)+");' style='height:25px'></td>"+    
    "<td>"+"<button type='button'  class='btn btn-sm bg-light' onClick='eliminarItemAro("+i+")'><i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i></button>"+"</td>"+
    "</tr>";
    
  }

  $("#aros-enviar-bodega").html(filas);
    let sumaros = aros_enviar_sucursal.map(item => item.cantidad).reduce((prev, curr) => prev + curr, 0);
    $("#count-aros").html(sumaros+" aros seleccionados");
  }

  function setCantidadAro(event, obj, idx){

    event.preventDefault();
    aros_enviar_sucursal[idx].cantidad = parseInt(obj.value);
    let sumaros = aros_enviar_sucursal.map(item => item.cantidad).reduce((prev, curr) => prev + curr, 0);
    $("#count-aros").html(sumaros+" aros seleccionados");

  }


function eliminarItemAro(idx){
    $("#item_t" + idx).remove();
    aros_enviar_sucursal.splice(idx, 1);
    let sumaros = aros_enviar_sucursal.map(item => item.cantidad).reduce((prev, curr) => prev + curr, 0);
    $("#count-aros").html(sumaros+" aros");
    listarArosBodega()
}

const btn_bodega = document.getElementById("btn-env-suc");
btn_bodega.addEventListener("click", () => {
  $("#bodega-sucursal").val(null).trigger('change');
  if(aros_enviar_sucursal.length==0){
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Sin aros para enviar',
      showConfirmButton: true,
      timer: 2000
    });
  }else{
    $("#modal-envios-bodega").modal();
  }
});

function enviarArosSucursal(){
  let sucursal_arr = document.getElementById("bodega-sucursal").value;
  let sucursal = sucursal_arr.toString();
  if(sucursal==""){
    Swal.fire({position: 'top-center',icon: 'error',title: 'Seleccionar sucursal',showConfirmButton: true,timer: 2000 });
    return false;
  }
  let id_usuario = document.getElementById("id_usuario").value;
  $.ajax({
    url:"../ajax/productos.php?op=enviar_aros",
    method:"POST",
    data:{'arrayAros':JSON.stringify(aros_enviar_sucursal),'sucursal':sucursal,'id_usuario':id_usuario},
    cache: false,
    dataType:"json", 
    success:function(data){
      if(data.msj=="OkInsert"){
        $("#modal-envios-bodega").modal("hide");
        aros_enviar_sucursal=[];
        document.getElementById("aros-enviar-bodega").innerHTML="";
        $("#count-aros").html("");
        $("#bodega-sucursal").val(null).trigger('change');
        Swal.fire({position: 'top-center',icon: 'success',title: 'Ingreso a bodega Exitoso',showConfirmButton: true,timer: 2000 });
        detalleIngresoBodegas(data.correlativo)
      }
    }
  });//fin ajax
}

function  detalleIngresoBodegas(correlativo){
  var form = document.createElement("form");
  form.target = "print_popup";
  form.method = "POST";
  form.action = "detalle_ingreso_pdf.php";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "n_ingreso";
  input.value = correlativo;
  form.appendChild(input);
  document.body.appendChild(form)
  form.submit();
  document.body.removeChild(form);
}

const btn_bodega_stock = document.getElementById("btn-bodegas");
btn_bodega_stock.addEventListener("click", () => {
  $("#modal-stock-consumos").modal();
});

$(document).ready(function(){
  $("#bodega-existencia").change(function () {         
    $("#bodega-existencia option:selected").each(function () {
      bodega = $(this).val();
      dtTemplateProductos("aros_existencia_bd","get_existencia_bodegas",bodega);         
    });
  })
});


function registrarMarca(){
  let marca = document.getElementById("nuevaMarca").value;
  $.ajax({
    url:"../ajax/productos.php?op=crear_marca",
    method:"POST",
    data:{marca:marca},
    cache: false,
    dataType:"json",
    success:function(marcas){
      $("#new-marca").modal("hide");
      $("#marca_aros").empty();
      $("#marca_aros").select2({ data: marcas})
    }
  });///fin ajax

}

function cargar_marcas(){
  $.ajax({
    url:"../ajax/productos.php?op=cargar_marca",
    method:"POST",
    //data:{marca:marca},
    cache: false,
    dataType:"json",
    success:function(marcas){
      $("#marca_aros").empty();
      $("#marca_aros").select2({ data: marcas})
    }
  });///fin ajax
}
 
function buscarAro(){
  let sucursal = $("#sucursal").val()

  $("#aros_orden").modal();
  dtTemplateProductos("datatable_aros_ordenes","seleccionar_aro_orden",sucursal)
}

function selectAroOrden(id_aro){
  $.ajax({
    url:"../ajax/productos.php?op=buscar_data_aro_id",
    method:"POST",
    data:{id_aro:id_aro},
    cache: false,
    dataType:"json",
    success:function(data){
      document.getElementById("modelo_aro_orden").value=data.modelo;
      document.getElementById("id_aro").value=data.id_aro;
      document.getElementById("marca_aro_orden").value=data.marca;
      document.getElementById("color_aro_orden").value=data.color;
      document.getElementById("material_aro_orden").value=data.material;
      $("#aros_orden").modal("hide");
    }
  });///fin ajax
}

function printStockSucursal(){
  let sucursal = document.getElementById("bodega-existencia").value;
  if(sucursal=="0"){
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccionar bodega',
      showConfirmButton: true,
      timer: 2500
    });
  return false;
  }else{
    var form = document.createElement("form");
    form.target = "print_popup";
    form.method = "POST";
    form.action = "stock_sucursal_pdf.php";

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "suc-bodega";
    input.value = sucursal;
    form.appendChild(input);
    document.body.appendChild(form)
    form.submit();
    document.body.removeChild(form);
  }
}

initProd()




