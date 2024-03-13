<?php

$incorrecta = false;

if(isset($_POST['action']) && $_POST['action'] == "login"){
    $pass = base64_encode($_POST['password']);
    if($pass == 'aW5zdGFsYWNpb25lczIwMDA='){
        session_start();
        $_SESSION['LOGGED'] = true;
        header('Location: index.php');
        die();
    } else{
        $incorrecta = true;
    }
}

include("head.php");

?>

<form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
    <input type="hidden" name="action" value="login">
    <div class="container">
        <div class="card mt-5 text-center shadow">
            <div class="card-body">
                <h5 class="card-title fs-2 my-4"><strong>INICIO DE SESIÓN</strong></h5>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <div class="text-start my-3">
                            <label for="password" class="form-label fs-5">Contraseña</label>
                            <input type="password" class="form-control form-control-sm" id="password" name="password">
                        </div>
                        <?php
                        if($incorrecta){
                            ?>
                            <div class="alert alert-danger my-3 p-2 text-start" role="alert">
                                ¡Contraseña incorrecta!
                            </div>
                            <?php
                        }
                        ?>
                        <button type="submit" class="btn btn-primary px-4 my-3">Enviar</button>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
        </div>
    </div>
</form>
