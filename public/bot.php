<?php
include_once '../configbot.php';

$telegramToken = TOKEN;
$apiUrl = "https://api.telegram.org/bot{$telegramToken}/";

function sendMessage($chatId, $message, $parseMode = 'Markdown') {
    global $apiUrl;
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => $parseMode
    ];
    file_get_contents($apiUrl . "sendMessage?" . http_build_query($data));
}

function sendOptions($chatId, $text, $options) {
    global $apiUrl;
    $keyboard = ["inline_keyboard" => $options];
    $data = [
        "chat_id" => $chatId,
        "text" => $text,
        "reply_markup" => json_encode($keyboard)
    ];
    file_get_contents($apiUrl . "sendMessage?" . http_build_query($data));
}

function verificaPessoaCadastrada($idPessoa) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoa,
        "function" => 'verificar_pessoa'
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function listadeAlimentos($alimento, $idPessoa) {
    $apiEndpoint = SITE_URL . "/listaAlimentos.php";
    $data = [
        "alimento" => $alimento,
        "id_pessoa" => $idPessoa
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function listadeTipoRefeicao($idPessoa) {
    $apiEndpoint = SITE_URL . "/listaTipoRefeicao.php";
    $data = [
        "id_pessoa" => $idPessoa
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function inserirRefeicao($idPessoa, $quantidade) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoa,
        "quantidade" => $quantidade,
        "function" => 'inserir_refeicao'
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function limparRequisicao($idPessoaTelegram) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoaTelegram,
        "function" => 'limpar_requisicao'
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function inserirRequisicao($idPessoaTelegram, $function, $linhaChat, $dsMessage) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoaTelegram,
        "function" => $function,
        "linha_chat" => $linhaChat,
        "ds_message" => $dsMessage
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function inserirAlimentoSelecionado($idPessoaTelegram, $function, $idAlimento) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoaTelegram,
        "function" => 'inserir_alimento_selecionado',
        "id_alimento" => $idAlimento,
        "ds_requisicao" => $function
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function inserirRefeicaoTipoSelecionado($idPessoaTelegram, $function, $idTipoRefeicao) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoaTelegram,
        "function" => 'inserir_refeicao_tipo',
        "id_tipo_refeicao" => $idTipoRefeicao,
        "ds_requisicao" => $function
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function getUltimaRequisicao($idPessoaTelegram) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoaTelegram,
        "function" => 'get_ultima_requisicao'
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

function getDadosConsumoDiario($idPessoaTelegram) {
    $apiEndpoint = SITE_URL . "/requisicoesBot.php";
    $data = [
        "id_pessoa" => $idPessoaTelegram,
        "function" => 'consumo_diario'
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiEndpoint, false, $context);
    return json_decode($result, true);
}

$input = file_get_contents("php://input");
$update = json_decode($input, true);

if (isset($update["message"])) {
    $message = $update["message"];
    $chatId = $message["chat"]["id"];
    $text = $message["text"];
    $requisicao = getUltimaRequisicao($message["chat"]["id"]);
    $boPessoaCadastrada = verificaPessoaCadastrada($chatId);

    if ($text == "/start") {
        limparRequisicao($chatId);
        sendOptions($chatId, "O que deseja fazer?", [
            [["text" => "Inserir refeição", "callback_data" => "inserir_refeicao_inicio"]],
            [["text" => "Meu Consumo Diário", "callback_data" => "consumo_diario"]],
            [["text" => "Obter meu id do telegram", "callback_data" => "obter_meu_id"]]
        ]);
    } elseif (!$boPessoaCadastrada['success']) {
        limparRequisicao($chatId);
        sendMessage($chatId, "Você ainda não está vinculado a uma conta no sit SITE_URL .e /.");
        sendMessage($chatId, "Para acesso ao bot, logue no site e siga os passos para configuração.");
    } elseif ($requisicao['requisicao'] == 'inserir_refeicao_inicio') {
        $arDadosAlimentos = listadeAlimentos($text, $chatId);
        $arAlimentos = [];
        foreach ($arDadosAlimentos as $alimento) {
            $arAlimentos[] = [["text" => $alimento['ds_alimento'], "callback_data" => 'alimento_selecionado_' . $alimento['id']]];
        }
        if(empty($arAlimentos)) {
            sendMessage($chatId, "Nenhum alimento encontrado, Digite outro nome de alimento:");
        } else {
            sendOptions($chatId, "Escolha o seu produto:", $arAlimentos);
        }
    } else if ($requisicao['requisicao'] == 'inserir_refeicao_tipo') {
        $dadosTipoRefeicao = listadeTipoRefeicao($chatId);
        $arTipoRefeicao = [];
        foreach($dadosTipoRefeicao as $tipoRefeicao) {
            $arTipoRefeicao[] = [["text" => $tipoRefeicao['tp_refeicao'], "callback_data" => 'tipo_refeicao_' . $tipoRefeicao['id']]];
        }
        if(empty($arTipoRefeicao)) {
            limparRequisicao($chatId);
            sendMessage($chatId, "Nenhum tipo de refeição encontrado SITE_URL ., /refeicaotipo e adicione os seus tipos.");
        } else {
            sendOptions($chatId, "Escolha o tipo de refeição: ", $arTipoRefeicao);
        }
    } elseif ($requisicao['requisicao'] == 'inserir_alimento_selecionado') {
        $boPonto = str_contains($text, '.');
        $boVirgula = str_contains($text, ',');
        if(is_numeric($text) && !($boPonto || $boVirgula)) {
            inserirRefeicao($chatId, $text);
            limparRequisicao($chatId);
            sendMessage($chatId, "Refeição inserida com sucesso!");
        } else {
            sendMessage($chatId, "Quantidade inválida, digite apenas a quantidade em gramas:");
        }
    } else {
        sendMessage($chatId, "Nenhuma requisição válida foi solicitada, digite /start para começar");
    }
} else if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $callbackData = $callbackQuery["data"];
    $chatId = $callbackQuery["message"]["chat"]["id"];
    $boPessoaCadastrada = verificaPessoaCadastrada($chatId);

    if ($callbackData == "obter_meu_id") {
        sendMessage($chatId, "Seu ID do Telegram é: " . $chatId);
    } else if($callbackData == "consumo_diario") {
        if (!$boPessoaCadastrada['success']) {
            limparRequisicao($chatId);
            sendMessage($chatId, "Você ainda não está vinculado a uma conta no sit SITE_URL .e /.");
            sendMessage($chatId, "Para acesso ao bot, logue no site e siga os passos para configuração.");
        } else {
            $dadosConsumo = getDadosConsumoDiario($chatId);
            sendMessage($chatId,  $dadosConsumo['message']);
        }
    } else if ($callbackData == "inserir_refeicao_inicio") {
        if (!$boPessoaCadastrada['success']) {
            limparRequisicao($chatId);
            sendMessage($chatId, "Você ainda não está vinculado a uma conta no sit SITE_URL .e /.");
            sendMessage($chatId, "Para acesso ao bot, logue no site e siga os passos para configuração.");
        } else {
            inserirRequisicao($chatId, 'inserir_refeicao_inicio', $callbackQuery["message"]['message_id'], $callbackData);
            sendMessage($chatId, "Digite o nome do alimento:");
        }
    } else if (strpos($callbackData, "alimento_selecionado_") === 0) { 
        $idAlimento = str_replace("alimento_selecionado_", "", $callbackData);
        inserirAlimentoSelecionado($chatId, 'inserir_refeicao_tipo', $idAlimento);
        $dadosTipoRefeicao = listadeTipoRefeicao($chatId);
        $arTipoRefeicao = [];
        foreach($dadosTipoRefeicao as $tipoRefeicao) {
            $arTipoRefeicao[] = [["text" => $tipoRefeicao['tp_refeicao'], "callback_data" => 'tipo_refeicao_' . $tipoRefeicao['id']]];
        }
        if(empty($arTipoRefeicao)) {
            limparRequisicao($chatId);
            sendMessage($chatId, "Nenhum tipo de refeição encontrado SITE_URL ., /refeicaotipo e adicione os seus tipos.");
        } else {
            sendOptions($chatId, "Escolha o tipo de refeição: ", $arTipoRefeicao);
        }
    } else if (strpos($callbackData, "tipo_refeicao_") === 0) {
        $idTipoRefeicao = str_replace("tipo_refeicao_", "", $callbackData);
        inserirRefeicaoTipoSelecionado($chatId, 'inserir_alimento_selecionado', $idTipoRefeicao);
        sendMessage($chatId, "Digite a quantidade consumida: ");
    }
}
?>