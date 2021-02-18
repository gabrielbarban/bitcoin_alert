<?php

include("../service/retro_service.php");
$retro_service = new retro_service();

$cod_empresa_beneficiario = 429448;
$ano = "2020";


// falta criar funcao pra pegar os dados do usuario (nome, email e data de nascimento, atraves do cod_empresa_beneficiario)




// busca os dados
$pontos = $retro_service->get_pontos_conquistados($ano, $cod_empresa_beneficiario)[0]["pontuacao"];
$passos = $retro_service->get_passos_conquistados($ano, $cod_empresa_beneficiario)[0]["qtd_passos"];
$notificacoes_recebidas = $retro_service->get_push_recebidas($ano, $cod_empresa_beneficiario)[0]["qtd_notificacao"]; 
$lembretes_medicamentos = $retro_service->get_lembretes_remedios($ano, $cod_empresa_beneficiario)[0]["quantidade_medicamento"];
$fotos_refeicoes = $retro_service->get_fotos_refeicao($ano, $cod_empresa_beneficiario); 
$vacinas_programadas = $retro_service->get_vacinas_programadas($ano, $cod_empresa_beneficiario)[0]["total_vacina"];

// monta layout
$html = "
	<html>
	<body>
	<i>Olá ".$nome."</i>
	<br>
	RETROSPECTIVA 2020<br>
	Você deu ".$qtdPassos." passos em ".$ano_referencia."
	</body>
	</html>
";

// dispara email
if($retro_service->envia_email($html, $email, $nome) == true){
	$response["codigo"] = 202;
  	$response["mensagem"] = "Retrospectiva enviada para o email ".$email;
  	header("Content-type: application/json; charset=utf-8");
  	echo json_encode($response);
} else{
	$response["codigo"] = 403;
  	$response["mensagem"] = "Não foi possível enviar o email.";
  	header("Content-type: application/json; charset=utf-8");
  	echo json_encode($response);
}


?>