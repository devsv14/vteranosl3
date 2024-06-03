function begin(){
document.getElementById("download_received").style.display = "none";
orders_received();
orders_processing();
}

function orders_received(){

  table_env = $('#data_lenses_received').DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax":{
      url:"../ajax/lens.php?op=get_orders_received",
      type : "POST",
      dataType : "json",
      error: function(e){
      console.log(e.responseText);
    },},
    "bDestroy": true,
    "responsive": true,
    "bInfo":true,
    "iDisplayLength": 1000,//Por cada 10 registros hace una paginación
    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
      "language": { 
      "sProcessing":     "Procesando...",       
      "sLengthMenu":     "Mostrar _MENU_ registros",       
      "sZeroRecords":    "No se encontraron resultados",       
      "sEmptyTable":     "Ningún dato disponible en esta tabla",       
      "sInfo":           "Show  _START_ to _END_ from _TOTAL_ items",       
      "sInfoEmpty":      "Show from 0 TO 0 de un total de 0 registros",       
      "sInfoFiltered":   "(filtered from a total of _MAX_ records)",       
      "sInfoPostFix":    "",       
      "sSearch":         "Filter:",       
      "sUrl":            "",       
      "sInfoThousands":  ",",       
      "sLoadingRecords": "Loading...",       
      "oPaginate": {       
      "sFirst":"Primero","sLast":"Previous","sNext":"Next","sPrevious": "Previus"       
      },      
      "oAria": {       
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",       
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"       
      }
    }, //cerrando language
  });

}

function orders_processing(){

  table_env = $('#data_lenses_processing').DataTable({      
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax":{
      url:"../ajax/lens.php?op=get_orders_processing",
      type : "POST",
      dataType : "json",
      error: function(e){
      console.log(e.responseText);
    },},
    "bDestroy": true,
    "responsive": true,
    "bInfo":true,
    "iDisplayLength": 1000,//Por cada 10 registros hace una paginación
    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
      "language": { 
      "sProcessing":     "Procesando...",       
      "sLengthMenu":     "Mostrar _MENU_ registros",       
      "sZeroRecords":    "No se encontraron resultados",       
      "sEmptyTable":     "Ningún dato disponible en esta tabla",       
      "sInfo":           "Show  _START_ to _END_ from _TOTAL_ items",       
      "sInfoEmpty":      "Show from 0 TO 0 de un total de 0 registros",       
      "sInfoFiltered":   "(filtered from a total of _MAX_ records)",       
      "sInfoPostFix":    "",       
      "sSearch":         "Filter:",       
      "sUrl":            "",       
      "sInfoThousands":  ",",       
      "sLoadingRecords": "Loading...",       
      "oPaginate": {       
      "sFirst":"Primero","sLast":"Previous","sNext":"Next","sPrevious": "Previus"       
      },      
      "oAria": {       
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",       
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"       
      }
    }, //cerrando language
  });

}



var received_orders_codes =[];
var orders_received_download = [];
$(document).on('click', '#received_all', function(){
	let items_received = document.getElementsByClassName('received_item');
	let check_box = document.getElementById('received_all');
	let chk_status = check_box.checked;
	if(chk_status){
		for(i=0;i<items_received.length;i++){
		id_item = items_received[i].id;
		document.getElementById(id_item).checked = true;
		received_orders_codes.push(id_item)		
	   }
	}else{
		for(i=0;i<items_received.length;i++){
		id_item = items_received[i].id;
		document.getElementById(id_item).checked = false;		
	   }
	   received_orders_codes=[];
	   orders_received_download = [];
	}

	get_data_received_orders()
	
});
function get_data_received_orders(){
  for (let i in received_orders_codes){
  	  let code = received_orders_codes[i];
  	  
  	  $.ajax({
      url:"../ajax/lens.php?op=get_data_array_received",
      method:"POST",
      cache:false,
      data : {code:code},
      dataType:"json",
      success:function(data){
       let obj_excel={
       	id    : data.id_orden,
       	right : data.right,
       	left  : data.left,
       	lente : data.lente
       }
       orders_received_download.push(obj_excel);  
      }
    });

  }  
}

const EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
const EXCEL_EXTENSION = '.xlsx';

function downloadAsExcel() {
	var today = new Date();
	var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
	var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    const worksheet = XLSX.utils.json_to_sheet(orders_received_download);
    const workbook = {
        Sheets:{
            'data':worksheet
        },
        SheetNames:["data"]
    };
    const excelBuffer = XLSX.write(workbook,{bookType:'xlsx',type:'array'});
    saveAsExcel(excelBuffer,'Received Andres ES'+"__"+date+" "+time);
}

function saveAsExcel(buffer, filename){
    const data = new Blob([buffer],{type: EXCEL_TYPE});
    saveAs(data,filename+EXCEL_EXTENSION);
}

function received_confirm_v(){
	let cant_received = received_orders_codes.length;
	if (cant_received==0) {
		Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'Empty order. Add lens order!!',
        showConfirmButton: true,
        timer: 2500
      });
    return false;
	}
	$("#received_envio_ord").modal('show');
	$("#n_orders_received").html(cant_received);
}

function registerReceived(){
  $("#modal-overlay").modal('show');
  $.ajax({
    url:"../ajax/lens.php?op=registerReceived",
    method:"POST",
    data:{'arrayReceived':JSON.stringify(received_orders_codes)},
    cache: false,
    dataType:"json",

    success:function(data){
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Successfully received',
        showConfirmButton: true,
        timer: 2500
      });
    $("#modal-overlay").modal('hide');
    $("#data_lenses_received").DataTable().ajax.reload();
    document.getElementById("download_received").style.display = "block";
    document.getElementById("btns_received").style.display = "none";
    }
  });//fin ajax
	
}

var orders_send = [];
$(document).on('click', '.send_item', function(){

  let id_orden = $(this).attr("value");
  let paciente = $(this).attr("name");
  let id_item = $(this).attr("id");

  console.log(`${id_orden} paciente ${paciente} ${id_item}`);
  let checkbox = document.getElementById(id_item);
  console.log(checkbox);

  let check_state = checkbox.checked;

  if (check_state) {
    let obj = {
      id_orden : id_orden,
      paciente : paciente,
      id_item  : id_item
    }
    orders_send.push(obj);
  }else{
    let indice = orders_send.findIndex((objeto, indice, orders_send) =>{
      return objeto.id_orden == id_orden
    });
    orders_send.splice(indice,1)
  }
  console.log(orders_send)
});

function send_confirm_v(){
  let cant_send = orders_send.length;

  if (cant_send==0) {
    Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'Not send. Empty order. Add lens!!',
        showConfirmButton: true,
        timer: 2500
      });
    return false;
  }
  $("#send_envio_ord").modal('show');
  $("#n_orders_send").html(cant_send);
}

function registerSending(){
  $('#send_envio_ord').modal('hide');

  $("#mess_overlay").html("Enviando ordenes...");
  $('#modal-overlay-w').modal('show');
  

}

begin();