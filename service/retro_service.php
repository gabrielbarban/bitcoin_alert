<?php

include("../model/retro_model.php");


class retro_service{


	function envia_email($html, $destinatario, $nome){

		require '../vendor/autoload.php';
		$email = new \SendGrid\Mail\Mail(); 

		$email->setFrom("dev@avatardasaude.com.br", "Gabriel");
		$email->setSubject("Retrospectiva 2020 - Avatar da Saúde");
		$email->addTo($destinatario, $nome);
		$email->addContent("text/plain", "conteudo");
		$email->addContent("text/html", $html);
		$key      = "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";
		$sendgrid = new \SendGrid($key);
		try
		{
		    $resposta = $sendgrid->send($email);
		    return true;
		}
		catch (Exception $e)
		{
			return false;
		}

	}

	//Quantidade de pontos conquistados por usuário
	public function get_pontos_conquistados($ano, $cod_empresa_beneficiario){
		$retro_model = new Retro();
		$data = $retro_model->get_pontos($ano, $cod_empresa_beneficiario);
		return $data;
	}

	//Quantidade de passos e distancias por usuario
	public function get_passos_conquistados($ano, $cod_empresa_beneficiario){
		$retro_model = new Retro();
		$data = $retro_model->get_passos($ano, $cod_empresa_beneficiario);
		return $data;
	}

	//Quantidade de notificações recebidas por usuário
	public function get_push_recebidas($ano, $cod_empresa_beneficiario){
		$retro_model = new Retro();
		$data = $retro_model->get_push_recebidas($ano, $cod_empresa_beneficiario);
		return $data;
	}

	//Quantidade de lembretes de medicamento cadastrados por usuario
	public function get_lembretes_remedios($ano, $cod_empresa_beneficiario){
		$retro_model = new Retro();
		$data = $retro_model->get_lembretes_remedios($ano, $cod_empresa_beneficiario);
		return $data;
	}

	//Quantidade de fotos por tipo de refeicao cadastradas por usuario
	public function get_fotos_refeicao($ano, $cod_empresa_beneficiario){
		$retro_model = new Retro();
		$data = $retro_model->get_fotos_refeicao($ano, $cod_empresa_beneficiario);
		
		if(count($data)>0){
			$quantidade=0;
			foreach ($data as $d) {
				$quantidade= $quantidade+$d["qtd_foto_refeicao"];
			}
		}

		return $quantidade;
	}

	//Quantidade de vacinas programadas por tipo de vacina por usuario
	public function get_vacinas_programadas($ano, $cod_empresa_beneficiario){
		$retro_model = new Retro();
		$data = $retro_model->get_vacinas_programadas($ano, $cod_empresa_beneficiario);
		return $data;
	}

}

?>