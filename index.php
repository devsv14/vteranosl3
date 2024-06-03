<?php
 require_once("config/conexion.php");   

  if(isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
    require_once("modelos/Login.php");
    $login = new Login();
    $login->login_users();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INABVE-AVPLUS</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .log{
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .log-div{
      border-radius: 8px;
    }
  </style>
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>

    var Toast = Swal.mixin({
      toast: true,
      position: 'top-center',
      showConfirmButton: false,
      timer:2500
    });

  function alert_log(){
    toastr.warning('Existen campos vacios!!')
  }

  function error_log(){
    toastr.error('Verificar que sus credenciales posean los permisos para iniciar sesión!!')
  }

</script>
</head>
<body class="hold-transition login-page" style="background-color:#292F33;">

  <?php
    if(isset($_GET["m"])) {               
      switch($_GET["m"]){
      case "1";
    ?>
    <script> 
        error_log();
        </script>
        <?php
          break;
        case "2";
        ?>
        <script> 
        alert_log();
        </script>
      <?php
    break;
    }

  }
  ?>

<div class="login-box">
  <!-- /.login-logo -->
  <div class="card log log-div">
    <div class="card-body login-card-body" style="border-radius: 8px !important">
      <div style="border-bottom: 5px !important;display:flex;">
        <img src="dist/img/inabve.jpg" alt="" class="img-responsive log" width="150" height="145" align="center" style="margin-top: 15px !important">
        <img src="dist/img/logo_avplus.jpg" alt="" class="img-responsive log" width="150" height="80" align="center" style="margin-top: 50px !important">
      </div>
      <form method="post" style=" margin-top:10px">        
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Usuario" style="border: 1px solid #001a34" name="usuario">
          <div class="input-group-append">
            <div class="input-group-text" style="border: 1px solid #001a34">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" style="border: 1px solid #001a34" name="pass">
          <div class="input-group-append">
            <div class="input-group-text" style="border: 1px solid #001a34">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
        <select class="form-control" name="sucursal-user">
          <?php 
          echo $sucursales;
          ?>
        </select>
        </div>
      <div class="form-group">
        <input type="hidden" name="enviar" class="form-control" value="si">       
      </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" style="background-color: #004883;border-radius: 0px">INICIAR SESIÓN</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<!-- jQuery -->
<!-- jQuery -->

</body>
</html>
