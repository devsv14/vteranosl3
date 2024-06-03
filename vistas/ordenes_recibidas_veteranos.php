<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["user"])){
$categoria_usuario = $_SESSION["categoria"];
date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H-i-s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); 
    require_once('../modelos/Ordenes.php');
    require_once('../modales/modal_acciones_optica.php');
    require_once('../modales/nueva_orden_lab.php');
    
?>
<style>
  .buttons-excel{
    margin: 2px;
    max-width: 150px;
  }
</style>
 <script src="../plugins/exportoExcel.js"></script>
 <script src="../plugins/keymaster.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>

  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" id="correlativo_acc_vet">
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"];?>"/>
      <input type="hidden" name="sucursal" id="sucursal" value="<?php echo $_SESSION["sucursal"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">
      </div>


        <button class="btn btn-info barcode_actions_vets float-right acc-acciones-opt" data-acciones="ingreso_orden_optica" data-toggle="modal" data-target="#modal_acciones_veteranos" style="border: solid 1px #1f2e50"><i class="fas fa-download"></i> RECIBIR</button>
        <h5 style="font-size: 16px; text-align: center;font-weight: bold;color: blue">ORDENES RECIBIDAS</h5>
        <table width="100%" class="table-hover table-bordered" id="ordenes_recibidas_data"  data-order='[[ 0, "desc" ]]'> 
              
          <thead class="style_th bg-dark" style="color: white">
           <th>ID</th>
           <th>Fecha rec.</th>
           <th>Paciente</th>
           <th>DUI</th>
           <th>Sucursal</th>
           <th>Detalles</th>
           <th>Imp. acta</th>
         </thead>
         <tbody class="style_th"></tbody>
       </table>

    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">
   <!--Modal Imagen Aro-->
   <div class="modal" id="imagen_aro_orden">
    <div class="modal-dialog" style="max-width: 55%">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
          <span><b>Código: </b></span><span id="cod_orden_lab"></span>&nbsp;&nbsp;&nbsp;<span><b>Paciente: </b></span><span id="paciente_ord_lab"></span>
          <div style="  background-size: cover;background-position: center;display:flex;align-items: center;">
            <img src="" alt="" id="imagen_aro_v" style="width: 100%;border-radius: 8px;">
          </div>          
        </div> 
      </div>
    </div>
  </div>
  <input type="hidden" id="cat_data_barcode" value="recibir_veteranos">
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>

<!--modal actas-->
<div class="modal" id="modal-actas">
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-info" style="padding:5px">
        <h4 class="modal-title w-100 text-center" style="font-size:16px">ENTREGA DE ACTAS</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h5 style="text-align:center;font-size:16px"><span style="color:red">NOTA: </span> Al imprimir la siguiente acta la orden pasa a estado de 'ENTREGADO A PACIENTE'</h5>
        <h5 style="text-align:center;font-size:18px"><u>La siguiente acta será entregada a:</u></h5>

          <div class="col-sm-12 shadow-sm" style="  display: flex; justify-content: center;align-items: center;margin:10px">

            <div class="form-group clearfix" style="margin:6px">

              <div class="icheck-success d-inline">
                <input type="radio" name="receptor-acta" id="ben-acta" class="chk-recept" value="beneficiario">
                <label for="ben-acta">Beneficiario
                </label>
              </div>

              <div class="icheck-warning d-inline" style="margin:6px">
                <input type="radio" name="receptor-acta" id="terc-acta" class="chk-recept" value="tercero">
                <label for="terc-acta">Tercero
                </label>
              </div>
            </div>

          </div>
          <div class="row " style="display:none" id="receptores-section">

            <div class="col-sm-8">
            <label for="inputPassword4">Receptor*</label>
              <input type="text" class="form-control clear_i" id="receptor-acta" name="receptor-acta" placeholder="nombre completo de receptor">
            </div>
            <div class="col-sm-4">
            <label for="inputPassword4">DUI*</label>
              <input type="text" class="form-control clear_i" id="receptor-dui" name="receptor-dui" placeholder="DUI receptor">
            </div>

          </div>
      </div><!-- Fin body modal -->
       <input type="hidden" id="codigo-recep-orden">
       <input type="hidden" id="pac-recep-orden">
       <input type="hidden" id="dui-acta">
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-block" id="btn-print-acta"><i class="fas fa-file-pdf"></i> Imprimir</button>
      </div>

    </div>
  </div>
</div>
<!-- ./wrapper -->
<?php 
require_once("links_js.php");
?>
<script type="text/javascript" src="../js/cleave.js"></script>
<script type="text/javascript" src="../js/acciones_optica.js?v=<?php echo(rand()); ?>"></script>
<script type="text/javascript" src="../js/ordenes.js?v=<?php echo(rand()); ?>"></script>
<script type="text/javascript" src="../js/actas.js?v=<?php echo(rand()); ?>"></script>

<script>
  new Cleave('#receptor-dui', {
  delimiter: '-',
  blocks: [8,1],
  uppercase : true
});
</script>

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  
} ?>
