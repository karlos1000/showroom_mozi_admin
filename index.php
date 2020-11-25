<?php
include_once 'common/screenPortions.php';
?>
<!DOCTYPE html>
<html lang="es">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Showroom</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<body>
<section id="inicio">
<div class="container">
        <div class="row">
            <div class="col-md-5"></div>
    <figure>    	
    </figure>
     <div class="col-md-5"></div>
        </div>
</div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-9">
				<div class="logo"><a href="index.php"><img src="images/logo.png" /></a></div>
                <div class="login-form">
                    <form method="post" action="checklogin.php" role="login">
                        <!--<img src="images/logo.jpg" class="img-responsive" alt="" />-->

                        <div class="form-group col-md-12">
                            <label class="col-md-3" for="email"><img src="images/iconos/perfil_gris.png"></label>
                            <input class="col-md-9" type="email" class="form-control input-lg" name="usr_email" id="usr_email"  placeholder="Correo electrónico" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-3" for="pwd"><img src="images/iconos/contrasena_icon.png"></label>
                            <input class="col-md-9" type="password" class="form-control input-lg" name="usr_pass" id="usr_pass" placeholder="Contraseña" required>
                        </div>

                        <div class="pwstrength_viewport_progress"></div>
                        <button type="submit" name="aceptar" class="btn btn-lg btn-primary btn-block">INICIAR SESIÓN </button>

                        <div class="cont_error">
                                <?php if (isset($_GET['login'])){ ?>
                                    <?php if ($_GET['login'] == "error"){ ?>
                                            <div class="alert alert-danger">Datos Incorrectos intenta nuevamente</div>
                                    <?php }
                                           if ($_GET['login'] == "cliente"){ ?>
                                            <div class="alert alert-danger">Estimado cliente para utilizar nuestro servicio descargue la aplicacion en su movil.</div>
                                    <?php }
                                        if ($_GET['login'] == "inactivo"){ ?>
                                            <div class="alert alert-danger">Su cuenta ha sido dada de baja.</div>
                                    <?php }
                                    ?>
                                <?php } ?>
                        </div>
                    </form>
                </div>
				<div class="img-login">
             		 <!-- <img src="images/iconos/img_login.png"> -->
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
  </section>
   <footer>
    <div class="panel-footer">
        <?php echo getPiePag(); ?>
    </div>
   </footer>

  <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
