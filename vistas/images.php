<?php
function showFiles($path) {
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {
            if(is_dir($path.$current)) {
                showFiles($path.$current.'/');
            }
            else {
                $files[] = $current;
            }
        }
    }
    echo '<h6>'.$path.'</h6><hr>';
    echo '<div class="row">';
    for ($i=0; $i<count( $files ); $i++) {
        echo '<div class="col-xs-12 col-lg-2 text-center" style="margin-bottom:10px;">';
        echo '<span class="preview" style="width:160px;height: 160px;margin-bottom:7px;"> ';
        echo '<img src="images/'.$files[$i].'" style="max-width:160px;max-height: 160px;" />';
        echo '</span>';
        echo '<a class="btn btn-danger" href="images.php?remove='.$files[$i].'"><i class="fas fa-trash-alt"></i> Eliminar</a>';
        echo '</div>';
    }
    echo '</div>';
}

if (isset($_GET['remove'])) {
    if (file_exists('images/'.$_GET['remove'])) {
        unlink('images/'.$_GET['remove']);
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="css/styles.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="js/dropzone.js"></script>
<script src="js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />

<title>Dropzone múltiple subida de archivos</title>

    </head>
    <body class="d-flex flex-column h-100">
    
    <header>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">BaulPHP</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Portada <span class="sr-only">(current)</span></a>
        </li>
      </ul>
      <form class="form-inline mt-2 mt-md-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Busqueda</button>
      </form>
    </div>
    </div>
  </nav>
</header>

<!-- Begin page content -->
<hr>
<br>
<main>
<div class="container">

    <div class="row">
      <div class="col-md-12">
        <hr>
	  </div>
    </div>
    
    <div class="row">
    <div class="col-md-12">
     <div class="card">
        <h5 class="card-header">Imagenes subidas</h5>
        <div class="card-body">
          <div id="content" class="col-lg-12">
            <?php showFiles('images/'); ?>
        </div> 
        </div>
    </div>  
    
    </div>
        
    </div>

<footer>
      <hr>
        <div class="copyright"> &copy; 2013 - <?=date("Y")?> <a href="https://baulcode.com" target="_blank">baulcode</a>. All rights reserved </div>
        <div class="footerlogo"><a href="https://baulcode.com" target="_blank"></a> </div>
</footer>

</div>
</main>
</body>
</html>
