<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color:#343a40;color: white">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color:white"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  SUCURSAL:&nbsp; <span style="text-transform:uppercase"><?php echo $_SESSION["sucursal"]; ?></span>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <li class="nav-item">
      <a class="nav-link" data-slide="true" href="logout.php" role="button">
        <i class="fas fa-sign-out-alt" style="background-color: yelllow"></i>
      </a>
    </li>
  </ul>
</nav>