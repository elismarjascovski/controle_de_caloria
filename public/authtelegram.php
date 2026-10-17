<?php
include_once '../configbot.php';
require_once '../vendor/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}

function checkTelegramAuthorization($auth_data) {
    $check_hash = $auth_data['hash'];
    unset($auth_data['hash']);
    $data_check_arr = [];

    foreach ($auth_data as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }

    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);

    $secret_key = hash('sha256', TOKEN, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);

    if (strcmp($hash, $check_hash) !== 0) {
        return false;
    }

    if ((time() - $auth_data['auth_date']) > 86400) {
        return false;
    }

    return $auth_data;
}

$auth_data = $_GET;
$user_data = checkTelegramAuthorization($auth_data);

if ($user_data) {
    $dbPessoa = new \app\models\portal\Pessoa();
    $dbUser = new \app\models\portal\User();
    $usuarioLogado = $dbUser->getDadosUsuarioLogado();
    $telegram_id = $user_data['id'];
    $dbPessoa->vincularTelegram($telegram_id, $usuarioLogado->id_pessoa);
    header("Location: /perfil");
    exit;
} else {
    header("Location: /erro/telegram");
    exit;
}
?>