<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro_acesso'] = 1;
    header('Location: /');
    exit();
}

?>