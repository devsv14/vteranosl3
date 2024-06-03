$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

let calendarEl = document.getElementById('calendar');
let frm = document.getElementById('formulario');
let eliminar = document.getElementById('btnEliminar');
let myModal = new bootstrap.Modal(document.getElementById('myModal'));
//let sucursal = document.getElementById("sucs").value;
document.addEventListener('DOMContentLoaded', function () {
    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev next today',
            center: 'title',
            right: 'dayGridMonth timeGridWeek listWeek'
        },
        events: base_url + 'Home/listar',
       
        //events: base_url + 'Home/listar?filtro='+ sucursal,
        editable: true,
        dateClick: function (info) {

            frm.reset();
                console.log(info.date)
                let hoy = new Date();
                hoy.setHours(0,0,0,0);
              
                if (info.date >= hoy) {
                  $("#input-ed").val("0")
                  $("#hora").empty();
                  document.getElementById('datos-titular').style.display = "none";
                  document.getElementById("paciente-vet").readOnly = false;
                  document.getElementById("dui-vet").readOnly = false;
                  document.getElementById("telefono-pac").readOnly = false;
                  document.getElementById("edad-pac").readOnly = false;
                  document.getElementById("ocupacion-pac").readOnly = false;
                  document.getElementById("genero-pac").disabled = false;
                  document.getElementById("sector-pac").disabled = false;
                  document.getElementById("munic_pac").disabled = false;
                  document.getElementById("departamento_pac").disabled = false;
                    document.getElementById("btnEdit").style.display="none";
                    document.getElementById("btnAccion").style.display="block";
                    document.getElementById('start').value = info.dateStr;
                    document.getElementById('id').value = '';
                    document.getElementById('btnAccion').textContent = 'Registrar';
                    myModal.show();
                    document.getElementById("fecha-cita").value=info.dateStr;
                    document.getElementById('titulo').textContent = 'Registrar Cita';
                    $('#munic_pac').val('1'); // Select the option with a value of '1'
                    $('#munic_pac').trigger('change');
                    $('#departamento_pac').val('1'); // Select the option with a value of '1'
                    $('#departamento_pac').trigger('change');
                    getDisponibilidadSucursales(info.dateStr);
                    
                } else {
                    Swal.fire(
                        'Fecha invalida!!',
                        'Fecha menor que hoy',
                        'warning'
                    )
                }               
                      
        },

        eventClick: function (info) {
            let sucursal = info.event.title;
            let fecha = info.event.startStr;
            
            document.getElementById('id').value = info.event.id;
            document.getElementById('start').value = info.event.startStr;
            document.getElementById('btnAccion').textContent = 'Modificar';
            document.getElementById('titulo').textContent = 'Actualizar Evento';

            getCitadosSucursal(fecha);
        },
    }); 

    calendar.render();
    frm.addEventListener('submit', function (e) {
        e.preventDefault();

        let paciente = document.getElementById('paciente-vet').value;
        let dui = document.getElementById('dui-vet').value;
        let telefono = document.getElementById('telefono-pac').value;
        let fecha = document.getElementById('fecha-cita').value;
        let hora = document.getElementById('hora').value;
        let sucursal = document.getElementById('sucursal-cita').value;
        let sector = document.getElementById("sector-pac").value;
        let depto = document.getElementById("sector-pac").value;
        let municipio = document.getElementById("sector-pac").value;
        let vet_titular = document.getElementById("vet-titular").value;
        let duitit  = document.getElementById("dui-titular").value;
        let tipo_paciente = document.getElementById("tipo-pac").value;
        let institucion = $("input[type='radio'][name='chk-instit']:checked").val();
        let asterisk = paciente.match(/\*/g);
        let licitacion = document.getElementById('licitacion').value;
        if(institucion=='inabve' && asterisk != '*'){
          Swal.fire('Notificaciones!!','Formato de titular invalido para consultar SIVET WEB','warning'
         )
         return false
        }
        
        if(sector=="CONYUGE" && (vet_titular =="" || duitit=="")){
          Swal.fire(                
            'Notificaciones!!',                
            'DUI y nombres de titular son obligatorios',
            'warning'
         )
         return false
        }

        if (paciente == '' || dui == '' || fecha == '' || sucursal=="0" || sector=="0" || depto=="" || municipio=='0' || telefono=='' || hora=="" || tipo_paciente=='0' || licitacion=='0') {
             Swal.fire(                
                'Notificaciones!!',                
                'Existen campos obligatorios vacios',
                'warning'
             )
        } else {
            const url = base_url + 'Home/registrar';
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);

                     Swal.fire(
                         'Notificacion',
                         res.msg,
                         res.tipo
                     )
                    if (res.estado) {
                        myModal.hide();
                        calendar.refetchEvents();
                        document.getElementById('licitacion').value='0';
                    }
                }
            }
            
        }//hasta aqui
    });
    eliminar.addEventListener('click', function () {
        myModal.hide();
        Swal.fire({
            title: 'Advertencia?',
            text: "Esta seguro de eliminar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = base_url + 'Home/eliminar/' + document.getElementById('id').value;
                const http = new XMLHttpRequest();
                http.open("GET", url, true);
                http.send();
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        Swal.fire(
                            'Avisos?',
                            res.msg,
                            res.tipo
                        )
                        if (res.estado) {
                            calendar.refetchEvents();
                        }
                    }
                }
            }
        })
    });
});


////////////////get cambio departamento ///////////////
 
$(document).ready(function(){
    $("#departamento_pac").change(function () {         
      $("#departamento_pac option:selected").each(function () {
       let depto = $(this).val();
         
         get_municipios(depto);       
                   
      });
    })
  });
  
  /***************MUNICIPIOS ***************/
  
    var ahuachapan=["Ahuachapán","Apaneca","Atiquizaya","Concepción de Ataco","El Refugio","Guaymango","Jujutla","San Francisco Menéndez","San Lorenzo","San Pedro Puxtla","Tacuba","Turín"];
    var cabanas=["Cinquera","Dolores (Villa Doleres)","Guacotecti","Ilobasco","Jutiapa","San Isidro","Sensuntepeque","Tejutepeque","Victoria"];
    var chalatenango=["Agua Caliente","Arcatao","Azacualpa","Chalatenango","Citalá","Comalapa","Concepción Quezaltepeque","Dulce Nombre de María","El Carrizal","El Paraíso","La Laguna","La Palma","La Reina","Las Vueltas","Nombre de Jesús","Nueva Concepción","Nueva Trinidad","Ojos de Agua","Potonico","San Antonio de la Cruz","San Antonio Los Ranchos","San Fernando","San Francisco Lempa","San Francisco Morazán","San Ignacio","San Isidro Labrador","San José Cancasque (Cancasque)","San José Las Flores","San Luis del Carmen","San Miguel de Mercedes","San Rafael","Santa Rita","Tejutla"];
    var cuscatlan=["Candelaria","Cojutepeque","El Carmen","El Rosario","Monte San Juan","Oratorio de Concepción","San Bartolomé Perulapía","San Cristóbal","San José Guayabal","San Pedro Perulapán","San Rafael Cedros","San Ramón","Santa Cruz Analquito","Santa Cruz Michapa","Suchitoto","Tenancingo"];
    var morazan=["Arambala","Cacaopera","Chilanga","Corinto","Delicias de Concepción","El Divisadero","El Rosario","Gualococti","Guatajiagua","Joateca","Jocoaitique","Jocoro","Lolotiquillo","Meanguera","Osicala","Perquín","San Carlos","San Fernando","San Francisco Gotera","San Isidro","San Simón","Sensembra","Sociedad","Torola","Yamabal","Yoloaiquín"];
    var lalibertad=["Antiguo Cuscatlán","Chiltiupán","Ciudad Arce","Colón","Comasagua","Huizúcar","Jayaque","Jicalapa","La Libertad","Santa Tecla (Nueva San Salvador)","Nuevo Cuscatlán","San Juan Opico","Quezaltepeque","Sacacoyo","San José Villanueva","San Matías","San Pablo Tacachico","Talnique","Tamanique","Teotepeque","Tepecoyo","Zaragoza"];
    var lapaz=["Cuyultitán","El Rosario (Rosario de La Paz)","Jerusalén","Mercedes La Ceiba","Olocuilta","Paraíso de Osorio","San Antonio Masahuat","San Emigdio","San Francisco Chinameca","San Juan Nonualco","San Juan Talpa","San Juan Tepezontes","San Luis La Herradura","San Luis Talpa","San Miguel Tepezontes","San Pedro Masahuat","San Pedro Nonualco","San Rafael Obrajuelo","Santa María Ostuma","Santiago Nonualco","Tapalhuaca","Zacatecoluca"];
    var launion=["Anamorós","Bolívar","Concepción de Oriente","Conchagua","El Carmen","El Sauce","Intipucá","La Unión","Lilisque","Meanguera del Golfo","Nueva Esparta","Pasaquina","Polorós","San Alejo","San José","Santa Rosa de Lima","Yayantique","Yucuaiquín"];
    var sanmiguel=["Carolina","Chapeltique","Chinameca","Chirilagua","Ciudad Barrios","Comacarán","El Tránsito","Lolotique","Moncagua","Nueva Guadalupe","Nuevo Edén de San Juan","Quelepa","San Antonio del Mosco","San Gerardo","San Jorge","San Luis de la Reina","San Miguel","San Rafael Oriente","Sesori","Uluazapa"];
    var sansalvador=["Aguilares","Apopa","Ayutuxtepeque","Ciudad Delgado","Cuscatancingo","El Paisnal","Guazapa","Ilopango","Mejicanos","Nejapa","Panchimalco","Rosario de Mora","San Marcos","San Martín","San Salvador","Santiago Texacuangos","Santo Tomás","Soyapango","Tonacatepeque"];
    var sanvicente=["Apastepeque","Guadalupe","San Cayetano Istepeque","San Esteban Catarina","San Ildefonso","San Lorenzo","San Sebastián","San Vicente","Santa Clara","Santo Domingo","Tecoluca","Tepetitán","Verapaz"];
    var santaana=["Candelaria de la Frontera","Chalchuapa","Coatepeque","El Congo","El Porvenir","Masahuat","Metapán","San Antonio Pajonal","San Sebastián Salitrillo","Santa Ana","Santa Rosa Guachipilín","Santiago de la Frontera","Texistepeque"];
    var sonsonate=["Acajutla","Armenia","Caluco","Cuisnahuat","Izalco","Juayúa","Nahuizalco","Nahulingo","Salcoatitán","San Antonio del Monte","San Julián","Santa Catarina Masahuat","Santa Isabel Ishuatán","Santo Domingo de Guzmán","Sonsonate","Sonzacate"];
    var usulutan=["Alegría","Berlín","California","Concepción Batres","El Triunfo","Ereguayquín","Estanzuelas","Jiquilisco","Jucuapa","Jucuarán","Mercedes Umaña","Nueva Granada","Ozatlán","Puerto El Triunfo","San Agustín","San Buenaventura","San Dionisio","San Francisco Javier","Santa Elena","Santa María","Santiago de María","Tecapán","Usulután"];
  function get_municipios(depto){
   $("#munic_pac").empty();
   if (depto=="San Salvador") {
    $("#munic_pac").select2({ data: sansalvador})
   }else if (depto=="La Libertad") {
    $("#munic_pac").select2({ data: lalibertad})
   }else if (depto=="Santa Ana") {
     $("#munic_pac").select2({ data: santaana})
   }else if (depto=="San Miguel") {
      $("#munic_pac").select2({ data: sanmiguel})
   }else if (depto=="Sonsonate") {
      $("#munic_pac").select2({ data: sonsonate})
   }else if (depto=="Usulutan") {
      $("#munic_pac").select2({ data: usulutan})
   }else if (depto=="Ahuachapan") {
      $("#munic_pac").select2({ data: ahuachapan})
   }else if (depto=="La Union") {
      $("#munic_pac").select2({ data: launion})
   }else if (depto=="La Paz") {
      $("#munic_pac").select2({ data: lapaz})
   }else if (depto=="Chalatenango") {
      $("#munic_pac").select2({ data: chalatenango})
   }else if (depto=="Cuscatlan") {
      $("#munic_pac").select2({ data: cuscatlan})
   }else if (depto=="Morazan") {
      $("#munic_pac").select2({ data: morazan})
   }else if (depto=="San Vicente") {
      $("#munic_pac").select2({ data: sanvicente})
   }else if (depto=="Cabanas") {
     $("#munic_pac").select2({ data: cabanas})
   }
  
  }

$(function () {
    //Initialize Select2 Elements
    $('#departamento_pac').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $("#departamento_pac").select2({
    maximumSelectionLength: 1
    });
    
    $('#munic_pac').select2()
    $("#munic_pac").select2({
    maximumSelectionLength: 1
    });

    $('#hora').select2()
    $("#hora").select2({
    maximumSelectionLength: 1
    });
  })



  function  getCitadosSucursal(fecha){
    $("#listarCitas").modal()
    tabla = $('#datatable_citas_suc').DataTable({      
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'frtip',//Definimos los elementos del control de tabla
      buttons: [     
        'excelHtml5',
      ],
  
      "ajax":{
        url:"../ajax/citados.php?op=get_citados_sucursal",
        type : "POST",
        data: {fecha:fecha},
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

function gethorasDisponiblesSucursal(sucursal){
  let fecha = document.getElementById("fecha-cita").value;
  gethorasDisponibles(sucursal,fecha)
}

function gethorasDisponiblesFecha(fecha){
  let sucursal = document.getElementById("sucursal-cita").value;
  gethorasDisponibles(sucursal,fecha)
}


function gethorasDisponibles(sucursal,fecha){
  console.log(sucursal,fecha);
  
  const fecha_act = new Date(fecha);
  const ini_sm  = new Date('2023-03-15');
  
  const numeroDia = new Date(fecha).getDay();
  const dias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
  const nombreDia = dias[numeroDia];
  let dateNotValid = ['2023-04-01', '2023-04-02', '2023-04-03', '2023-04-04', '2023-04-05', '2023-04-06', '2023-04-07', '2023-04-08', '2023-04-09', '2023-04-10', '2023-04-11', '2023-04-12', '2023-04-13', '2023-04-14', '2023-04-15'];
  let disp = []
  if(sucursal=="Metrocentro"){
    if(nombreDia != "sabado"){
      disp = ['9:00:00 AM','9:10:00 AM','9:20:00 AM','9:30:00 AM','9:40:00 AM','9:50:00 AM','10:00:00 AM','10:10:00 AM','10:20:00 AM','10:30:00 AM','10:40:00 AM','10:50:00 AM','11:00:00 AM','11:10:00 AM','11:20:00 AM','11:30:00 AM','11:40:00 AM','11:50:00 AM','12:00:00 PM','12:30:00 PM','12:40:00 PM','12:50:00 PM','1:00:00 PM','1:10:00 PM','1:20:00 PM'];
    }else{
      disp = ['9:10:00 AM','9:20:00 AM','9:30:00 AM','9:40:00 AM','9:50:00 AM','10:00:00 AM','10:10:00 AM','10:20:00 AM','10:30:00 AM','10:40:00 AM'];
    }

  }else if(sucursal=="Sonsonate"){
      disp =[
        '8:00:00 AM','8:25:00 AM','8:50:00 AM','9:15:00 AM','9:40:00 AM','1:00 PM','1:20 PM','1:40 PM','2:00 PM','2:20 PM'
      ]
  }else if(sucursal=="San Vicente" || sucursal=="San Vicente Centro"){
    disp = ['8:00:00 AM','8:30:00 AM','9:00:00 AM','9:30:00 AM','10:00:00 AM','10:30:00 AM','11:00:00 AM','11:30:00 AM']
  }else if(sucursal=="0"){
    disp=[]
  }else if(sucursal=="San Miguel"){
   let result = dateNotValid.includes(fecha);
    if(result==false){
        disp =['9:30:00 AM','10:00:00 AM','10:30:00 AM','11:00:00 AM','1:00:00 PM','1:30:00 PM','2:00:00 PM','2:30:00 PM'];
    }
  }else if(sucursal=="Usulutan"){
   let result = dateNotValid.includes(fecha);
    if(result==false){
      disp =['8:00:00 AM','9:00:00 AM','10:00:00 AM','11:00:00 AM','1:00:00 PM','1:00:00 PM','2:00:00 PM','3:00:00 PM','4:00:00 PM'];
    } 
  }else if(sucursal=="Valencia"){
    if(fecha=='2023-01-25'){
      disp =[        '8:00 AM','8:02 AM','8:04 AM','8:06 AM','8:08 AM','8:10 AM','8:12 AM','8:14 AM','8:16 AM','8:18 AM','8:20 AM','8:22 AM','8:24 AM','8:26 AM','8:28 AM','8:30 AM','8:32 AM','8:34 AM','8:36 AM','8:38 AM','8:40 AM','8:42 AM','8:44 AM','8:46 AM','8:48 AM','8:50 AM','8:52 AM','8:54 AM','8:56 AM','8:58 AM','9:00 AM','9:02 AM','9:04 AM','9:06 AM','9:08 AM','9:10 AM','9:12 AM','9:14 AM','9:16 AM','9:18 AM','9:20 AM','9:22 AM','9:24 AM','9:26 AM','9:28 AM','9:30 AM','9:32 AM','9:34 AM','9:36 AM','9:38 AM','9:40 AM','9:42 AM','9:44 AM','9:46 AM','9:48 AM','9:50 AM','9:52 AM','9:54 AM','9:56 AM','9:58 AM','10:00 AM','10:02 AM','10:04 AM','10:06 AM','10:08 AM','10:10 AM','10:12 AM','10:14 AM','10:16 AM','10:18 AM','10:20 AM','10:22 AM','10:24 AM','10:26 AM','10:28 AM','10:30','10:35','10:40','10:45','10:50','10:55','11:00','11:05','11:10','11:15','11:20','11:25','11:30','11:35','11:40','11:45','11:50','11:55','1:00 PM','1:05 PM','1:10 PM','1:15 PM','1:20 PM','1:25 PM','1:30 PM','1:35 PM','1:40 PM','1:45 PM','1:50 PM','1:55 PM','2:00 PM','2:03 PM','2:06 PM','2:09 PM','2:12 PM','2:15 PM','2:18 PM','2:21 PM','2:24 PM','2:27 PM','2:30 PM','2:33 PM','2:36 PM','2:39 PM','2:42 PM','2:45 PM','2:48 PM','2:51 PM','2:54 PM','2:57 PM','3:00 PM','3:03 PM','3:06 PM','3:09 PM','3:12 PM','3:15 PM','3:18 PM','3:21 PM','3:24 PM','3:27 PM','3:30 PM','3:33 PM','3:36 PM','3:39 PM','3:42 PM','3:45 PM','3:48 PM'
      ]
    }else{

     disp = ['8:00','8:05','8:10','8:15','8:20','8:25','8:30','8:35','8:40','8:45','8:50','8:55','9:00','9:05','9:10','9:15','9:20','9:25','9:30','9:35','9:40','9:45','9:50','9:55','10:00','10:05','10:10','10:15','10:20','10:25','10:30','10:35','10:40','10:45','10:50','10:55','11:00','11:05','11:10','11:15','11:20','11:25','11:30','11:35','11:40','11:45','11:50','11:55','1:00 PM','1:05 PM','1:10 PM','1:15 PM','1:20 PM','1:25 PM','1:30 PM','1:35 PM','1:40 PM','1:45 PM','1:50 PM','1:55 PM','2:00 PM','2:05 PM','2:10 PM','2:15 PM','2:20 PM','2:25 PM','2:30 PM','2:35 PM','2:40 PM','2:45 PM','2:50 PM','2:55 PM','3:00 PM','3:05 PM','3:10 PM','3:15 PM','3:20 PM','3:25 PM','3:30 PM','3:35 PM','3:40 PM','3:45 PM','3:50 PM','3:55 PM','4:00 PM'
      ]
    }
  }else if(sucursal=="Santa Ana"){
    if(nombreDia != "sabado"){
    disp = [
      '8:00:AM','8:10:AM','8:20:AM','8:30:AM','8:40:AM','8:50:AM','9:00:AM','9:10:AM','9:20:AM','9:30:AM','9:40:AM','9:50:AM','10:00:AM','10:10:AM','10:20:AM','10:30:AM','10:40:AM','10:50:AM','11:00:AM','11:10:AM','11:20:AM','11:30:AM','11:40:AM','1:00 PM','1:10 PM'
    ]
  }else{
    disp = [
      '8:00:00 AM','8:15:00 AM','8:30:00 AM','8:45:00 AM','9:00:00 AM','9:15:00 AM','9:30:00 AM','9:45:00 AM','10:00:00 AM','10:15:00 AM'
    ]
  }
  }else if(sucursal=="Cascadas"){
    if(nombreDia != "sabado"){
    disp = [
      '9:00:AM','9:10:AM','9:20:AM','9:30:AM','9:40:AM','9:50:AM','10:00:AM','10:10:AM','10:20:AM','10:30:AM','10:40:AM','10:50:AM','11:00:AM','11:10:AM','11:20:AM','11:30:AM','11:40:AM','1:00 PM','1:10 PM','1:20:PM','1:30 PM','1:40:PM','1:50 PM','2:00:PM','2:10 PM'
    ]
  }else{
    disp = [
     '9:00:00 AM','9:15:00 AM','9:30:00 AM','9:45:00 AM','10:00:00 AM','10:15:00 AM','10:30:00 AM','11:00:00 AM','11:30:00 AM','12:00:00 MD',
    ]
  }
  }else if(sucursal=="Apopa"){
    if(nombreDia != "sabado"){
    disp = [
      '8:50:00 AM','9:00:00 AM','9:10:00 AM','9:20:00 AM','9:30:00 AM','9:40:00 AM','9:50:00 AM','10:00:00 AM','10:10:00 AM','10:20:00 AM','10:30:00 AM','10:40:00 AM','10:50:00 AM','11:00:00 AM','11:10:00 AM','11:20:00 AM','11:30:00 AM','11:40:00 AM','11:50:00 AM','12:00:00 PM','12:10:00 PM','12:20:00 PM','12:30:00 PM'
   ]
  }
 }else if(sucursal=="San Miguel AV PLUS"){

    if(nombreDia == "miercoles"){
         disp= [];
    
    }else if(nombreDia == "martes"){
        disp = ['8:00:00 AM','8:15:00 AM','8:30:00 AM','8:45:00 AM','9:00:00 AM','9:15:00 AM','9:30:00 AM','9:45:00 AM','10:00:00 AM','10:15:00 AM','10:30:00 AM','10:45:00 AM','11:00:00 AM','11:15:00 AM','11:30:00 AM']
    }else{
        disp=['9:00:00 AM','9:10:00 AM','9:20:00 AM','9:30:00 AM','9:40:00 AM','9:50:00 AM','10:00:00 AM','10:10:00 AM','10:20:00 AM','10:30:00 AM','10:40:00 AM','10:50:00 AM','11:00:00 AM','11:10:00 AM','11:20:00 AM','11:30:00 AM','11:40:00 AM','11:50:00 AM','12:00:00 PM','12:30:00 PM', '12:40:00 PM', '12:50:00 PM', '1:00:00 PM', '1:10:00 PM', '1:20:00 PM']
    }
    
 ////Fin san Miguel Av Plus
 }else{
    if(nombreDia != "sabado"){
    disp = [
      '8:00:00 AM','8:10:00 AM','8:20:00 AM','8:30:00 AM','8:40:00 AM','8:50:00 AM','9:00:00 AM','9:10:00 AM','9:20:00 AM','9:30:00 AM','9:40:00 AM','9:50:00 AM','10:00:00 AM','10:10:00 AM','10:20:00 AM','10:30:00 AM','10:40:00 AM','10:50:00 AM','11:00:00 AM','11:10:00 AM','11:20:00 AM','11:30:00 AM','11:40:00 AM','11:50:00 AM','12:00:00 PM'
    ]
  }else{
    disp = [
      '8:00:00 AM','8:15:00 AM','8:30:00 AM','8:45:00 AM','9:00:00 AM','9:15:00 AM','9:30:00 AM','9:45:00 AM','10:00:00 AM','10:15:00 AM'
    ]
  }
  }
   
  $.ajax({
        url:"../ajax/citados.php?op=get_horas_select",
        method:"POST",
        data:{fecha:fecha,sucursal:sucursal},
        cache: false,
        dataType:"json",
        success:function(horas){
          console.log(horas)
          let tam_array = horas.length;
          if(tam_array==0){
            $("#hora").empty();
            $("#hora").select2({ data: disp})
          }else{
            let diff = disp.filter(d => !horas.includes(d));
            $("#hora").empty();
            $("#hora").select2({ data: diff})
          }
          
          
        }
      });///fin ajax 
}

$(".inp-citas").keyup(function(){
  $(this).val($(this).val().toUpperCase());
});



/////////////GET DATA API//////////////
document.querySelectorAll(".chk-ins").forEach(i => i.addEventListener("click", e => {
  let institucion = $("input[type='radio'][name='chk-instit']:checked").val();
  console.log(institucion)
  if(institucion=='brf'){
    clearInputs()
    document.getElementById("tipo-pac").value = "BRF";
    document.getElementById("sector-pac").value = "BRF";
    document.getElementById("beneficiarios-vet").value = "";
    //$('#tipo-pac').prop('disabled', true);
    
    $("#tipo-pac").attr("readonly", true);
    $("#sector-pac").attr("readonly", true);
    $('#beneficiarios-vet').prop('disabled', true);
    document.getElementById("datos-titular").style.display = "none";
    document.getElementById("vet-titular").value='';
    document.getElementById("dui-titular").value='';
    document.getElementById("paciente-vet").value=''
  }else{
    $('#tipo-pac').attr('readonly', false);
    $('#sector-pac').attr('readonly', false);
    document.getElementById("tipo-pac").value = "0";
    document.getElementById("sector-pac").value = "0";
  }
}));



$(document).ready(function(){
  $("#tipo-pac").change(function () {         
    $("#tipo-pac option:selected").each(function () {
      let titular = document.getElementById("paciente-vet").value;
      let tam_titular = titular.length;
      if(tam_titular<18){
        Swal.fire({position: 'top-center',icon: 'error',title: 'Formato de titular invalido',showConfirmButton: true,
        timer: 2500}); return 0;
      }
      if(titular==''){
        Swal.fire({position: 'top-center',icon: 'error',title: 'Seleccionar Titular',showConfirmButton: true,
        timer: 1500
      }); return 0;
      }
      //clearInputs()
      let sector = $(this).val();
      let institucion = $("input[type='radio'][name='chk-instit']:checked").val();    
      if(institucion=='brf'){
        Swal.fire({position: 'top-center',icon: 'error',title: 'Opciones deshabilitadas por el momento',showConfirmButton: true,
        timer: 1500
      });
      document.getElementById('tipo-pac').value='BRF'
      return false;
      }
      let dui_titular  = document.getElementById("paciente-vet").value;
      const valores = dui_titular.split("*");
      let dui = valores[0];
      if(sector=="Designado" || sector=="Conyuge"){ 
      if(sector=='Designado'){
          document.getElementById("loader").style.display = "block"; 
          document.getElementById("beneficiarios-vet").disabled = true; 
       }
        getBeneficiarios(dui,'beneficiario',sector)
      }else if(sector=='BRF'){
        console.log('N/A')
      }else{
        document.getElementById("loader").style.display = "block";
        $('#beneficiarios-vet').prop('disabled', true);
        $('#beneficiarios-vet').val('');
        getBeneficiarios(dui,'titular',sector)
      }                      
    });
  })
});

const input_pac = document.getElementById("paciente-vet");

input_pac.onpaste = function() {
  let institucion = $("input[type='radio'][name='chk-instit']:checked").val();
  let paciente = document.getElementById("paciente-vet");

  if(institucion=='inabve'){
    buscarPaciente(paciente)
  }

};

function buscarPaciente(paciente){
  console.log(paciente.length % 4)
  if(paciente.length >= 0){
    //document.getElementById("paciente-vet").readOnly = true;    
    let institucion = $("input[type='radio'][name='chk-instit']:checked").val();
    if(institucion==undefined){
      Swal.fire({position: 'top-center',icon: 'error',title: 'Seleccionar institucion INABVE/BRF',showConfirmButton: true,
        timer: 1500
    });
      return false;
    }
    console.log(institucion)
    if(institucion=='inabve'){
      getDataPaciente(paciente)
    }
  }else{
    document.getElementById("paciente-vet").value="";
    document.getElementById("tipo_pac").value="0"
  }
  
}

var datalist = document.getElementById('options-pacientes');
function getDataPaciente(paciente){
  fetch('https://apis.inabve.sv/v1/api_sivetweb/registros/veteranos/info',{
  method: 'POST',
  mode: 'no-cors',
  body: JSON.stringify({
    "busqueda": paciente
  }),
  headers: {
    'Content-type': 'Application/json'
  }
})
.then(response => response.json())
.then(data => {
    console.log(data)
  var elements = data.data;
    datalist.innerHTML = "";
    elements.forEach(option => {
      const optionElement = document.createElement('option');
      optionElement.value = option.dui+ '*' +option.nombres+' ' +option.apellidos+"-("+option.sector+")";
      datalist.appendChild(optionElement);
    });
 
});
}

var datalistben = document.getElementById('options-beneficiarios');
var data_beneficiarios = [];
var sector_act = '';

function getBeneficiarios(dui,parametro,sector){
document.getElementById('beneficiarios-vet').value='';
const data = { "busqueda": dui };

 fetch('https://apis.inabve.sv/v1/api_sivetweb/registros/veteranos/info', {
   method: 'POST', // or 'PUT',
   headers: {
     'Content-Type': 'application/json',
   },
   body: JSON.stringify(data),
 })
   .then((response) => response.json())
   .then((data) => {
     let datos = data.data;
     
     console.log(datos)
   
     let beneficiarios = datos[0].grupoFamiliar;
     console.log(beneficiarios,datos)

      if(parametro=='beneficiario'){

        if(beneficiarios.length==0 && sector=='Designado'){
         // document.getElementById('tipo-pac').value = "0";
          document.getElementById('beneficiarios-vet').placeholder='Este paciente no posee beneficiarios';
          $('#beneficiarios-vet').prop('disabled', true);
          document.getElementById("loader").style.display = "none";
        }else{
        document.getElementById("sector-pac").value=datos[0].sector;
        data_beneficiarios = [];
        sector_act = datos[0].sector;
        document.getElementById("datos-titular").style.display = "flex";
        document.getElementById("vet-titular").value=datos[0].nombres+" "+datos[0].apellidos;
        document.getElementById("dui-titular").value=datos[0].dui;
        data_beneficiarios.push(beneficiarios); console.log(beneficiarios);
        datalistben.innerHTML = "";
        beneficiarios.forEach((option, indice) => {
        const optionElement = document.createElement('option');
        optionElement.value = parseInt(indice)+1 + '-' +option.nombres+' ' +option.apellidos +'-('+option.parentesto+')';
        datalistben.appendChild(optionElement);
        document.getElementById("loader").style.display = "none";
      });      
      document.getElementById("beneficiarios-vet").disabled = false;
     }
     }else{
      //PACIENTES TITULARES
      document.getElementById("datos-titular").style.display = "none";
      document.getElementById("vet-titular").value='';
      document.getElementById("dui-titular").value='';
      document.getElementById("dui-vet").value=datos[0].dui;
      document.getElementById("telefono-pac").value=datos[0].celular1;
      document.getElementById("telefono-opcional").value=datos[0].celular2;
      document.getElementById("edad-pac").value=datos[0].edad;
      document.getElementById("genero-pac").value=datos[0].genero;
      document.getElementById("sector-pac").value=datos[0].sector;
      document.getElementById("loader").style.display = "none";
     }
     
   })
   .catch((error) => {
     console.error('Error:', error);
   });
}


function getDataBeneficiarios(beneficiarios){
 
  let array_beneficiario = beneficiarios.split('-'); 
  const indice = parseInt(array_beneficiario[0]-1);
  const lista_beneficiarios = data_beneficiarios[0];
  const beneficiario_selected = lista_beneficiarios[indice];
  if (beneficiarios.includes('-')) {  
  document.getElementById("loader").style.display = "block";
  if(beneficiario_selected.dui==''){
      let aleatorio = Math.floor(Math.random() * (99000 - 30000 + 1)) + 30000;
      let dui_m =  beneficiario_selected.idVeterano+aleatorio+'-M';
      document.getElementById("dui-vet").value=dui_m;
  }else{
    document.getElementById("dui-vet").value=beneficiario_selected.dui;
  }
  
  document.getElementById("telefono-pac").value=beneficiario_selected.celular1;
  document.getElementById("telefono-opcional").value=beneficiario_selected.celular2;
  document.getElementById("edad-pac").value=beneficiario_selected.edad;
  if(beneficiario_selected.sexo=='M'){
    document.getElementById("genero-pac").value="Masculino";
  }else{
    document.getElementById("genero-pac").value="Femenino";
  }
  //document.getElementById("sector-pac").value=sector_act;
  document.getElementById("loader").style.display = "none";  
  }
}

const input = document.querySelector("#paciente-vet");
input.addEventListener("input", function(){

   let tipo_paciente = document.getElementById("tipo-pac").value;
   let dui = document.getElementById("dui-vet").value;
   
   document.getElementById("tipo-pac").value='0'
   if(input=='' && (tipo_paciente !='' || dui !='')){
    clearInputs()
   }
});


function clearInputs(){
  let element = document.getElementsByClassName("i-citas");  
  for(i=0;i<element.length;i++){
    let id_element = element[i].id;
    document.getElementById(id_element).value = "";
 }
}