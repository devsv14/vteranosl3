<!DOCTYPE html>
<html lang="es">
<?php
require_once("../config/conexion.php");
if(isset($_SESSION["user"])){
require_once("../vistas/links_plugin.php");
require_once("modales/listarCitas.php");
?>

<head>
    <script>
        window.addEventListener("load", function() {
       // Ocultar el loader
       document.getElementById("loader").style.display = "none";
    });
    </script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Citas</title>
    
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/main.min.css">
    <style>
    option.suc-tooltip {
    position: relative;
}
 
option.suc-tooltip:hover::after {
    content: attr(data-title);
    background-color: #8fbc8f;
    color: #fff;
    padding: 8px;
    border-radius: 4px;
    font-size: 12px;
    line-height: 14px;
    display: block;
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    z-index: 1;
}

#cupaciones-list{

        background: white;
        color:black;

      }

#loader {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 520px;
  height: 520px;
  background-color: transparent;
  z-index: 10000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.spinner {
  border: 16px solid #f3f3f3;
  border-top: 16px solid #3498db;
  border-radius: 50%;
  width: 200px;
  height: 200px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
  

    </style>
</head>

<body>

<div class="wrapper">

<div id="loader">
    <div class="spinner"></div>
</div>
<!-- top-bar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color:#343a40;color: white">
    <!-- Left navbar links -->
    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color:white"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <h5 style="text-align:center;">AGENDAR CITAS</h5>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-slide="true" href="../vistas/logout.php" role="button" >
          <i class="fas fa-sign-out-alt" style="background-color: yelllow"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php'); ?>
  <div class="content-wrapper">
   
    <button class="btn btn-outline-primary btn-xs" style="margin-left:25px"><i class="fas fa-calendar"></i> Citas diarias</button>
    <button class="btn btn-outline-dark btn-xs" style="margin-left:25px" onClick="showModalGestion()"><i class="fas fa-cog"></i> Gestion de citas</button>
    <div class="container">
        <div id="calendar"></div>
    </div>

 <!--MODAL GESTION CITAS -->

<div class="modal" id="gestion-citas">
  <div class="modal-dialog" style="max-width:85%">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary" style="padding:10px;">
        <h4 class="modal-title  w-100 text-center position-absolute" style="font-size:16px">GESTIONAR CITAS</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table-bordered table-hover" width="100%" id="data-gest-citas" style="font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align: center">

        <thead style="color:white;" class='bg-dark'>
            <tr>
                <th style="width:35%">Paciente</th>
                <th style="width:10%">DUI</th>
                <th style="width:15%">Tipo Paciente</th>
                <th style="width:10%">Sector</th>
                <th style="width:10%">fecha</th>
                <th style="width:10%">Sucursal</th>
                <th style="width:10%">Editar</th>
            </tr>
        </thead>

        </table>
      </div>


    </div>
  </div>
</div>
 
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog" style="max-width:95%">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title" id="titulo" style="color: white">Registro citas</h5>
                    <span aria-hidden="true" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <form id="formulario" autocomplete="off">
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <div class="row">

                        <div class="col-sm-12">
                        <div class="icheck-success d-inline">
                            <input type="radio" name="chk-instit" id="inabve" class="chk-ins" value="inabve">
                            <label for="inabve">INABVE
                            </label>
                        </div>

                        <div class="icheck-warning d-inline" style="margin:6px">
                            <input type="radio" name="chk-instit" id="brf" class="chk-ins" value="brf">
                            <label for="brf">BRF
                            </label>                           
                        </div>
                        </div>
                        
                        <div class="col-md-2">
                        <label for="usuario-lente">Licitacion</label>
                        <select class="form-control" id="licitacion" name="licitacion">
                            <option value='0'>Seleccionar...</option>
                            <option value="l2">L2</option>
                            <option value="l1">L1</option>
                        </select>
                        </div>

                        <div class="col-sm-4" style='text-align:left'>
                        <label for="title">Titular</label>
                            <input id="paciente-vet" type="search" class="form-control inp-citas" name="paciente-vet"
                            onkeyup = "buscarPaciente(this.value)"
                            list="options-pacientes"
                            autocomplete="off"
                            onchange='clearInputs()'
                            >
                            <datalist id="options-pacientes"></datalist>
                        </div>

                        <div class="col-md-2">
                            <label for="dui">Tipo Paciente</label>
                            <select class="form-control" id="tipo-pac" name="tipo-pac">
                            <option value="0">Seleccionar...</option>
                            <option value="Veterano">Veterano</option>
                            <option value="Ex-Combatiente">Ex-Combatiente</option>
                            <option value="Conyuge">Conyuge</option>
                            <option value="Designado">Designado</option>
                            <option value="BRF">BRF</option>
                        </select>
                        </div>

                        <div class="col-sm-4" style='text-align:left'>
                        <label for="title">Beneficiarios</label>
                            <input id="beneficiarios-vet" type="search" class="form-control inp-citas i-citas" name="beneficiarios-vet"
                            list="options-beneficiarios"
                            autocomplete="off"
                            onchange = 'getDataBeneficiarios(this.value)'
                            >
                            <datalist id="options-beneficiarios"></datalist>
                        </div>
                            
     <!--                    <div class="col-md-4">
                            <label for="title">Paciente</label>
                            <input id="paciente-vet" type="text" class="form-control inp-citas" name="paciente-vet">
                        </div> -->



                        <div class="col-md-2">
                            <label for="dui">DUI</label>
                            <input id="dui-vet" type="text" class="form-control i-citas" name="dui-vet">
                        </div>

                        <div class="col-md-2">
                            <label for="dui">Telefono</label>
                            <input id="telefono-pac" type="text" class="form-control i-citas" name="telefono-pac">
                        </div>
                        <div class="col-md-2">
                            <label for="dui">Tel. opcional</label>
                            <input id="telefono-opcional" type="text" class="form-control i-citas" name="telefono-opcional" val="*">
                        </div>

                        <div class="col-md-2">
                            <label for="dui">Edad</label>
                            <input id="edad-pac" type="number" class="form-control i-citas" name="edad-pac" min="1" max="125">
                        </div>

                        <div class="col-md-4">
                        <label for="usuario-lente">Genero</label>
                        <select class="form-control inp-citas i-citas" id="genero-pac" name="genero-pac">
                            <option>Seleccionar...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                        </div> 
                        
                        <div class="col-md-4">
                        <label for="dui">Sector</label>
                        <select class="form-control i-citas" id="sector-pac" name="sector-pac">
                            <option value="0">Seleccionar...</option>
                            <option value="FMLN">FMLN</option>
                            <option value="FAES">FAES</option>
                            <option value="BRF">BRF</option>
                        </select>
                        </div>


                        <div class="col-md-4">
                            <label for="ocupacion-pac">Ocupación</label>
                            <input id="ocupacion-pac" type="text" class="form-control inp-citas ocupaciones-list i-citas" name="ocupacion-pac" multiple list="ocupaciones-list">
                        </div>
                        <datalist id="ocupaciones-list">
                        <option value="OFICIOS DOMÉSTICOS"></option>
                        <option value="JORNALERO"></option>
                        <option value="AGRICULTOR"></option>
                        <option value="CARPINTERO"></option>
                        <option value="APICULTOR"></option>
                        <option value="GANADERO"></option>
                        <option value="PANADERO"></option>
                        <option value="OFICIOS VARIOS"></option>
                        <option value="AMA DE CASA"></option>
                        <option value="GRANJERO"></option>
                        <option value="COMERCIANTE"></option>
                        <option value="EMPLEADO"></option>
                        <option value="SEGURIDAD PÚBLICA"></option>
                        <option value="ESTUDIANTE"></option>
                        <option value="MOTORISTA"></option>
                        <option value="SASTRE"></option>
                        <option value="ARTESANA"></option>
                        </datalist>

                        <div class="col-sm-12" id="datos-titular" style="display:none">
                        <div class="col-md-7" id="nombre-tit">
                            <label for="ocupacion-pac">Veterano/Ex-combatiente titular *</label>
                            <input id="vet-titular" type="text" class="form-control inp-citas i-citas" placeholder="Veterano/Ex-combatiente titular" name="vet-titular">
                        </div>

                        <div class="col-md-5" id="dui-tit">
                            <label for="ocupacion-pac"> DUI Veterano/Ex-combatiente titular *</label>
                            <input id="dui-titular" type="text" class="form-control inp-citas i-citas" placeholder="DUI" name="dui-titular">
                        </div>

                        </div>

                        <div class=" form-group col-sm-4 select2-purple">
                        <label for="" class="etiqueta">Departamento </label> <span id="departamento_pac_data" style="color: red"></span>
                        <select class="select2 form-control clear_input" id="departamento_pac" name="departamento_pac" multiple="multiple" data-placeholder="Seleccionar Departamento" data-dropdown-css-class="select2-purple" style="width: 100%;height: ">              
                            <option value="0">Seleccione Depto.</option>
                            <option value="San Salvador">San Salvador</option>
                            <option value="La Libertad">La Libertad</option>
                            <option value="Santa Ana">Santa Ana</option>
                            <option value="San Miguel">San Miguel</option>
                            <option value="Sonsonate">Sonsonate</option>
                            <option value="Usulutan">Usulután</option>
                            <option value="Ahuachapan">Ahuachapán</option>
                            <option value="La Union">La Unión</option>
                            <option value="La Paz">La Paz</option>
                            <option value="Chalatenango">Chalatenango</option>
                            <option value="Morazan">Morazán</option>
                            <option value="Cuscatlan">Cuscatlán</option>
                            <option value="San Vicente">San Vicente</option>
                            <option value="Cabanas">Cabañas</option>
                        </select>               
                        </div>

                        <div class=" form-group col-sm-4 select2-purple">
                            <label for="" class="etiqueta">Municipio </label> <span id="munic_pac_data" style="color: red"></span>
                            <select class="select2 form-control clear_input" id="munic_pac" name="munic_pac" multiple="multiple" data-placeholder="Seleccionar Municipio" data-dropdown-css-class="select2-purple" style="width: 100%;height: ">
                                <option value="0">Seleccione Municipio.</option>
                            </select>               
                       </div>

                       <div class="col-md-3">
                            <label for="start">Fecha</label>
                            <input class="form-control" id="fecha-cita" type="date" name="fecha-cita" onchange="gethorasDisponiblesFecha(this.value)"  onclick="gethorasDisponiblesFecha(this.value)">
                        </div> 

                        <div class="col-md-3">
                            <label for="start">Sucursal</label>
                            <select class="form-control suc-tooltip" id="sucursal-cita" name="sucursal-cita"  onchange="gethorasDisponiblesSucursal(this.value)"  onclick="gethorasDisponiblesSucursal(this.value)">
                           
                            </select>
                        </div>
                 

                        <div class="col-md-2 select2-primary">
                            <label for="hora" >Hora</label>
                            <select class="select2 form-control clear_input" id="hora" name="hora" multiple="multiple" data-placeholder="Seleccionar hora" data-dropdown-css-class="select2-primary" style="width: 100%;height: ">
                            <option value="0">Seleccione hora...</option>
                            </select> 
                        </div>

                        <input type="hidden" id="start">
                        <input type="hidden" id="input-ed">
                        </div>
                    </div>
                    <input type="hidden" id="id_citado">
                    <input type="hidden" id="id_usuario_vet" name="id_usuario_vet" value="<?php echo $_SESSION["id_user"]?>">
                    <input type="hidden" id="usuario-lente" name="usuario-lente" value="0">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark btn-block" id="btnAccion">Guardar</button>
                       
                    </div>
                </form>
                <button class="btn btn-outline-info btn-flat" id="btnEdit" style="display:none;" onClick="editarCitaSendData()"><i class="fas fa-edit"></i> EDITAR</button>
            </div>
        </div>
    </div>




</div>
</div>
<?php 
require_once("../vistas/links_js.php");
?>
  
    <script src="<?php echo base_url; ?>Assets/js/main.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/es.js"></script>
    <script>
        const base_url = '<?php echo base_url; ?>';
    </script>
   
    <script src="<?php echo base_url; ?>Assets/js/app.js?v=<?php echo(rand()); ?>"></script>
    <script src='../js/cleave.js?v=<?php echo(rand()); ?>'></script>
    <script src='../js/citados.js?v=<?php echo(rand()); ?>'></script>

 
    <script>
        let telefono = new Cleave('#telefono-pac', {
        delimiter: '-',
        blocks: [4,4],
        uppercase: true
        });
        
        let dui = new Cleave('#dui-vet', {
        delimiter: '-',
        blocks: [8,1],
        uppercase: true
        });
    
        let telefono_op = new Cleave('#telefono-opcional', {
        delimiter: '-',
        blocks: [4,4],
        uppercase: true
        });

        let duitit = new Cleave('#dui-titular', {
        delimiter: '-',
        blocks: [8,1],
        uppercase: true
        });
    </script>

</body>

</html>

<?php } else{
echo "Acceso denegado";
  } ?>