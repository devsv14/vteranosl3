function init() {
  // document.getElementById("enviar_a").style.display = "none";
  listar_ordenes_digitadas('0');
  listar_ordenes_enviar();
  get_numero_orden();
  get_ordenes_env('0');
  get_ordenes_env_lab('0');
  get_ordenes_procesando();
  get_ordenes_por_enviar();
  listar_rectificaciones();
  document.getElementById("btn-print-bc").style.display = "none";
}
//Show datos table REPORTE LENTES
get_reporte_lentes()
get_reporte_lentes_resumen()

function ocultar_btn_print_rec_ini() {
  document.getElementById("btn_print_recibos").style.display = "none";
}
function estado_btn_edit() {
  const btnEditOrden = document.getElementById('order_create_edit');
  btnEditOrden.innerHTML = '<i class="fas fa-save"></i> Guardar cambios'
}
function estado_btn_save() {
  const btnEditOrden = document.getElementById('order_create_edit');
  btnEditOrden.innerHTML = '<i class="fas fa-save"></i> Guardar'
}
/////////////// SELECCIONAR SUCURSAL //////////
$(document).ready(function () {
  $("#optica_orden").change(function () {
    $("#optica_orden option:selected").each(function () {
      let optica = $(this).val();
      $.post('../ajax/ordenes.php?op=sucursales_optica', { optica: optica }, function (data) {
        $("#optica_sucursal").html(data);
      });
    });
  })
});

/////////validar ingreso de adicion////////////
function valida_adicion() {
  let vs_check = $("#lentevs").is(":checked");
  if (vs_check == true) {
    document.getElementById('oddicionf_orden').readOnly = true;
    document.getElementById('oiadicionf_orden').readOnly = true;
    document.getElementById('oddicionf_orden').value = "";
    document.getElementById('oiadicionf_orden').value = "";
  } else {
    document.getElementById('oddicionf_orden').readOnly = false;
    document.getElementById('oiadicionf_orden').readOnly = false;
  }

  let lentebf_chk = $("#lentebf").is(":checked");

  if (lentebf_chk == true) {
    document.getElementById('ap_od').readOnly = true;
    document.getElementById('ap_oi').readOnly = true;
  } else {
    document.getElementById('ap_od').readOnly = false;
    document.getElementById('ap_oi').readOnly = false;
  }

  let lentemulti_chk = $("#lentemulti").is(":checked");

  if (lentemulti_chk == true) {
    document.getElementById('ao_od').readOnly = true;
    document.getElementById('ao_oi').readOnly = true;
  } else {
    document.getElementById('ao_od').readOnly = false;
    document.getElementById('ao_oi').readOnly = false;
  }
}

function status_checks_tratamientos() {

  let photocrom_check = $('#photocromphoto').is(":checked");

  if (photocrom_check) {

    $("#transitionphoto").attr("disabled", true);

    $('#lbl_arsh').css('color', 'green');

    $("#arbluecap").attr("disabled", true);
    $('#arbluecap').prop('checked', false)
    $('#lbl_arbluecap').css('color', '#989898');

    $("#arnouv").attr("disabled", true);
    $('#arnouv').prop('checked', false)
    $('#lbl_arnouv').css('color', '#989898');

    $("#blanco").attr("disabled", true);
    $('#blanco').prop('checked', false)
    $('#lbl_blanco').css('color', '#989898');

    $("#transitionphoto").attr("disabled", true);
    $('#transitionphoto').prop('checked', false)
    $('#lbl_transitionphoto').css('color', '#989898');

  } else {
    $("#transitionphoto").removeAttr("disabled");
  }

}

function create_barcode() {

  let codigo = $('#codigoOrden').val();

  $.ajax({
    url: "../ajax/ordenes.php?op=crear_barcode",
    method: "POST",
    data: { codigo: codigo },
    cache: false,
    dataType: "json",
    error: function (data) {
      setTimeout("guardar_orden();", 1500);
    },
    success: function (data) {
      //console.log(data)
    }
  });///fin ajax
}

//window.onkeydown= space_guardar_orden;

function guardar_orden(parametro = 'saveEdit') {
  //SESSION DE USUARIO LOGUEADO
  let sessionSucursal = $("#session_sucursal").val()

  let categoria_lente = "";

  let validate = $("#validate").val();
  if (validate == "1") {
    categoria_lente = $("#categoria_lente").val();
  } else {
    categoria_lente = "*";
  }

  let genero = $("#genero_pac_t").html();
  let correlativo_op = $("#correlativo_op").html();
  let paciente = $("#paciente_t").html();
  let fecha_creacion = $("#fecha_creacion").val();
  let od_pupilar = $("#od_pupilar").val();
  let oipupilar = $("#oipupilar").val();
  let odlente = $("#odlente").val();
  let oilente = $("#oilente").val();
  let id_usuario = $("#id_usuario").val();
  let observaciones_orden = $("#observaciones_orden").val();
  let dui = $("#dui_pac_t").html();
  let tipo_lente = $("input[type='radio'][name='tipo_lente']:checked").val();

  if (tipo_lente === undefined) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Debe especificar el tipo de lente',
      showConfirmButton: true,
      timer: 9500
    });
    return false;
  }

  let alto_indice = $("input[type='radio'][name='indice']:checked").val();
  if (alto_indice == undefined) {
    var indice = "No";
  } else {
    var indice = "Si";
  }
  let color = $("input[type='radio'][name='colors']:checked").val();
  let od_esferas = $("#odesferasf").val();
  let od_cilindros = $("#odcilindrosf").val();
  let od_eje = $("#odejesf").val();
  let od_adicion = $("#oddicionf").val();
  let oi_esferas = $("#oiesferasf").val();
  let oi_cilindros = $("#oicilindrosf").val();
  let oi_eje = $("#oiejesf").val();
  let oi_adicion = $("#oiadicionf").val();
  let edad = $("#edad_pac_t").html();
  let ocupacion = $("#ocupacion_pac_t").html();
  let avsc = $("#avsc").val();
  let avfinal = $("#avfinal").val();
  let avsc_oi = $("#avsc_oi").val();
  let avfinal_oi = $("#avfinal_oi").val();
  let telefono = $("#telef_pac_t").html();
  let user = $("#user_act").val();
  let depto = $("#departamento_pac_t").html();
  let municipio = $("#munic_pac_data_t").html();
  let instit = $("#instit_t").html();
  let patologias = $("#patologias-ord").val();
  let id_cita = $("#id_cita_ord").val();
  //alert(id_cita)
  let id_aro = $("#id_aro").val();
  //Aro insertado manual
  let modelo_aro_orden = $("#modelo_aro_orden").val()
  let marca_aro_orden = $("#marca_aro_orden").val()
  let material_aro_orden = $("#material_aro_orden").val()
  let color_aro_orden = $("#color_aro_orden").val()

  let sucursal = $("#user_sucursal").val();
  let codigo = $("#codigo_correlativo").val()

  let campos_orden = document.getElementsByClassName('oblig');

  let laboratorio = $("#laboratorio").val()
  let usuario_lente = $("#usuario_lente").val();
  categoria_lente = $("#categoria_lente").val()
  //Si la sucursal es valencia, se deja por default laboratorio y categoria
  if (laboratorio == undefined && categoria_lente == undefined) {
    laboratorio = "-"
    categoria_lente = "SC"
  }

  if (id_usuario != 1 && parametro != 'Rectificacion') {
    for (let i = 0; i < campos_orden.length; i++) {
      if (campos_orden[i].value == "") {
        let id = campos_orden[i].id;
        //console.log(id);
        $('#' + id).addClass(' is-invalid');
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Existen campos obligatorios vacios',
          showConfirmButton: true,
          timer: 2500
        });
        return false;
      }
    }
  }

  if (correlativo_op != "") {
    $("#nueva_orden_lab").modal('hide');
  }

  //$("#nueva_orden_lab").modal('hide'); //oculta el modal

  //Validacion si es por ingreso manual
  let valueSwitch = document.getElementById('customSwitch1').checked;
  //Edit estado especial
  let btn_switch_edit = document.getElementById('ediccion_orden_citas').checked

  let titular = $("#titular").val();
  let dui_titular = $("#dui_titular").val()
  let id_titular = $("#titular_id").val()
  if (valueSwitch || btn_switch_edit) {
    paciente = $("#paciente").val()
    dui = $("#dui_pac").val()
    edad = $("#edad_pac").val()
    telefono = $("#telef_pac").val()
    genero = $("#genero_pac").val()
    ocupacion = $("#ocupacion_pac").val()
    departamento = $("#departamento_pac").val()
    depto = departamento.toString();
    //Validacion para editar un registro
    if (depto === "") {
      depto = $("#depto_pac").html()
    }
    municipio_array = $("#munic_pac").val()
    municipio = municipio_array.toString()

    if (municipio === "") {
      municipio = $("#muni_pac_label").html()
    }

    instit = $("#instit").val()
    sucursal = $("#sucursal_optica").val();
    //Datos nuevos para titular
    let titular = $("#titular").val();
    let dui_titular = $("#dui_titular").val()
    let id_titular = $("#titular_id").val()
  }
  if (isNaN(edad) || edad > 130) {
    document.getElementById('edad_pac').classList.add('is-invalid')
    return false;
  } else {
    document.getElementById('edad_pac').classList.add('is-valid')
  }
  let customSwitch1 = document.getElementById('customSwitch1').checked
  if (customSwitch1) {
    if (paciente == "" || dui == "" || depto == "" || municipio == "" || instit == "") {
      Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'Por favor, rellenar el formulario completo!',
        showConfirmButton: true,
        timer: 2500
      });
      return false;
    }
  }
  if (id_cita == 0 || id_cita == null) {
    sucursal = $("#sucursal_optica").val()
  } else {
    sucursal = $("#sucursal").val()
  }
  //New code rollback aro
  let old_id_aro = $("#old_id_aro").val();
  //Para capturar la data de input de observaciones ediccion
  let obser_edicion = ""
  if(codigo != "" && parametro !='Rectificacion'){
    //Input si es una ediccion
    obser_edicion = document.getElementById('obser_edicion').value
    if(obser_edicion == ""){
      Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'Por favor, digita el motivo de la edición!',
        showConfirmButton: true,
        timer: 2500
      });
      $("#nueva_orden_lab").modal('show')
      return false;
    }
  }
  $.ajax({
    url: "../ajax/ordenes.php?op=registrar_orden",
    method: "POST",
    data: { codigo: codigo, paciente: paciente, fecha_creacion: fecha_creacion, od_pupilar: od_pupilar, oipupilar: oipupilar, odlente: odlente, oilente: oilente, id_aro: id_aro, id_usuario: id_usuario, observaciones_orden: observaciones_orden, dui: dui, od_esferas: od_esferas, od_cilindros: od_cilindros, od_eje: od_eje, od_adicion: od_adicion, oi_esferas: oi_esferas, oi_cilindros: oi_cilindros, oi_eje: oi_eje, oi_adicion: oi_adicion, tipo_lente: tipo_lente, validate: validate, categoria_lente: categoria_lente, edad: edad, ocupacion: ocupacion, avsc: avsc, avfinal: avfinal, avsc_oi: avsc_oi, avfinal_oi: avfinal_oi, telefono: telefono, genero: genero, user: user, depto: depto, municipio: municipio, instit: instit, patologias: patologias, color: color, indice: indice, id_cita: id_cita, sucursal: sucursal, laboratorio: laboratorio, titular: titular, dui_titular: dui_titular, id_titular: id_titular, modelo_aro_orden: modelo_aro_orden, marca_aro_orden: marca_aro_orden, material_aro_orden: material_aro_orden, color_aro_orden: color_aro_orden, usuario_lente: usuario_lente, old_id_aro: old_id_aro, sessionSucursal: sessionSucursal,obser_edicion:obser_edicion },
    cache: false,
    dataType: "json",
    success: function (data) {
      //console.log(data)
      if (data.mensaje == "exito") {
        order_new_clear_form() //Limpia el html y input
        Swal.fire({
          position: 'top-center',
          icon: 'success',
          title: 'ID orden registrada: ' + data.id_orden_lab,
          showConfirmButton: true
        });
        $("#datatable_ordenes").DataTable().ajax.reload();
        //explode();
      } else if (data == "dui_existe") {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'El DUI del beneficiario ya esta registrado',
          showConfirmButton: true,
          timer: 2500
        });
      } else if (data == 'existe') {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Beneficiario ya existe en la base de datos',
          showConfirmButton: true,
          timer: 2500
        });

      } else if (data == "edit_orden") {
        Swal.fire({
          position: 'top-center',
          icon: 'info',
          title: 'Orden editada exitosamente',
          showConfirmButton: true,
          timer: 2500
        });
        $("#datatable_ordenes").DataTable().ajax.reload(null, false);
      } else if (data == "datos_incorrectos") {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Por favor, rellenar el formulario completo!',
          showConfirmButton: true,
          timer: 2500
        });
        $("#datatable_ordenes").DataTable().ajax.reload(null, false);
      } else if (data == "en_proceso") {
        Swal.fire({
          position: 'top-center',
          icon: 'warning',
          title: 'La orden ya esta en proceso!',
          showConfirmButton: true,
          timer: 2500
        });
        $("#datatable_ordenes").DataTable().ajax.reload(null, false);
        $("#nueva_orden_lab").modal('show')
      } else {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Upps, ha ocurrido un error, intente nuevamente!',
          showConfirmButton: true,
          timer: 2500
        });
        $("#datatable_ordenes").DataTable().ajax.reload(null, false);
      }

    }
  });//////FIN AJAX
  //explode();
    if(parametro=='Rectificacion'){
    marcarRectificacion(dui,observaciones_orden)
  }
}

function marcarRectificacion(dui,observaciones_orden){
  $.ajax({
    url: "../ajax/ordenes.php?op=marcar_rectificacion",
    method: "POST",
    data: {dui:dui,observaciones_orden:observaciones_orden},
    dataType: "json",
    success: function (data) {
     console.log(data)
    }
  });//Fin Ajax
}

//////////ELIMINAR CLASE IS INVALID
$(document).on('keyup', '.is-invalid', function () {
  let id = $(this).attr("id");
  document.getElementById(id).classList.remove('is-invalid');
  document.getElementById(id).classList.add('is-valid');

});

function alerts(alert) {
  Swal.fire({
    position: 'top-center',
    icon: alert,
    title: 'Existen campos obligatorios vacios',
    showConfirmButton: true,
    timer: 3500
  });
}

function verEditar(codigo, paciente, id_aro, institucion, id_cita,dui) {
    //Input para observaciones
  document.getElementById('observaciones_order').classList.replace('col-md-12','col-md-6')
  document.getElementById('obser_edicion').value = ""
  //Para observaciones
  document.getElementById('input_obser').style.display = "block"
  //Activa ediccion espcial
  if (names_permisos.includes('edit_especial_orden')) {
    document.getElementById('btn_edit_especial').style.display = 'block';
    document.getElementById('radio_button_orden').style.display = "none";
  } else {
    document.getElementById('radio_button_orden').style.display = "block"
    document.getElementById('btn_edit_especial').style.display = 'none';
  }
  $("#validate").val("1");
  document.getElementById("ediccion_orden_citas").checked = false
  //GESTION PERMISOS BTN ESTADO
  if (names_permisos.includes('ediccion_orden')) {
    $("#order_create_edit").attr('disabled', false)
  } else {
    $("#order_create_edit").attr('disabled', true)
  }
  //Disabled selected sucursal
  $("#sucursal_optica").attr('disabled', true)
  $("#sucursal_optica_cita").attr('disabled', true)

  $("#modal_title").html('EDITAR ORDEN')
  //Modal Collapse acciones
  $(".collapse").collapse('hide');

  document.getElementById('tableAcciones').style.display = "block"

  estado_btn_edit(); // cambia el contenido del boton del modal
  document.getElementById('btnBuscarCitado').style.display = "none" //Oculta boton agregar cita
  let categoria = $("#get_categoria").val();
  //document.getElementById("hist_orden").style.display = "block";
  if (categoria == 'a') {
    let disable_inputs = document.getElementsByClassName('rx_f');
    for (i = 0; i < disable_inputs.length; i++) {
      let id_element = disable_inputs[i].id;
      document.getElementById(id_element).readOnly = true;
    }
  }

  $("#nueva_orden_lab").modal('show');
  $('#munic_pac').val('1'); // Select the option with a value of '1'
  $('#munic_pac').trigger('change');
  $('#departamento_pac').val('1'); // Select the option with a value of '1'
  $('#departamento_pac').trigger('change');
  //clear_attr();
  $.ajax({
    url: "../ajax/ordenes.php?op=get_data_orden",
    method: "POST",
    cache: false,
    data: { codigo: codigo, paciente: paciente, id_aro: id_aro, institucion: institucion, id_cita: id_cita },
    dataType: "json",
    success: function (data) {
      //console.log(data)

      //Permisos para ediccion espcial, si lo tiene se oculta el ingreso manual btn
      verify_permit_edicion_especial()

      $("#odesferasf").val(data.od_esferas);
      $("#odcilindrosf").val(data.od_cilindros);
      $("#odejesf").val(data.od_eje);
      $("#oddicionf").val(data.od_adicion);
      $("#oiesferasf").val(data.oi_esferas);
      $("#oicilindrosf").val(data.oi_cilindros);
      $("#oiejesf").val(data.oi_eje);
      $("#oiadicionf").val(data.oi_adicion);
      $("#od_pupilar").val(data.pupilar_od);
      $("#oipupilar").val(data.pupilar_oi);
      $("#odlente").val(data.lente_od);
      $("#oilente").val(data.lente_oi);
      $("#marca_aro_orden").val(data.marca);
      $("#modelo_aro_orden").val(data.modelo)
      $("#horizontal_aro_orden").val(data.horizontal_aro);
      $("#material_aro_orden").val(data.material);
      $("#observaciones_orden").val(data.puente_aro);
      $("#observaciones_orden").val(data.observaciones);
      $("#color_aro_orden").val(data.color);
      $("#categoria_lente").val(data.categoria);
      $("#destino_orden_lente").val(data.laboratorio);

      $("#usuario_pac").val(data.usuario_lente);
      $("#avsc").val(data.avsc);
      $("#avfinal").val(data.avfinal);
      $("#avsc_oi").val(data.avsc_oi);
      $("#avfinal_oi").val(data.avfinal_oi);

      $("#patologias-ord").val(data.patologias)

      $("#codigo_correlativo").val(data.codigo)
      $("#id_cita_ord").val(data.id_cita)
      $("#id_aro").val(data.id_aro)
      $("#laboratorio").val(data.laboratorio)
      $("#usuario_lente").val(data.usuario_lente)

      if (data.colorTratamiento == "Blanco") {
        document.getElementById("blanco").checked = true;
      }
      if (data.colorTratamiento == "Photocromatico") {
        document.getElementById("photo").checked = true;
      }
      $("#customSwitch1").prop('checked', true) // default en true
      let valueSwitch = document.getElementById('customSwitch1').checked;
      //Btn para ediccion especial
      let btn_switch_edit = document.getElementById('ediccion_orden_citas').checked;

      //Validation por cita
      if (data.id_cita != 0 || data.id_cita != "") {
        $("#customSwitch1").prop("checked", false);
        document.getElementById('show_form_manual').style.display = "none"
        document.getElementById('tables_cita').style.display = "block"
        $("#paciente_t").html(data.paciente);
        $("#dui_pac_t").html(data.dui);
        $("#edad_pac_t").html(data.edad);
        $("#correlativo_op").html("ORDEN:" + data.codigo);
        $("#telef_pac_t").html(data.telefono);
        $("#genero_pac_t").html(data.genero);
        $("#ocupacion_pac_t").html(data.ocupacion);
        $("#departamento_pac_t").html(data.depto);
        $("#munic_pac_data_t").html(data.municipio);
        $("#instit_t").html(data.institucion);
        $("#sucursal_optica_cita").val(data.sucursal)

        $("#titular").val('');
        $("#dui_titular").val('')
        $("#titular_id").val('')

      } else {
        $("#customSwitch1").prop("checked", false);
        document.getElementById('tables_cita').style.display = "block"
        document.getElementById('id_cita_ord').value = 0
        document.getElementById('show_form_manual').style.display = "block"

        $("#paciente_t").html('');
        $("#dui_pac_t").html('');
        $("#edad_pac_t").html('');
        $("#correlativo_op").html("ORDEN:" + data.codigo);
        $("#telef_pac_t").html('');
        $("#genero_pac_t").html('');
        $("#ocupacion_pac_t").html('');
        $("#departamento_pac_t").html('');
        $("#munic_pac_data_t").html('');
        $("#instit_t").html('');
      }

      //Validacion si es por ingreso manual
      if (valueSwitch || btn_switch_edit) {
        paciente = $("#paciente").val(data.paciente)
        dui = $("#dui_pac").val(data.dui)
        edad = $("#edad_pac").val(data.edad)
        telefono = $("#telef_pac").val(data.telefono)
        genero = $("#genero_pac").val(data.genero)
        ocupacion = $("#ocupacion_pac").val(data.ocupacion)
        depto = $("#depto_pac").html(data.depto)
        municipio = $("#muni_pac_label").html(data.municipio)
        instit = $("#instit").val(data.institucion)
        $("#sucursal_optica").val(data.sucursal);
        //new
        $("#titular").val(data.titular);
        $("#dui_titular").val(data.dui_titular)
        $("#titular_id").val(data.id_titulares)
      }
      if (data.institucion == "CONYUGE") {
        document.getElementById('titular_form').style.display = "block"
        $("#paciente_t").html(data.paciente);
        $("#dui_pac_t").html(data.dui);
        $("#edad_pac_t").html(data.edad);
        $("#correlativo_op").html("ORDEN:" + data.codigo);
        $("#telef_pac_t").html(data.telefono);
        $("#genero_pac_t").html(data.genero);
        $("#ocupacion_pac_t").html(data.ocupacion);
        $("#departamento_pac_t").html(data.depto);
        $("#munic_pac_data_t").html(data.municipio);
        $("#instit_t").html(data.institucion);
      } else {
        document.getElementById('titular_form').style.display = "none"
      }
      if (data.genero == "Mascu") {
        $("#genero_pac_t").html('Masculino');
        $("#genero_pac").val('Masculino')
      } else {
        $("#genero_pac_t").html('Femenino');
        $("#genero_pac").val('Femenino')
      }
      //Alto indice
      $("#alto-indice").attr('checked', false)
      if (data.alto_indice == "Si") {
        $("#alto-indice").attr('checked', true)
      }

      //Carga el DUI del paciente
      $("#dui_paciente").val(data.dui)
      let tipo_lente = data.tipo_lente;
      const acentos = { 'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u', 'Á': 'A', 'É': 'E', 'Í': 'I', 'Ó': 'O', 'Ú': 'U' };
      let lente = tipo_lente.split('').map(letra => acentos[letra] || letra).join('').toString();
      let cadena = lente.replace(/ /g, "");

      document.getElementById(cadena).checked = true

      //New code
      //ROLLBACK ARO
      $("#old_id_aro").val(data.id_aro)
    }
  });
  show_create_order(codigo);
  var ob = document.getElementById("order_create_edit");
  ob.classList.remove("btn-block");
  document.getElementById("btn_rectificar").style.display = "flex";
  historialOrden(codigo,dui);
}

function historialOrden(codigo,dui) {
  let categoriaUser = $("#categoria-usuer-hist").val();
  console.log(categoriaUser)
  $.ajax({
    url: "../ajax/ordenes.php?op=ver_historial_orden",
    method: "POST",
    cache: false,
    data: { codigo: codigo, dui_paciente: dui },
    dataType: "json",
    success: function (data) {
      $("#hist_orden_detalles").html("");
      let filas = '';
      for (var i = 0; i < data.length; i++) {
        filas = filas + "<tr id='fila" + i + "'>" +
          "<td colspan='15' style='width:15%''>" + data[i].fecha_hora + "</td>" +
          "<td colspan='25' style='width:25%''>" + data[i].usuario + "</td>" +
          "<td colspan='25' style='width:25%''>" + data[i].accion + "</td>" +
          "<td colspan='35' style='width:35%''>" + data[i].observaciones + "</td>" +
          "</tr>";
      }
      $("#hist_orden_detalles").html(filas);
    }//Fin success
  });//Fin ajax
}

////////////////OCULTAR ICONOS //////////
$(document).on('click', '#order_new', function () {
    //Input para observaciones
  document.getElementById('input_obser').style.display = "none"
  document.getElementById('observaciones_order').classList.replace('col-md-6','col-md-12')
  order_new_clear_form()
  //Disabled false btn
  $("#order_create_edit").attr('disabled', false)
  $("#created").html('')
  //Disabled selected = false sucursal
  $("#sucursal_optica").attr('disabled', false)
  $("#sucursal_optica_cita").attr('disabled', false)
  //Oculta el btn ediccion espcial
  document.getElementById('btn_edit_especial').style.display = "none";
  //Form manual default hide
  document.getElementById('show_form_manual').style.display = "none"
  //table cita default show
  document.getElementById('tables_cita').style.display = "block"
  //Verificar permiso manual -> show modal
  verify_permit_manual()
});

function order_new_clear_form() {
  //Content vacio a nueva orden
  document.getElementById('btnBuscarCitado').style.display = "block"
  $("#modal_title").html('NUEVA ORDEN')
  $("#alto-indice").attr('checked', false);
  document.getElementById('alto-indice').checked = false;
  $('#alto-indice').val("");

  estado_btn_save();
  //Conyuge
  document.getElementById('titular_form').style.display = "none"

  $("#mensaje_existe_dui").html('')
  $("#depto_pac").html('')
  $("#muni_pac_label").html('')
  $("#id_aro").val('')
  //muestra la tabla de acciones
  document.getElementById('tableAcciones').style.display = "none"

  $("#paciente_t").html('');
  $("#dui_pac_t").html('');
  $("#edad_pac_t").html('');
  $("#correlativo_op").html('');
  $("#telef_pac_t").html('');
  $("#genero_pac_t").html('');
  $("#ocupacion_pac_t").html('');
  $("#departamento_pac_t").html('');
  $("#munic_pac_data_t").html('');
  $("#instit_t").html('');
  $("#id_cita_ord").val('')
  $("#id_aro").val('')

  $("#validate").val("save");
  $('#munic_pac').val('1'); // Select the option with a value of '1'
  $('#munic_pac').trigger('change');
  $('#departamento_pac').val('1'); // Select the option with a value of '1'
  $('#departamento_pac').trigger('change');
  document.getElementById("instit").selectedIndex = "0";

  document.getElementById("buscar_aro").style.display = "flex";
  document.getElementById("mostrar_imagen").style.display = "none";
  //document.getElementById("hist_orden").style.display = "none";
  let elements = document.getElementsByClassName("clear_orden_i");

  for (i = 0; i < elements.length; i++) {
    let id_element = elements[i].id;
    document.getElementById(id_element).value = "";
  }

  //document.getElementById("departamento_pac_data").innerHTML = "";
  document.getElementById("munic_pac_data_t").innerHTML = "";

  let checkboxs = document.getElementsByClassName("chk_element");
  for (j = 0; j < checkboxs.length; j++) {
    let id_chk = checkboxs[j].id;
    document.getElementById(id_chk).checked = false;
  }
  document.getElementById("btn_rectificar").style.display = "none";

  document.getElementById("order_create_edit").style.display = "block";
  var ob = document.getElementById("order_create_edit");
  ob.classList.add("btn-block");
}

function show_create_order(codigo) {
  console.log('hh')
  let cat_user = $("#cat_users").val();

  if (cat_user == 3 || cat_user == 1) {
    $.ajax({
      url: "../ajax/ordenes.php?op=show_create_order",
      method: "POST",
      cache: false,
      data: { codigo: codigo },
      dataType: "json",
      success: function (data) {

        $("#created").html(data.info_orden)
      }
    });
  } else {
    console.log('00')
  }
}

function clear_form_orden() {

  let fields = document.getElementsByClassName("clear_orden_i");

  for (i = 0; i < fields.length; i++) {
    let val_element = fields[i].value;
    let id_element = fields[i].id;
    document.getElementById(id_element).value = "";
  }

  $("#observaciones_orden").val("");
  document.getElementById('observaciones_orden').classList.remove('is-invalid');
  document.getElementById("VisionSencilla").checked = false;
  document.getElementById("Flaptop").checked = false;
  document.getElementById("Progresive").checked = false;
}

function clear_attr() {
  document.getElementById('paciente').classList.remove('is-invalid', 'is-valid');
  //document.getElementById('dui_pac').classList.remove('is-invalid','is-valid');
  document.getElementById('fecha_creacion').classList.remove('is-invalid', 'is-valid');
  document.getElementById('odesferasf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('odcilindrosf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('odejesf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oddicionf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oiesferasf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oicilindrosf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oiejesf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oiadicionf').classList.remove('is-invalid', 'is-valid');
  document.getElementById('od_pupilar').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oipupilar').classList.remove('is-invalid', 'is-valid');
  document.getElementById('odlente').classList.remove('is-invalid', 'is-valid');
  document.getElementById('oilente').classList.remove('is-invalid', 'is-valid');
  document.getElementById('marca_aro_orden').classList.remove('is-invalid', 'is-valid');
  document.getElementById('modelo_aro_orden').classList.remove('is-invalid', 'is-valid');
  document.getElementById('horizontal_aro_orden').classList.remove('is-invalid', 'is-valid');
  document.getElementById('vertical_aro_orden').classList.remove('is-invalid', 'is-valid');
  document.getElementById('puente_aro_orden').classList.remove('is-invalid', 'is-valid');
  document.getElementById('observaciones_orden').classList.remove('is-invalid', 'is-valid');
}

function get_numero_orden() {
  clear_form_orden();
}

function update_numero_orden() {
  $.ajax({
    url: "../ajax/ordenes.php?op=get_correlativo_orden",
    method: "POST",
    cache: false,
    dataType: "json",
    success: function (data) {
      let correlativo_op = data.codigo_orden;
      guardar_orden(correlativo_op);
    }
  });
}

function listar_ordenes_digitadas(filter) {

  let inicio = $("#desde_orders").val();
  let hasta = $("#hasta_orders").val();
  let listado_general = names_permisos.includes('listado_general_citas');
  let sucursal = $("#sucursal").val();
  let permiso_listar = ''; listado_general ? permiso_listar = 'Ok' : permiso_listar = 'Not';
  //console.log(names_permisos)
  tabla_ordenes = $('#datatable_ordenes').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/ordenes.php?op=get_ordenes_dig",
      type: "POST",
      dataType: "json",
      data: { inicio: inicio, hasta: hasta, filter: filter, sucursal: sucursal, permiso_listar: permiso_listar },
      error: function (e) {
        console.log(e.responseText);
      },
    },

    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 25,//Por cada 10 registros hace una paginación
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

function eliminarBeneficiario(codigo) {

  let cat_user = $("#cat_users").val();
  const permiso_eliminar_orden = names_permisos.includes('eliminar_orden')

  if (cat_user == "Admin" || permiso_eliminar_orden === true) {
    $.ajax({
      url: "../ajax/ordenes.php?op=eliminar_orden",
      method: "POST",
      cache: false,
      data: { codigo: codigo },
      dataType: "json",
      success: function (data) {
        $("#datatable_ordenes").DataTable().ajax.reload();
        if (data == "orden_proceso") {
          Swal.fire({
            position: 'top-center',
            icon: 'warning',
            title: 'La orden esta en proceso',
            showConfirmButton: true,
            timer: 9500
          });
        } else {
          Swal.fire({
            position: 'top-center',
            icon: 'success',
            title: 'La orden ha sido eliminada',
            showConfirmButton: true,
            timer: 9500
          });
        }
      }
    });

  } else {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'No posee permisos para eliminar',
      showConfirmButton: true,
      timer: 9500
    });
  }
}

///////////////////////////////////////GESTION ORDENES ANDRES //////////////
function listar_ordenes_enviar() {

  $("#data_ordenes").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    //dom: 'Bfrti',
    //"buttons": [ "excel"],
    "searching": true,
    "ajax":
    {
      url: '../ajax/ordenes.php?op=listar_ordenes_enviar',
      type: "post",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "language": {
      "sSearch": "Buscar:"
    }
  }).buttons().container().appendTo('#datatable_ordenes_wrapper .col-md-6:eq(0)');
}

$(document).on("click", ".actions_orders", function () {
  console.log("Okok")
  document.getElementById("order_create_edit").style.display = "none";
  //document.getElementById("sendto").style.display = "flex";
});

function enviarOrden() {
  let numero_orden = $("#correlativo_op").html();
  $.ajax({
    url: "../ajax/ordenes.php?op=enviar_orden",
    method: "POST",
    cache: false,
    data: { numero_orden: numero_orden },
    dataType: "json",
    success: function (data) {
      $("#nueva_orden_lab").modal('hide');
      $("#data_ordenes").DataTable().ajax.reload();
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Orden enviada exitosamente',
        showConfirmButton: true,
        timer: 9500
      });
    }
  });
}

function listar_ordenes_enviadas() {
  enviados = $('#data_ordenes').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [
      'excelHtml5',
    ],
    "ajax": {
      url: "../ajax/ordenes.php?op=listar_ordenes_enviadas",
      type: "POST",
      dataType: "json",
      //data:{sucursal:sucursal,sucursal_usuario:sucursal_usuario},           
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 10,//Por cada 10 registros hace una paginación
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

function get_ordenes_por_enviar() {
  let inicio = $("#desde_clasif").val();
  let hasta = $("#hasta_clasif").val();
  if ((inicio == undefined || inicio == null || inicio == "") && (hasta == undefined || hasta == null || hasta == "")) {
    inicio = '0';
    hasta = '0';
  }

  let lente = $("#tipo_lente_pendiente").val();
  let instit = $("#inst-env").val();

  //console.log(`inicio ${inicio} hasta ${hasta} lente ${lente}`);return false;
  table_enviados = $('#data_ordenes_sin_procesar').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'frtip',//Definimos los elementos del control de tabla
    //buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_ordenes_por_enviar",
      type: "POST",
      data: { inicio: inicio, hasta: hasta, lente: lente, instit: instit },
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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

//////////////////////////  ORDENES ENVIADAS LENTES ////////
function get_ordenes_env(laboratorio) {

  $("#lab_act").html(laboratorio);
  let inicio = $("#desde_table").val();
  let hasta = $("#hasta_table").val();
  let cat_lente = $("#cat_lente").val();
  let tipo_lente = $("#tipo_lente_report").val();

  if ((inicio == undefined || inicio == null || inicio == "") && (hasta == undefined || hasta == null || hasta == "")) {
    inicio = '0';
    hasta = '0';
  }
  let instit = $("#inst-proc").val();

  table_env = $('#data_ordenes_env').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excel'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_ordenes_env",
      type: "POST",
      //dataType : "json",
      data: { laboratorio: laboratorio, cat_lente: cat_lente, inicio: inicio, hasta: hasta, tipo_lente: tipo_lente, instit: instit },
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 48,//Por cada 10 registros hace una paginación
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
        "sFirst": "Primero", "sLast": "Último", "sNext": "Siguiente", "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }, //cerrando language
  });
}
//////////////////////////ORDENES ENVIADAS A LABORATORIO
function get_ordenes_env_lab(laboratorio) {
  $("#lab_actual_send").html(laboratorio);

  let inicio = $("#desde_table_send").val();
  let hasta = $("#hasta_table_send").val();
  let cat_lente = $("#cat_lente_send").val();
  let tipo_lente = $("#tipo_lente_env").val();

  table_env = $('#data_ordenes_env_laboratorio').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excel'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_ordenes_enviadas_lab",
      type: "POST",
      //dataType : "json",
      data: { laboratorio: laboratorio, cat_lente: cat_lente, inicio: inicio, hasta: hasta, tipo_lente: tipo_lente },
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 24,//Por cada 10 registros hace una paginación
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
        "sFirst": "Primero", "sLast": "Último", "sNext": "Siguiente", "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }, //cerrando language
  });
}
//////////////// OEDENES PROCESANDO 
//////////////////////////ORDENES ENVIADAS LENTES
function get_ordenes_procesando() {
  table_proces = $('#data_ordenes_procesando').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'frtip',//Definimos los elementos del control de tabla
    //buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_ordenes_procesando",
      type: "POST",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 20,//Por cada 10 registros hace una paginación
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
        "sFirst": "Primero", "sLast": "Último", "sNext": "Siguiente", "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }, //cerrando language
  });
}

/*********************
ARREGLO ORDENES ENVIAR
***************************/
var ordenes_enviar = [];
$(document).on('click', '.ordenes_enviar', function () {
  let id_orden = $(this).attr("value");
  let paciente = $(this).attr("name");
  let id_item = $(this).attr("id");
  let checkbox = document.getElementById(id_item);
  let check_state = checkbox.checked;

  if (check_state) {
    let obj = {
      id_orden: id_orden,
      paciente: paciente,
      id_item: id_item
    }
    ordenes_enviar.push(obj);
  } else {
    let indice = ordenes_enviar.findIndex((objeto, indice, ordenes_enviar) => {
      return objeto.id_orden == id_orden
    });
    ordenes_enviar.splice(indice, 1)
  }
  console.log(ordenes_enviar);
  let count = ordenes_enviar.length;
  $("#count_select").html(count);
});

/************ confirmar orden envio  ************/
function enviar_confirm_v() {
  let n_ord_enviar = ordenes_enviar.length;
  if (n_ord_enviar != 0) {
    $("#confirmar_envio_ord").modal('show');
    $("#n_trabajos_env").html(n_ord_enviar);
  } else {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Debe agregar items de envio',
      showConfirmButton: true,
      timer: 2500
    });
    return false;
  }
}


function registrarEnvioVet() {
  let dest = $("#destino_envio").val();
  let user = $("#user_act").val();
  let cat = $("#cat_envio").val();
  let categoria_len = cat.toString();
  let destino = dest.toString();
  $('#confirmar_envio_ord').modal('hide');

  $.ajax({
    url: "../ajax/ordenes.php?op=enviar_ordenes",
    method: "POST",
    data: { 'arrayEnvio': JSON.stringify(ordenes_enviar), 'destino': destino, 'user': user, 'categoria_len': categoria_len },
    cache: false,
    dataType: "json",

    success: function (data) {
      //console.log(data)
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Envio realizado exitosamente',
        showConfirmButton: true,
        timer: 2500
      });
      ordenes_enviar = [];
      $("#data_ordenes_sin_procesar").DataTable().ajax.reload(null, false);
    }
  });//fin ajax
}

////LENTES/////************

function listar_ordenes_enviar() {
  $("#datatable_ordenes_enviar").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    //dom: 'Bfrti',
    //"buttons": [ "excel"],
    "searching": true,
    "ajax":
    {
      url: '../ajax/ordenes.php?op=get_ordenes_enviar',
      type: "post",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "language": {
      "sSearch": "Buscar:"
    }
  }).buttons().container().appendTo('#datatable_ordenes_wrapper .col-md-6:eq(0)');

}

function verImagen(img) {
  $('#imagen_aro_order').modal('show');

  document.getElementById("imagen_aro_ord").src = "images/" + img;

}
/////////////////panel damin show //////////
$(document).on('click', '.show_panel_admin', function () {
  document.getElementById("order_create_edit").style.display = "flex";
  document.getElementById("created").style.display = "none";
  document.getElementById("enviar_a").style.display = "none";
  $("#validate").val("1");
});

function sendEdit() {
  let codigo = $("#correlativo_op").html();
  let destino = $("#destino_orden_lente").val();
  let usuario = $("#user_act").val();

  if (destino == "0" || usuario == "0") {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Campos obligatorio vacios',
      showConfirmButton: true,
      timer: 9500
    });
    return false;
  } else {
    $("#nueva_orden_lab").modal('hide');
    $("#data_ordenes").DataTable().ajax.reload();
  }

  let paciente = $("#paciente").val();
  $.ajax({
    url: "../ajax/ordenes.php?op=enviar_lente",
    method: "POST",
    data: { codigo: codigo, destino: destino, usuario: usuario },
    dataType: "json",
    success: function (data) {
      //console.log(data);
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Enviado a ' + destino,
        showConfirmButton: true,
        timer: 2500
      });
      guardar_orden();
    }
  });//Fin Ajax

}

$(document).on('click', '#labs_envio', function () {
  let laboratorio = $(this).attr("value");
  //console.log(laboratorio);
});

function editaLaboratorio(paciente, categoria, laboratorio, codigo) {

  $("#cambiaLabModal").modal('show');
  $("#pac_edit_lab").html(paciente);
  $("#categoria_lente_edit").val(categoria);
  $("#destino_orden_lente_edit").val(laboratorio);
  $("#codigoEd").val(codigo);
}

function CambiarLab() {

  let cat = $("#categoria_lente_edit").val();
  let dest = $("#destino_orden_lente_edit").val();
  let codigo = $("#codigoEd").val();
  let paciente = $("#pac_edit_lab").html();

  $.ajax({
    url: "../ajax/ordenes.php?op=editar_envio",
    method: "POST",
    data: { codigo: codigo, dest: dest, cat: cat, paciente: paciente },
    dataType: "json",
    success: function (data) {
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Orden exitosamente',
        showConfirmButton: true,
        timer: 2000
      });
      $("#cambiaLabModal").modal('hide');
      $("#data_ordenes").DataTable().ajax.reload();
    }
  });//Fin Ajax
}

//function reportDownload(){
// let 
//}


function showTablas() {
  let laboratorio = $("#lab_act").html();
  let tipo_lente = $("#tipo_lente_report").val();
  let base = $("#cat_lente").val();
  let inicio = $("#desde_table").val();
  let fin = $("#hasta_table").val();

  if (laboratorio == '0') {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccione el laboratorio',
      showConfirmButton: true,
      timer: 3500
    });
    return false;
  }
  if ((inicio == undefined || inicio == null || inicio == "") || (fin == undefined || fin == null || fin == "")) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccione rango de fecha',
      showConfirmButton: true,
      timer: 3500
    });
    return false;
  }

  if (tipo_lente == '0' || base == '0') {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Base y/o lente deben ser seleccionados',
      showConfirmButton: true,
      timer: 3500
    });
    return false;
  }

  var form = document.createElement("form");
  form.target = "print_popup";
  form.method = "POST";
  form.action = "tabla_resumen.php";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "laboratorio";
  input.value = laboratorio;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "tipo_lente";
  input.value = tipo_lente;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "base";
  input.value = base;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "inicio";
  input.value = inicio;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "fin";
  input.value = fin;
  form.appendChild(input);

  let alto = (parseInt(window.innerHeight) / 2);
  let ancho = (parseInt(window.innerWidth) / 2);
  let x = parseInt((screen.width - ancho));
  let y = parseInt((screen.height - alto));

  document.body.appendChild(form);//"width=600,height=500"
  window.open("about:blank", "print_popup", `
             width=${ancho}
             height=${alto}
             top=${y}
             left=${x}`);
  form.submit();
  document.body.removeChild(form);



}
var orders = []
function print_orden_alert() {
  orders = [];
  for (var i = 0; i < ordenes_enviar.length; i++) {
    orders.push(ordenes_enviar[i].id_item);
  }

  let items = orders.length;
  if (items == 0) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Orden de impresion vacia',
      showConfirmButton: true,
      timer: 2500
    });
    return false;
  }
  $("#n_items_print").html(items);
  $("#print_order").modal('show');
}

function imprimir_ordenes() {
  ordenes_enviar = [];
  $("#count_select").html("0");
  $("#data_ordenes_env_laboratorio").DataTable().ajax.reload();
  $("#print_order").modal('hide');
  $.ajax({
    url: "../ajax/ordenes.php?op=reset_tables_print",
    method: "POST",
    data: { 'array_restart_print': JSON.stringify(orders) },
    cache: false,
    dataType: "json",
    success: function (data) {
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Envío Realizado',
        showConfirmButton: true,
        timer: 2500
      });

      $("#data_ordenes_env").DataTable().ajax.reload();
      window.open('imp_orden.php?orders=' + orders, '_blank');
      document.getElementById("select-all-env").checked = false;
      orders = [];

    }
  });//fin ajax


}

function clear_input_date() {
  document.getElementById("desde_table").value = null;
  document.getElementById("hasta_table").value = null;
}

function clear_input_date_clas() {
  document.getElementById("desde_clasif").value = null;
  document.getElementById("hasta_clasif").value = null;
}


///////////////////// TABLE ORDENES ENVIADAS /////////
function showTablasEnviadas() {
  let laboratorio = $("#lab_actual_send").html();
  let tipo_lente = $("#tipo_lente_env").val();
  let base = $("#cat_lente_send").val();
  let inicio = $("#desde_table_send").val();
  let fin = $("#hasta_table_send").val();
  //console.log(laboratorio); return false;
  if (laboratorio == '0') {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccione el laboratorio',
      showConfirmButton: true,
      timer: 3500
    });
    return false;
  }
  if ((inicio == undefined || inicio == null || inicio == "") || (fin == undefined || fin == null || fin == "")) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccione rango de fecha',
      showConfirmButton: true,
      timer: 3500
    });
    return false;
  }

  if (tipo_lente == '0' || base == '0') {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Base y/o lente deben ser seleccionados',
      showConfirmButton: true,
      timer: 3500
    });
    return false;
  }

  var form = document.createElement("form");
  form.target = "print_popup";
  form.method = "POST";
  form.action = "tabla_resumen_enviadas.php";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "laboratorio";
  input.value = laboratorio;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "tipo_lente";
  input.value = tipo_lente;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "base";
  input.value = base;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "inicio";
  input.value = inicio;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "fin";
  input.value = fin;
  form.appendChild(input);

  let alto = (parseInt(window.innerHeight) / 2);
  let ancho = (parseInt(window.innerWidth) / 2);
  let x = parseInt((screen.width - ancho));
  let y = parseInt((screen.height - alto));

  document.body.appendChild(form);//"width=600,height=500"
  window.open("about:blank", "print_popup", `
             width=${ancho}
             height=${alto}
             top=${y}
             left=${x}`);
  form.submit();
  document.body.removeChild(form);
}

function selectOrdenesImprimir() {

  let checkbox = document.getElementById('select-all-env');
  let check_state = checkbox.checked;
  let ordenes_imprimir = document.getElementsByClassName('ordenes_enviar');
  if (check_state) {
    for (let i = 0; i <= ordenes_imprimir.length - 1; i++) {
      let id_item = ordenes_imprimir[i].id;
      document.getElementById(id_item).checked = true;
      orders.push(id_item);
    }
  } else if (check_state == false) {
    for (let i = 0; i <= ordenes_imprimir.length - 1; i++) {
      let id_item = ordenes_imprimir[i].id;
      document.getElementById(id_item).checked = false;
    }
    orders = [];
  }
}

function print_orden_alert_multiple() {

  let items = orders.length;
  if (items == 0) {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Orden de impresion vacia',
      showConfirmButton: true,
      timer: 2500
    });
    return false;
  }
  $("#n_items_print").html(items);
  $("#print_order").modal('show');
}

function registrarRectificacion() {
  let campos_orden = document.getElementsByClassName('oblig');

  let motivo = $('#motivo-rct').val();
  let estado_aro = $('#est-aro-rct').val();
  let usuario = $("#usuario").val();
  let codigo = $("#correlativo_op").html();
  let correlativo = $("#correlativo_rectificacion").html();

  if (motivo == '') {
    Swal.fire({
      position: 'top-center', icon: 'error',
      title: 'Debe especificar rl motivo de la rectificacion', showConfirmButton: true, timer: 2500
    }); return false;
  }

  $.ajax({
    url: "../ajax/ordenes.php?op=registrar_rectificacion",
    method: "POST",
    data: { motivo: motivo, estado_aro: estado_aro, usuario: usuario, codigo: codigo, correlativo: correlativo },
    cache: false,
    success: function (data) {
      if (data == 'Insert!') {
        $("#rectificacionesModal").modal('hide');
        guardar_orden('Rectificacion')
      } else {
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'Ha ocurrido un error',
          showConfirmButton: true,
          timer: 2500
        });
      }
    }
  });
}

const btn_recti = document.getElementById('btn_rectificar');

btn_recti.addEventListener("click", () => {
  getCorrelativoRectificacion();
  document.getElementById("motivo-rct").value = "";
  document.getElementById("est-aro-rct").value = "";
  $('#rectificacionesModal').on('shown.bs.modal', function () {
    $('#motivo-rct').focus();
  });

});


function getCorrelativoRectificacion() {
  $.ajax({
    url: "../ajax/ordenes.php?op=correlativo_rectificacion",
    method: "POST",
    cache: false,
    dataType: "json",
    success: function (data) {
      $("#correlativo_rectificacion").html(data.correlativo);
    }
  });
}

function detRecti(codigoOrden) {

  $('#modal_detalle_recti').modal();
  listar_orden_orig_mods(codigoOrden);
}

function listar_orden_orig_mods(codigoOrden) {
  $.ajax({
    url: "../ajax/ordenes.php?op=listar_ordenes_rect",
    method: "POST",
    data: { codigoOrden: codigoOrden },
    cache: false,
    success: function (data) {
      $("#listar_ordenes_rectif").html(data);
    }
  });

  listar_orden_act(codigoOrden)
}

function listar_orden_act(codigoOrden) {
  $.ajax({
    url: "../ajax/ordenes.php?op=listar_det_orden_act",
    method: "POST",
    data: { codigoOrden: codigoOrden },
    cache: false,
    success: function (data) {
      $("#orden_inicial").html(data);
    }
  });
}

function listar_rectificaciones() {
  table_enviados = $('#data_rectificaciones').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'frtip',//Definimos los elementos del control de tabla
    //buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=listar_rectificaciones",
      type: "POST",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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


function listar_estadisticas() {

  let inicio = $("#desde_estadistica").val();
  let hasta = $("#hasta_estadistica").val();

  tabla_ordenes = $('#datatable_estadisticas').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [
      'excelHtml5',
    ],

    "ajax": {
      url: "../ajax/ordenes.php?op=get_estadisticas",
      type: "POST",
      data: { inicio: inicio, hasta: hasta },
      error: function (e) {
        console.log(e.responseText);
      },
    },

    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 25,//Por cada 10 registros hace una paginación
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
  });
}

////////////////get cambio departamento ///////////////

$(document).ready(function () {
  $("#departamento_pac").change(function () {
    $("#departamento_pac option:selected").each(function () {
      let depto = $(this).val();
      get_municipios(depto);
    });
  })
});

/***************MUNICIPIOS ***************/

var ahuachapan = ["Ahuachapán", "Apaneca", "Atiquizaya", "Concepción de Ataco", "El Refugio", "Guaymango", "Jujutla", "San Francisco Menéndez", "San Lorenzo", "San Pedro Puxtla", "Tacuba", "Turín"];
var cabanas = ["Cinquera", "Dolores (Villa Doleres)", "Guacotecti", "Ilobasco", "Jutiapa", "San Isidro", "Sensuntepeque", "Tejutepeque", "Victoria"];
var chalatenango = ["Agua Caliente", "Arcatao", "Azacualpa", "Chalatenango", "Citalá", "Comalapa", "Concepción Quezaltepeque", "Dulce Nombre de María", "El Carrizal", "El Paraíso", "La Laguna", "La Palma", "La Reina", "Las Vueltas", "Nombre de Jesús", "Nueva Concepción", "Nueva Trinidad", "Ojos de Agua", "Potonico", "San Antonio de la Cruz", "San Antonio Los Ranchos", "San Fernando", "San Francisco Lempa", "San Francisco Morazán", "San Ignacio", "San Isidro Labrador", "San José Cancasque (Cancasque)", "San José Las Flores", "San Luis del Carmen", "San Miguel de Mercedes", "San Rafael", "Santa Rita", "Tejutla"];
var cuscatlan = ["Candelaria", "Cojutepeque", "El Carmen", "El Rosario", "Monte San Juan", "Oratorio de Concepción", "San Bartolomé Perulapía", "San Cristóbal", "San José Guayabal", "San Pedro Perulapán", "San Rafael Cedros", "San Ramón", "Santa Cruz Analquito", "Santa Cruz Michapa", "Suchitoto", "Tenancingo"];
var morazan = ["Arambala", "Cacaopera", "Chilanga", "Corinto", "Delicias de Concepción", "El Divisadero", "El Rosario", "Gualococti", "Guatajiagua", "Joateca", "Jocoaitique", "Jocoro", "Lolotiquillo", "Meanguera", "Osicala", "Perquín", "San Carlos", "San Fernando", "San Francisco Gotera", "San Isidro", "San Simón", "Sensembra", "Sociedad", "Torola", "Yamabal", "Yoloaiquín"];
var lalibertad = ["Antiguo Cuscatlán", "Chiltiupán", "Ciudad Arce", "Colón", "Comasagua", "Huizúcar", "Jayaque", "Jicalapa", "La Libertad", "Santa Tecla (Nueva San Salvador)", "Nuevo Cuscatlán", "San Juan Opico", "Quezaltepeque", "Sacacoyo", "San José Villanueva", "San Matías", "San Pablo Tacachico", "Talnique", "Tamanique", "Teotepeque", "Tepecoyo", "Zaragoza"];
var lapaz = ["Cuyultitán", "El Rosario (Rosario de La Paz)", "Jerusalén", "Mercedes La Ceiba", "Olocuilta", "Paraíso de Osorio", "San Antonio Masahuat", "San Emigdio", "San Francisco Chinameca", "San Juan Nonualco", "San Juan Talpa", "San Juan Tepezontes", "San Luis La Herradura", "San Luis Talpa", "San Miguel Tepezontes", "San Pedro Masahuat", "San Pedro Nonualco", "San Rafael Obrajuelo", "Santa María Ostuma", "Santiago Nonualco", "Tapalhuaca", "Zacatecoluca"];
var launion = ["Anamorós", "Bolívar", "Concepción de Oriente", "Conchagua", "El Carmen", "El Sauce", "Intipucá", "La Unión", "Lilisque", "Meanguera del Golfo", "Nueva Esparta", "Pasaquina", "Polorós", "San Alejo", "San José", "Santa Rosa de Lima", "Yayantique", "Yucuaiquín"];
var sanmiguel = ["Carolina", "Chapeltique", "Chinameca", "Chirilagua", "Ciudad Barrios", "Comacarán", "El Tránsito", "Lolotique", "Moncagua", "Nueva Guadalupe", "Nuevo Edén de San Juan", "Quelepa", "San Antonio del Mosco", "San Gerardo", "San Jorge", "San Luis de la Reina", "San Miguel", "San Rafael Oriente", "Sesori", "Uluazapa"];
var sansalvador = ["Aguilares", "Apopa", "Ayutuxtepeque", "Ciuddad Delgado", "Cuscatancingo", "El Paisnal", "Guazapa", "Ilopango", "Mejicanos", "Nejapa", "Panchimalco", "Rosario de Mora", "San Marcos", "San Martín", "San Salvador", "Santiago Texacuangos", "Santo Tomás", "Soyapango", "Tonacatepeque"];
var sanvicente = ["Apastepeque", "Guadalupe", "San Cayetano Istepeque", "San Esteban Catarina", "San Ildefonso", "San Lorenzo", "San Sebastián", "San Vicente", "Santa Clara", "Santo Domingo", "Tecoluca", "Tepetitán", "Verapaz"];
var santaana = ["Candelaria de la Frontera", "Chalchuapa", "Coatepeque", "El Congo", "El Porvenir", "Masahuat", "Metapán", "San Antonio Pajonal", "San Sebastián Salitrillo", "Santa Ana", "Santa Rosa Guachipilín", "Santiago de la Frontera", "Texistepeque"];
var sonsonate = ["Acajutla", "Armenia", "Caluco", "Cuisnahuat", "Izalco", "Juayúa", "Nahuizalco", "Nahulingo", "Salcoatitán", "San Antonio del Monte", "San Julián", "Santa Catarina Masahuat", "Santa Isabel Ishuatán", "Santo Domingo de Guzmán", "Sonsonate", "Sonzacate"];
var usulutan = ["Alegría", "Berlín", "California", "Concepción Batres", "El Triunfo", "Ereguayquín", "Estanzuelas", "Jiquilisco", "Jucuapa", "Jucuarán", "Mercedes Umaña", "Nueva Granada", "Ozatlán", "Puerto El Triunfo", "San Agustín", "San Buenaventura", "San Dionisio", "San Francisco Javier", "Santa Elena", "Santa María", "Santiago de María", "Tecapán", "Usulután"];
function get_municipios(depto) {
  $("#munic_pac").empty()
  if (depto == "San Salvador") {
    $("#munic_pac").select2({ data: sansalvador })
  } else if (depto == "La Libertad") {
    $("#munic_pac").select2({ data: lalibertad })
  } else if (depto == "Santa Ana") {
    $("#munic_pac").select2({ data: santaana })
  } else if (depto == "San Miguel") {
    $("#munic_pac").select2({ data: sanmiguel })
  } else if (depto == "Sonsonate") {
    $("#munic_pac").select2({ data: sonsonate })
  } else if (depto == "Usulutan") {
    $("#munic_pac").select2({ data: usulutan })
  } else if (depto == "Ahuachapan") {
    $("#munic_pac").select2({ data: ahuachapan })
  } else if (depto == "La Union") {
    $("#munic_pac").select2({ data: launion })
  } else if (depto == "La Paz") {
    $("#munic_pac").select2({ data: lapaz })
  } else if (depto == "Chalatenango") {
    $("#munic_pac").select2({ data: chalatenango })
  } else if (depto == "Cuscatlan") {
    $("#munic_pac").select2({ data: cuscatlan })
  } else if (depto == "Morazan") {
    $("#munic_pac").select2({ data: morazan })
  } else if (depto == "San Vicente") {
    $("#munic_pac").select2({ data: sanvicente })
  } else if (depto == "Cabanas") {
    $("#munic_pac").select2({ data: cabanas })
  }

}

function getPacientesCitados() {
  let fecha = document.getElementById('desde_orders').value;
  let sucursal = document.getElementById('sucursal').value;
  if (fecha == "") {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccionar fecha',
      showConfirmButton: true,
      timer: 1500
    });
    return false;
  }
  let form = document.createElement("form");
  form.target = "print_blank";
  form.method = "POST";
  form.action = "imprimir_citas_pdf.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "fecha-cita";
  input.value = fecha;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "sucursal";
  input.value = sucursal;
  form.appendChild(input);


  document.body.appendChild(form);//"width=600,height=500"
  form.submit();
  document.body.removeChild(form);
}

$(document).ready(function () {
  $("#patologias-ord").change(function () {
    $("#patologias-ord option:selected").each(function () {
      patologia = $(this).val();
      if (patologia == "No") {
        document.getElementById("photo").disabled = true;
        document.getElementById("photo").checked = false;
      } else if (patologia != "No") {
        document.getElementById("photo").disabled = false;
        document.getElementById("photo").checked = false;
      } else if (patologia == "") {
        document.getElementById("photo").disabled = false;
        document.getElementById("photo").checked = false;
      }

    });
  })
});

function validaAltoIndice() {
  let esfera_od = document.getElementById("odesferasf").value;
  let cilindros_od = document.getElementById("odcilindrosf").value;

  let esferas_oi = document.getElementById("oiesferasf").value;
  let cilindros_oi = document.getElementById("oicilindrosf").value;

  if (esfera_od > 4 || cilindros_od > 4 || esferas_oi > 4 || cilindros_oi > 4 || esfera_od < -4 || cilindros_od < -4 || esferas_oi < -4 || cilindros_oi < -4) {
    document.getElementById("alto-indice").checked = true;
    document.getElementById("alto-indice").disabled = false;
    document.getElementById("label-index").style.color = "green";
  } else {
    document.getElementById("alto-indice").checked = false;
    document.getElementById("alto-indice").disabled = false;
    document.getElementById("label-index").style.color = "gray";
  }
}

function modalImprimirActa(codigo, paciente) {
  $("#modal-actas").modal()
  document.getElementById("codigo-recep-orden").value = codigo;
  document.getElementById("pac-recep-orden").value = paciente;
}

//let btn_print = document.getElementById('btn-print-acta')

/* btn_print.addEventListener("click", function() {
  let receptor = $("input[type='radio'][name='receptor-acta']:checked").val();
  let nombre_receptor = document.getElementById('receptor-acta').value;
  let dui_receptor = document.getElementById('receptor-dui').value;
  if(receptor==undefined){
    Swal.fire({position: 'top-center',icon: 'error',title: 'Especificar tipo de receptor',showConfirmButton: true,
      timer: 1500
    });
    return false;
  }
  if(receptor=='tercero' && (nombre_receptor=='' || dui_receptor=='')){
    Swal.fire({position: 'top-center',icon: 'error',title: 'DUI y nombre de receptor son obligatorios',showConfirmButton: true,
      timer: 1500
    });
    return false;
  }
  
  let titular = document.getElementById('pac-recep-orden').value;
  let codigo_orden = document.getElementById('codigo-recep-orden').value;
  
  imprimirActa(nombre_receptor,dui_receptor,titular,codigo_orden,receptor)
}); */
function imprimirActa(nombre_receptor, dui_receptor, paciente, codigo, tipo_receptor) {
  let form = document.createElement("form");
  form.target = "blank";
  form.method = "POST";
  form.action = "imprimir_acta.php";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "codigo";
  input.value = codigo;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "paciente";
  input.value = paciente;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "receptor";
  input.value = nombre_receptor;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "dui-receptor";
  input.value = dui_receptor;
  form.appendChild(input);

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "tipo-receptor";
  input.value = tipo_receptor;
  form.appendChild(input);

  document.body.appendChild(form);//"width=600,height=500"
  form.submit();
  document.body.removeChild(form);
}

$(document).ready(function () {
  $("#instit").change(function () {
    $("#instit option:selected").each(function () {
      let sector = $(this).val();
      document.getElementById('titular_form').style.display = "none"
      if (sector == "CONYUGE") {
        document.getElementById('titular_form').style.display = "block"
      }

    });
  })
});

function comprobarExistenciaDUI(id) {
  let dui_pac = document.getElementById(id).value
  document.getElementById('mensaje_existe_dui').textContent = ""
  $.ajax({
    url: "../ajax/ordenes.php?op=comprobar_exit_DUI_pac",
    method: "POST",
    data: { dui_pac: dui_pac },
    cache: false,
    dataType: "json",
    success: function (data) {
      if (data.dui != "") {
        document.getElementById('mensaje_existe_dui').textContent = "El dui ya esta registrado"
        document.getElementById('dui_pac').classList.add('is-invalid')
      }
    }
  });
}

function get_table_acciones() {

  let codigo = $("#codigo_correlativo").val()
  let dui_paciente = $("#dui_paciente").val();
  $.ajax({
    url: "../ajax/ordenes.php?op=ver_historial_orden",
    method: "POST",
    data: { codigo: codigo, dui_paciente: dui_paciente },
    cache: false,
    dataType: "json",
    success: function (data) {
      $("#datatable_acciones_orden").html("");
      let filas = '';
      for (var i = 0; i < data.length; i++) {
        filas = filas + "<tr id='fila" + i + "'>" +
          "<td>" + data[i].id_accion + "</td>" +
          "<td>" + data[i].nombres + "</td>" +
          "<td>" + data[i].tipo_accion + "</td>" +
          "<td>" + data[i].observaciones + "</td>" +
          "<td>" + data[i].fecha + "</td>" +
          "</tr>";
      }
      $("#datatable_acciones_orden").html(filas);
    }
  });
}


function datatable_template(id, url, data, pagination = 50) {
  var table = $('#' + id).DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=" + url,
      type: "POST",
      data: data,
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": pagination,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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
/**
 *CODE PARA REPORTE DE LENTES 
 */
function get_reporte_lentes_resumen() {
  $('#dt_reporte_lente').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_reporte_lentes_resumen",
      type: "POST",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
   drawCallback: function(){
      var api = this.api();
      $(api.table().footer()).html(
        '<tr><td align="left"><span style="color:#2A3D3A;font-weight: bold">Total</span></td><td align="center" style="color:#2A3D3A;font-weight: bold">'+api.column(1,{page:'current'}).data().sum()+'</td> </tr>'
      )},
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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
function get_reporte_lentes() {
  $('#dt_reporte_lente_filtro').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_reporte_lentes",
      type: "POST",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    drawCallback: function(){
      var api = this.api();
      $(api.table().footer()).html(
        '<tr><td colspan="2" align="left"><span style="color:#2A3D3A;font-weight: bold">Total</span></td><td align="center" style="color:#2A3D3A;font-weight: bold">'+api.column(2).data().sum()+'</td> </tr>'
      )},
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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
//Filtrar datos BD

function filtrar_reporte_lentes() {
  let desdeFecha = document.getElementById('desde').value
  let hastaFecha = document.getElementById('hasta').value
  let sucursal = document.getElementById('sucursal').value
  //data send
  let data = {
    desdeFecha: desdeFecha,
    hastaFecha: hastaFecha,
    sucursal: sucursal
  }
  //Validation 
  if (desdeFecha !== "" && hastaFecha !== "") {
    //Filtrado datatable
    $('#dt_reporte_lente_filtro').DataTable({
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: ['excelHtml5'],
      "ajax": {
        url: "../ajax/ordenes.php?op=get_reporte_lentes",
        type: "POST",
        dataType: "json",
        data: data,
        error: function (e) {
          console.log(e.responseText);
        },
      },
      drawCallback: function(){
        var api = this.api();
        $(api.table().footer()).html(
          '<tr><td colspan="2" align="left"><span style="color:#2A3D3A;font-weight: bold">Total</span></td><td align="center" style="color:#2A3D3A;font-weight: bold">'+api.column(2,{page:'current'}).data().sum()+'</td> </tr>'
        )},
      "bDestroy": true,
      "responsive": true,
      "bInfo": true,
      "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
        "sSearch": "Buscar:",
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
  } else if ((desdeFecha == "" && hastaFecha !== "") || (desdeFecha !== "" && hastaFecha == "")) {
    //Alerta de rango de fecha
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: '¡Por favor, especificar un rango de fecha!',
      showConfirmButton: true,
      timer: 3500
    });
    return 1;
  }
  //Filtrado datatable
  $('#dt_reporte_lente_filtro').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_reporte_lentes",
      type: "POST",
      dataType: "json",
      data: data,
      error: function (e) {
        console.log(e.responseText);
      },
    },
    drawCallback: function(){
      var api = this.api();
      $(api.table().footer()).html(
        '<tr><td colspan="2" align="left"><span style="color:#2A3D3A;font-weight: bold">Total</span></td><td align="center" style="color:#2A3D3A;font-weight: bold">'+api.column(2,{page:'current'}).data().sum()+'</td> </tr>'
      )},
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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
/**
 *@Autor: Jose Deodanes 
 *@Funcion: Para desactivar por defecto valores de ingreso manual y ediccion especial
 *@ 2023
 */
function disabledElement(type = ""){
  //Select 
  if(type == "manual"){
    document.getElementById('select_cita').style.display = "none"
    document.getElementById('select_manual').style.display = "block"
  }else if(type == "edit_special"){
    document.getElementById('select_manual').style.display = "none"
    document.getElementById('select_cita').style.display = "block"
  }
}
/**
 *@Autor: Jose Deodanes 
 *@Funcion: Para activar ingreso manual
 *@ 2023
 */
function AllowManualInput(){
  let checkInput = document.getElementById('customSwitch1').checked
  let contentForm = document.getElementById('show_form_manual')
  //Tble citas
  let contentTable = document.getElementById('tables_cita')
  //Control display form
  if(checkInput){
    //Show ingreso manual
    //Si es una orden digitada en Callcenter
    let id_cita = $("#id_cita_ord").val()
    if(id_cita != 0){
      Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: '¡Por favor, comunicarse con CallCenter!',
        showConfirmButton: true,
        timer: 3500
      });
      //Check disabled
      document.getElementById('customSwitch1').checked = false
      //Salidad forzada
      return 0;
    }
    contentForm.style.display = "block"
    contentTable.style.display = "none"
    //Hide buscador de citas
    document.getElementById('btnBuscarCitado').style.display = "none"
    disabledElement('manual')
  }else{
    contentForm.style.display = "none"
    contentTable.style.display = "block"
    //show buscador de citas
    document.getElementById('btnBuscarCitado').style.display = "block"
  }
}
/**
 *@Autor: Jose Deodanes 
 *@Funcion: Para activar ediccion espcial
 *@ 2023
 */
function AllowEdicionSpecial(){
  let checkInput = document.getElementById('ediccion_orden_citas').checked
  let contentForm = document.getElementById('show_form_manual')
  //Tble citas
  let contentTable = document.getElementById('tables_cita')
  //Control display form
  if(checkInput){
    //Show ingreso manual
    contentForm.style.display = "block"
    contentTable.style.display = "none"
    disabledElement('edit_special')
  }else{
    contentForm.style.display = "none"
    contentTable.style.display = "block"
  }
}
/**
 * 
 * PERMISOS PARA INGRESO MANUAL Y EDICCION ESPÉCIAL
 * 
 */
function verify_permit_manual(){
  const permiso_manual = names_permisos.includes("ingreso_manual") //return true
  //show buscador de citas
  document.getElementById('btnBuscarCitado').style.display = "block"
  //disabled checkManual
  document.getElementById('customSwitch1').checked = false;
  if (permiso_manual) {
    document.getElementById('radio_button_orden').style.display = "block"
  } else {
    document.getElementById('radio_button_orden').style.display = "none"
  }
}

/**
 *  CODE EDICION ESPECIAL EN ORDEN CITAS 
 */
//Ediccion espcial mostrar el btn
function verify_permit_edicion_especial(){
  //disabled checkEdiccion especial
  document.getElementById('ediccion_orden_citas').checked = false;
  if (names_permisos.includes('edit_especial_orden')) {
    document.getElementById('btn_edit_especial').style.display = 'block';
    //Ingreso manual
    document.getElementById('radio_button_orden').style.display = 'none';
  } else {
    document.getElementById('btn_edit_especial').style.display = 'none';
    //Ingreso manual
    document.getElementById('radio_button_orden').style.display = 'block';
  }
}


/***
 * FILTRADO DE CATEGORIA DE LENTE
 * NEW CODE : 17/01/2023
 * function filtrar
 */

function filtrar_lentes_category(){
  let f_desde = document.getElementById('f_desde').value
  let f_hasta = document.getElementById('f_hasta').value
  let estado_ordenes = document.getElementById('filter-estado').value
  if((f_desde == "" && f_hasta != "") || (f_desde != "" && f_hasta == "")){
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: '¡Por favor, debe de especificar rango de fecha!',
      showConfirmButton: true,
      timer: 3500
    });
    //Default
    document.getElementById('f_desde').value = ""
    document.getElementById('f_hasta').value = ""
  }
  //Filtrar por fecha
  $('#dt_reporte_lente').DataTable({
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax": {
      url: "../ajax/ordenes.php?op=get_reporte_lentes_resumen",
      type: "POST",
      data: {desde: f_desde,hasta: f_hasta, estadoOrdenes: estado_ordenes},
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    drawCallback: function(){
      var api = this.api();
      $(api.table().footer()).html(
        '<tr><td colspan="3" align="left"><span style="color:#2A3D3A;font-weight: bold">Total</span></td><td align="center" style="color:#2A3D3A;font-weight: bold">'+api.column(1,{page:'current'}).data().sum()+'</td> </tr>'
      )},
    "bDestroy": true,
    "responsive": true,
    "bInfo": true,
    "iDisplayLength": 50,//Por cada 10 registros hace una paginación
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
      "sSearch": "Buscar:",
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
/**
 * FUNCTION PARA REEMPRIMIR VIÑETAS O RECETAS DE ORDEN
 */
//Validacion de permiso para reemprimir
function checkPermisoReimpReceta(){
  let siPermiso = names_permisos.includes("reimpresion_receta")
  try{
    if(siPermiso){
      document.getElementById('reimpresioReceta').style.display = 'block';
    }else{
      document.getElementById('reimpresioReceta').style.display = 'none';
    }
  }catch(err){
    console.log('')
  }
}
checkPermisoReimpReceta()
//Funcion para reemprimir
 function reemprimirVinetas(){
  console.log('Reemprimir')
  const dui_veterano = document.getElementById('dui_pac_t').textContent;
  if(dui_veterano === ""){
    Swal.fire({
      position: 'top-center',
      icon: 'warning',
      title: 'Esta función no esta disponible al crear la orden!',
      showConfirmButton: true,
      timer: 9500
    });
    return 0;
  }
  var form = document.createElement("form");
  form.target = "_blank";
  form.method = "POST";
  form.action = "reemprimirVineta.php";

  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "dui_vet";
  input.value = dui_veterano;
  form.appendChild(input);

  document.body.appendChild(form)
  form.submit();
  document.body.removeChild(form);
}

init();