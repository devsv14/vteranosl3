var Toast = Swal.mixin({
  toast: true,
  position: 'top-center',
  showConfirmButton: false,
  timer: 2000
});
document.addEventListener('keydown',handleInputFocusTransfer);
function handleInputFocusTransfer(e){
  const focusableInputElements= document.querySelectorAll('.cant_ingreso');  
  const focusable= [...focusableInputElements]; 
  const index = focusable.indexOf(document.activeElement);
  let nextIndex = 0;

  if (e.keyCode === 38) {
    e.preventDefault();
    nextIndex= index > 0 ? index-1 : 0;
    focusableInputElements[nextIndex-1].focus();
    focusableInputElements[nextIndex-1].select();  

  }else if (e.keyCode === 40) {
    e.preventDefault();
    nextIndex= index+1 < focusable.length ? index+1 : index;
    focusableInputElements[nextIndex+1].focus();
    let ids = focusableInputElements[nextIndex+1].select();
  }else if(e.keyCode === 37){
    e.preventDefault();
    nextIndex= index > 0 ? index-1 : 0;
    focusableInputElements[nextIndex].focus();
    let ids = focusableInputElements[nextIndex].select();
  }else if(e.keyCode === 39){
    e.preventDefault();
    nextIndex= index+1 < focusable.length ? index+1 : index;
    focusableInputElements[nextIndex].focus();
    let ids = focusableInputElements[nextIndex].select();
  }
}


function init(){
  listar_descargos_bodega();
  $(".modal-header").on("mousedown", function(mousedownEvt) {
    let $draggable = $(this);
    let x = mousedownEvt.pageX - $draggable.offset().left,
        y = mousedownEvt.pageY - $draggable.offset().top;
    $("body").on("mousemove.draggable", function(mousemoveEvt) {
    $draggable.closest(".modal-dialog").offset({
    "left": mousemoveEvt.pageX - x,
      "top": mousemoveEvt.pageY - y
    });
    });
    $("body").one("mouseup", function() {
      $("body").off("mousemove.draggable");
    });
    $draggable.closest(".modal").one("bs.modal.hide", function() {
        $("body").off("mousemove.draggable");
    });
  });
    listar_descargos();
}


function get_dataTableTerm(id_tabla,div_tabla){
	  $.ajax({
      url:"../ajax/stock.php?op=get_tableTerm",
      method:"POST",
      data : {id_tabla:id_tabla},
      cache:false,
      //dataType:"json",
      success:function(data){   
      	$("#"+div_tabla).html(data);
      }
    });

}


function getDataIngresoModal(esfera,cilindro,codigo,marca,diseno,titulo,id_td,id_tabla){
	$('#modal_ingresos_term').modal('show');
	
	$('#cant_ingreso').val('0');
	$('#cant_descargo').val('0');
	
	$('#codigo_lente_term').val(codigo);
	$('#marca_lente').val(marca);
	$('#dis_lente').val(diseno);
	$('#esfera_terminado').val(esfera);
	$('#cilindro_terminado').val(cilindro);
	$('#title_modal_term').html(titulo);
	$('#id_td').val(id_td);
	$('#id_tabla').val(id_tabla);
  $('#cant_ingreso').val('0');
	if (codigo != ""){ 
   document.getElementById('codigo_lente_term').readOnly = true;
  }else{
    document.getElementById('codigo_lente_term').readOnly = false;
  }
}

function editCode(){
  $("#new_barcode_lens").modal('show');
  $('#new_barcode_lens').on('shown.bs.modal', function() {
  $('#codebar_lente').val('');
  $('#codebar_lente').focus();
  });
}


key('escape', function(){
  clearCode();
});


function clearCode(){
  $('#codigo_term_ingreso').focus();
  $('#codigo_term_ingreso').val('');
}

/*================UPDATE STOCK TERMINADOS====================*/
key('enter', function(){
  setStockTerminados();
});

function setStockTerminados(){

  let cat_codigo = $("#categoria_codigo").val();
  let codigoProducto = $("#codigo_lente_term").val();
  let cantidad_ingreso = $("#cant_ingreso").val();
  let marca = $("#marca_lente").val();
  let diseno = $("#dis_lente").val();
  let esf = $("#esfera_terminado").val();
  let cil = $("#cilindro_terminado").val();
  let id_td = $("#id_td").val();
  let id_tabla = $("#id_tabla").val();
  let titulo = $("#title_modal_term").html();
  let cant_descargo = $("#cant_descargo").val();
  let usuario = $("#user_term").val();

  let operacion = '';

  if ((cantidad_ingreso != 0 || cantidad_ingreso !="")  && (cant_descargo==0 || cant_descargo=="")) {
      operacion = 'Ingreso';
  }else if((cantidad_ingreso == 0 || cantidad_ingreso == "")  && (cant_descargo !=0 || cant_descargo !="")){
      operacion = 'Descargo';
  }

  if (codigoProducto=="" || codigoProducto==null || codigoProducto==undefined){
	$("#new_barcode_lens").modal('show');
	$('#new_barcode_lens').on('shown.bs.modal', function() {
	$('#codebar_lente').val('');
	$('#codebar_lente').focus();
	});
	  return false;
  }
  if(cant_descargo == 0 && cantidad_ingreso==0){
    alerts_productos("error", "Debe Especificar si es un ingreso o descargo");
    return false; 
  
  }
  if(cantidad_ingreso=="0" && operacion=="Ingreso") {
	  alerts_productos("error", "Debe Especificar la cantidad a ingresar");
	  return false; 
	}else if(cant_descargo=="0" && operacion=="Descargo"){
    alerts_productos("error", "Debe Especificar la cantidad a descargar");
    return false; 
  }

   $.ajax({
    url:"../ajax/stock.php?op=update_stock_terminados",
    method:"POST",
    data:{codigoProducto:codigoProducto,cantidad_ingreso:cantidad_ingreso,id_tabla:id_tabla,esf:esf,cil:cil,id_td:id_td,cat_codigo:cat_codigo,operacion:operacion,cant_descargo:cant_descargo,usuario:usuario},
    cache: false,
    dataType:"json",
    success:function(data){
    	console.log(data);
        $("#modal_ingresos_term").modal('hide');
      if(data=="insertar"){        
        alerts_productos("success", "Producto inicializado en bodega");
      }else if(data=="Editar"){
      	alerts_productos("info", "El stock ha sido actualizado");
      }else if(data=='descargos'){
        alerts_productos("success", "Descargo registrado");
      }else{
        alerts_productos("warning", data);
      }

      id_div = 'tabla_term'+id_tabla
      get_dataTableTerm(id_tabla,id_div);
      getNewStockTerm(id_td,id_tabla,codigoProducto);      
    }      
  });  

}/*============ FIN UPDATE STOCK TERMINADOS ================*/

/*============= GET NEW STOCK ITEM ====================*/
function getNewStockTerm(id_td,id_tabla,codigoProducto){
	$.ajax({
    url:"../ajax/stock.php?op=new_stock_terminados",
    method:"POST",
    data:{codigoProducto:codigoProducto,id_tabla:id_tabla,id_td:id_td},
    cache: false,
    dataType:"json",
    success:function(data){
    console.log(data);
    document.getElementById(id_td).innerHTML=data.stock;
    document.getElementById(id_td).style.background='#5bc0de';
    document.getElementById(id_td).style.color='white';
    }      
  }); 
}/*============= FIN GET NEW STOCK ITEM ====================*/

function downloadExcelTerm(tabla,title,fecha){
  let titulo = fecha+"_"+title;
  let tablaExport = document.getElementById(tabla);
  if(tablaExport == null || tablaExport == undefined ){
  	alerts_productos("warning", "Debe desplegar la tabla para poder ser descargada");
  	return false;
  }

  let table2excel = new Table2Excel();
  table2excel.export(document.getElementById(tabla),titulo);
}

function downloadExcelTermx(){


  let table2excel = new Table2Excel();
  table2excel.export(document.getElementById("tabla_base"));
}

key('⌘+i, ctrl+i', function(){ 
  ingresosGeneral();
});

function ingresosGeneral(){  
  $("#modal_ingresos_term_general").modal('show');
  $('#modal_ingresos_term_general').on('shown.bs.modal', function() {
  $('#codigo_term_ingreso').val('');
  $('#codigo_term_ingreso').focus();
  });

  $('#modal_ingresos_term_general').modal({
  backdrop: 'static',
  keyboard: true
})
  ingresos_inventario = [];
  $("#items_ingresos_terminados").html("");
}

var ingresos_inventario = [];
function getLenteTermData(){  
    let codigoTerminado = $('#codigo_term_ingreso').val();
    $.ajax({
    url:"../ajax/stock.php?op=getDataTerminados",
    method:"POST",
    data:{codigoTerminado:codigoTerminado},
    cache: false,
    dataType:"json",
      success:function(data){
      console.log(data)
      if(data!="Vacio"){
        let item_lente = {
          marca : data.marca,
          diseno : data.diseno,
          esfera : data.esfera,
          cilindro : data.cilindro,
          cantidad : 1,
          costo : 0,
          stock : data.stock,
          codigo : data.codigo
        }
        ingresos_inventario.push(item_lente);
        listar_items_ingreso_term();
        clearCode();
      }else if(data=="Vacio"){
        alerts_productos("error", "Codigo no existe");
        var error = document.getElementById("error_sound_ingreso"); 
        error.play();
        clearCode();
      } 
    }     
  });
}


function listar_items_ingreso_term(){
  $("#items_ingresos_terminados").html("");
  var filas = '';
  let tam_array = (ingresos_inventario.length)-1; 
  for(var i=tam_array; i >= 0; i--){
    var filas = filas + "<tr id='fila"+i+"'>"+
    "<td>"+(i+1)+"</td>"+
    "<td>"+ingresos_inventario[i].codigo+"</td>"+
    "<td>"+ingresos_inventario[i].marca+"</td>"+
    "<td>"+ingresos_inventario[i].diseno+"</td>"+
    "<td>"+ingresos_inventario[i].stock+" U."+"</td>"+
    "<td>"+ingresos_inventario[i].esfera+"</td>"+
    "<td>"+ingresos_inventario[i].cilindro+"</td>"+
    "<td>"+"<input type='text' value='1' class='form-control cant_ingreso' style='height:25px;text-align:center' id='cant_"+i+"' onClick='setCantidadIng(event, this, "+(i)+");' onKeyUp='setCantidadIng(event, this, "+(i)+");'>"+"</td>"+
    "<td>"+"<input type='text' value='1' class='form-control cant_ingreso'style='height:25px;text-align:center'>"+"</td>"+
    "<td>"+"<i class='fas fa-trash' onClick='eliminarFila("+i+");'></i>"+"</td>"+        
    "</tr>";
  }
  
  $('#items_ingresos_terminados').html(filas);
}

function setCantidadIng(event, obj, idx){
    event.preventDefault();
    ingresos_inventario[idx].cantidad = parseInt(obj.value);
}

  function eliminarFila(index) {
    $("#fila" + index).remove();
    drop_index(index);
  }

  function drop_index(position_element){
    ingresos_inventario.splice(position_element, 1);
    listar_items_ingreso_term();
  }

function setStockTerminadosUpdate(){
  let codigo = $("#codigo_ingreso").val();
  let td = $("#id_td_ingreso").val();
  let id_tabla = $("#id_tabla_ingreso").val();

  $.ajax({
    url:"../ajax/stock.php?op=updateTerminados",
    method:"POST",
    data:{codigo:codigo,td:td,id_tabla:id_tabla},
    cache: false,
    dataType:"json",
    success:function(data){

    }
  });
}


function registroMultiple(){
 $.ajax({
 url:"../ajax/stock.php?op=registroMultiple",
 method: "POST",
 data: {'lentesUpdate':JSON.stringify(ingresos_inventario)},
 cache:  false,
 dataType: "json",
 success:function(data){
   $("#modal_ingresos_term_general").modal("hide");
   alerts_productos("success", "El inventario ha sido actualizado exitosamente");
   //get_dataTableTerm();
 }
 });//Fin Ajax
}

/**************************************************
==================== BASES INVENTARIO =============
****************************************************/
/*-------------- SHOW TABLAS BASES----------*/
function get_dataTableBase(id_div,marca){
 $.ajax({
      url:"../ajax/stock.php?op=get_tableBaseVs",
      method:"POST",
      data : {marca:marca},
      cache:false,
      //dataType:"json",
      success:function(data){   
         console.log(data)
        $("#"+id_div).html(data);
      }
    });
}

function initStockBasesvs(base,codigo,id_tabla,marca,diseno,id_td){
  $("#tipo_lente_code").val("Base");
  $("#modal_ingresos_basevs").modal('show');
  $("#title_modal_bases").html(`Ingreso a Inventario base ${base}, ${diseno}`);
  $("#codigo_lente_term").val(codigo);
  $("#marca_basevs").val(marca);
  $("#base_basevs").val(base);
  $("#id_td_base").val(id_td);
  $("#id_tabla_base").val(id_tabla);
  $("#dis_base").val(diseno);
  $("#cant_ingreso_base").val('0');

  if (codigo==''){
    $("#new_barcode_lens").modal('show');
    $('#new_barcode_lens').on('shown.bs.modal', function() {
    $('#codebar_lente').val('');
    $('#codebar_lente').focus();
  });
  }
}
/*===============  INICIALIZAR STOCK BASES =================*/
function setStockBases(){

  let codigoProducto = $("#codigo_lente_term").val();
  let diseno = $("#dis_base").val();
  let base = $("#base_basevs").val();
  let cantidad = $("#cant_ingreso_base").val();
  let comprobante = $("#comprobante_base").val();
  let costo = $("#comprobante_base").val();
  let id_td = $("#id_td_base").val();
  let id_tabla = $("#id_tabla_base").val();
  let cat_codigo = $("#categoria_codigo").val();
  let marca = $("#marca_basevs").val();
  let id_div = "base"+marca;

  if (codigoProducto=="" || codigoProducto==null || codigoProducto==undefined){
    $("#new_barcode_lens").modal('show');
    $('#new_barcode_lens').on('shown.bs.modal', function() {
    $('#codebar_lente').val('');
    $('#codebar_lente').focus();
  });
  return false;
  }

  if(cantidad=="0") {
    alerts_productos("error", "Debe Especificar la cantidad a ingresar");
    return false; 
  }

  $.ajax({
  url:"../ajax/stock.php?op=update_stock_basevs",
  method:"POST",
  data:{codigoProducto:codigoProducto,id_td,base:base,cantidad:cantidad,id_tabla:id_tabla,comprobante:comprobante,costo:costo,cat_codigo:cat_codigo},
  cache: false,
  dataType:"json",
  success:function(data){
    $("#modal_ingresos_basevs").modal('hide');
    if (data=='Insert'){
      alerts_productos("success", "Producto inicializado en bodega");
    }else if(data=="Edit"){
      alerts_productos("info", "El stock ha sido actualizado");
    }

     setStockBasevs(id_td,base,codigoProducto);           
  }          
  });
}
  function setStockBasevs(id_td,base,codigo){

    $.ajax({
    url:"../ajax/stock.php?op=new_stock_basevs",
    method:"POST",
    data:{codigo:codigo,base:base,id_td:id_td},
    cache: false,
    dataType:"json",
    success:function(data){    
    document.getElementById(id_td).innerHTML=data.stock;
    document.getElementById(id_td).style.background='#5bc0de';
    document.getElementById(id_td).style.color='white';
    }      
  }); 

  }

  function eliminarItemDescargo(codigo,tabla,ojo){
   document.getElementById(tabla).innerHTML="";
   if (ojo=='derecho') {
    document.getElementById("cod_lente_inv").readOnly = false;
    document.getElementById("cod_lente_inv").value = "";
    $('#cod_lente_inv').focus();
   }else{
    document.getElementById("cod_lente_oi").readOnly = false;
    document.getElementById("cod_lente_oi").value = "";
    $('#cod_lente_oi').focus();
   }
   let indice = array_items_desc.findIndex((objeto, indice, array_items_desc) =>{
      return objeto.codigo == codigo
    });
    array_items_desc.splice(indice,1)
  }


function agregarDescargo(){

  let paciente = $("#pac_orden_desc").html();
  let codigo_orden = $("#cod_det_orden_descargo").html();
  let id_optica = $("#id_optica_desc").val();
  let id_sucursal = $("#id_sucursal_desc").val();
  let id_usuario = $("#id_usuario").val();

  if (codigo_orden=="") {
    alerts_productos("error", "Orden no ha sido agregada");
    return false;
  }

  let tam_array_desc = array_items_desc.length;
  if (tam_array_desc==0) {
    alerts_productos("error", "Debe agregar productos");return false;
  }
  if (tam_array_desc==1) {
    Swal.fire({
    title: 'La orden solo posee un lente. Desea proceder?',
    text: "Confirmar",
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if(result.value){
      registrarDescargo(paciente,codigo_orden,id_optica,id_sucursal,id_usuario);
    }
  });
  }else{
    registrarDescargo(paciente,codigo_orden,id_optica,id_sucursal,id_usuario);
  }
}

function registrarDescargo(paciente,codigo_orden,id_optica,id_sucursal,id_usuario){

  $.ajax({
    url:"../ajax/stock.php?op=registrar_descargo",
    method:"POST",
    data : {'arrayItemsDescargo':JSON.stringify(array_items_desc),'paciente':paciente,'codigo_orden':codigo_orden,'id_optica':id_optica,'id_sucursal':id_sucursal,'id_usuario':id_usuario},
    cache:false,
    dataType:"json",
    success:function(data){
      if (data=="Ok"){
        $("#datatable_desc_diarios").DataTable().ajax.reload();
        clearDataOrdenDesc();
        array_items_desc = [];
        document.getElementById("cod_orden_current").readOnly = false;
        document.getElementById("cod_lente_inv").readOnly = false;
        document.getElementById("cod_lente_inv").value = "";
        document.getElementById("cod_lente_oi").readOnly = false;
        document.getElementById("cod_lente_oi").value = "";

        let tablas_descargo = document.getElementsByClassName("tabla_descargos");
        for (var i = 0; i < tablas_descargo.length; i++) {
          tablas_descargo[i].innerHTML="";
        }

      Toast.fire({icon: 'success',title: 'Descargo registrado.'})
      }else if(data=="Error"){
        alerts('error','La orden '+codigo_orden+' ya se ha registrado');
      }
    }
  });
}


function listar_descargos(){

  tabla_ordenes= $('#datatable_desc_diarios').DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [     
      'excelHtml5',
    ],

    "ajax":{
      url:"../ajax/stock.php?op=listar_descargos",
      type : "POST",
      dataType : "json",
      //data:{},           
      error: function(e){
      console.log(e.responseText);
    },           
    },

        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 25,//Por cada 10 registros hace una paginación
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
       
              "sFirst":    "Primero",
       
              "sLast":     "Último",
       
              "sNext":     "Siguiente",
       
              "sPrevious": "Anterior"
       
          },
       
          "oAria": {
       
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
       
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
       
          }

         }, //cerrando language

          //"scrollX": true

    });
}

/*------------------------  TABLAS BIFOCALES ---------------*/
function get_dataTableBasesFtop(id_tabla,id_div,marca,diseno){
  console.log(id_tabla,id_div,marca,diseno);
  $.ajax({
    url:"../ajax/stock.php?op=get_tableBaseFlaptop",
    method:"POST",
    data : {id_tabla:id_tabla,marca:marca,diseno:diseno},
    cache:false,
    success:function(data){
      $("#"+id_div).html(data);
    }
  });
}

function getDataModalFtop(codigo,marca,base,adicion,ojo,id_td,id_tabla,diseno){
 
  $('#modal_ingresos_baseftop').modal();
  $("#title_modal_basesft").html(`Ingreso a Inventario base: ${base}, adicion: ${adicion}`);
  $("#codigo_lente_ft").val(codigo);
  $("#base_baseft").val(base);
  $("#adicionft").val(adicion);
  $("#marca_baseft").val(marca);
  $("#ojo_baseft").val(ojo);
  $("#id_td_baseft").val(id_td);
  $('#id_tabla_baseft').val(id_tabla);
  $('#diseno_lente_bf').val(diseno);
  $('#cant_ingreso_baseft').val('');

  if (codigo==''){
    $("#new_barcode_lens").modal('show');
    $('#new_barcode_lens').on('shown.bs.modal', function() {
    $('#codebar_lente').val('');
    $('#codebar_lente').focus();
  });
  }else{
    $('#modal_ingresos_baseftop').on('shown.bs.modal', function() {
    $('#cant_ingreso_baseft').val('');
    $('#cant_ingreso_baseft').focus();
  });
  }
}

function setStockBasesFlaptop(){
  let codigoProducto = $('#codigo_lente_ft').val();
  let identificador =$("#id_td_baseft").val();
  let base = $('#base_baseft').val();
  let adicion = $('#adicionft').val();
  let cantidad = $('#cant_ingreso_baseft').val();
  let comprobante = $('#comprobante_baseft').val();
  let costo = $('#costo_baseft').val();
  let id_tabla = $('#id_tabla_baseft').val();
  let ojo = $('#ojo_baseft').val();
  let marca = $('#marca_baseft').val();
  let diseno =  $('#diseno_lente_bf').val();

  if (codigoProducto=="" || codigoProducto==null || codigoProducto==undefined){
    Toast.fire({icon: 'warning',title: ' Campo código vacio.', position: 'top-center'})
    $("#new_barcode_lens").modal('show');
    $('#new_barcode_lens').on('shown.bs.modal', function() {
    $('#codebar_lente').val('');
    $('#codebar_lente').focus();
  });
  return false;
  }

  if(cantidad=="0" || cantidad=="") {
    alerts_productos("error", "Debe Especificar la cantidad a ingresar");
    return false; 
  }


  $.ajax({
  url:"../ajax/stock.php?op=update_stock_baseftop",
  method:"POST",
  data:{codigoProducto:codigoProducto,identificador:identificador,base:base,adicion:adicion,cantidad:cantidad,comprobante:comprobante,costo:costo,id_tabla:id_tabla,ojo:ojo},
  cache: false,
  dataType:"json",
  success:function(data){
    $("#modal_ingresos_baseftop").modal('hide');
    if (data=='Insert'){
      alerts_productos("success", "Producto inicializado en bodega");
      setStockBaseFtp(identificador,base,adicion,codigoProducto,id_tabla,marca,diseno);
    }else if(data=="Edit"){
      alerts_productos("info", "El stock ha sido actualizado");
      setStockBaseFtp(identificador,base,adicion,codigoProducto,id_tabla,marca,diseno);
    }
  }          
  });

get_dataTableBasesFtop(id_tabla,)

}


function setStockBaseFtp(id_td,base,adicion,codigoProducto,id_tabla,marca,diseno){

    $.ajax({
    url:"../ajax/stock.php?op=new_stock_base_ftp",
    method:"POST",
    data:{codigoProducto:codigoProducto,base:base,adicion:adicion,id_tabla:id_tabla,id_td:id_td},
    cache: false,
    dataType:"json",
    success:function(data){
    get_dataTableBasesFtop(id_tabla,'contenft'+id_tabla,marca,diseno)
    let tds = document.getElementsByClassName('class-bf-td'+id_tabla);
    const recorreArray = (arr) => {
      for(let i=0; i<=arr.length-1; i++){
      let id = arr[i].id;
      var td = document.getElementById(id);
      td.style.backgroundColor = 'white';
      }
    }

    recorreArray(tds);

    document.getElementById(id_td).innerHTML=data.stock;
    document.getElementById(id_td).style.background='#5bc0de';
    document.getElementById(id_td).style.color='white';
    get_dataTableBasesFtop(id_tabla,'contenft'+id_tabla,marca,diseno);

  }

  });
}

//////////////////////////  INGRESOS A BODEGA ////////
function listar_descargos_bodega(){

  table_env = $('#data_ingresos_bodega').DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excel'],
    "ajax":{
      url:"../ajax/stock.php?op=listar_descargos_bodega",
      type : "POST",
      dataType : "json",
      //data : {laboratorio:laboratorio,cat_lente:cat_lente,inicio:inicio,hasta:hasta,tipo_lente:tipo_lente},
      error: function(e){
      console.log(e.responseText);
    },},
    "bDestroy": true,
    "responsive": true,
    "bInfo":true,
    "iDisplayLength": 72,//Por cada 10 registros hace una paginación
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

init();