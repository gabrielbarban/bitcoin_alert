<?php

class api_prices_service{

	public function envia_email($valor_atual, $valor_txt, $porcentagem, $diferenca, $indice){

		if($indice === "subida"){
			$palavra_chave_1 = "uma subida";
			$palavra_chave_2 = "Parabéns, você ficou um pouco mais rico.";
		} else if($indice === "queda"){
			$palavra_chave_1 = "uma queda";
			$palavra_chave_2 = "Acesse o App da Brasil Bitcoin, e reveja seus objetivos.";
		}
		$html = "Olá Sr. Gabriel, venho lhe informar que o Bitcoin estava em {$valor_txt}, e foi para {$valor_atual}, {$palavra_chave_1} de {$porcentagem}%. {$palavra_chave_2}";

		require '../vendor/autoload.php';
		$email = new \SendGrid\Mail\Mail(); 

		$email->setFrom("dev@avatardasaude.com.br", "Gabriel");
		$email->setSubject("Alerta Bitcoin | BARBAN SOFTWARES");
		$email->addTo("barbangabriel@gmail.com", "Gabriel Barban");
		$email->addTo("clr123rocha@yahoo.com.br", "Claudio Lourenco");
		$email->addTo("claudiolr.sp@uol.com.br", "Claudio Lourenco");
		$email->addTo("lanamelo2015@gmail.com", "Lana Melo");
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

	public function salva_btc($valor_atual){

		$arquivo = fopen ('../data/btc.txt', 'r');
		while(!feof($arquivo)){
			$valor_txt = fgets($arquivo, 1024);
		}
		fclose($arquivo);

		if($valor_atual === $valor_txt){
			echo "o valor nao mudou";exit;
		} else{
			$this->valida_envia_valor($valor_atual, $valor_txt);
			$myfile = fopen("../data/btc.txt", "w+");
			fwrite($myfile, $valor_atual);
			fclose($myfile);
		}
	}

	public function pega_prices(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://brasilbitcoin.com.br/API/prices/BTC',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$response = json_decode(curl_exec($curl));
		$last_btc = $response->last;
		$this->salva_btc($last_btc);
		return $last_btc;
	}

	public function valida_envia_valor($valor_atual, $valor_txt){
		
		// caindo...
		if($valor_atual < $valor_txt){
			$diferenca = $valor_txt - $valor_atual;
			$porcentagem = (100 * $diferenca) / $valor_txt;
			$porcentagem = number_format($porcentagem, 6);

			if($porcentagem > 2){
				if($this->envia_email($valor_atual, $valor_txt, $porcentagem, $diferenca, "queda")){
					echo "Email enviado | COD 1";
				}
			}

			else if($porcentagem < 2){
				//vida q segue mlk
				echo "uma queda baixa, de {$porcentagem}%";
			}

		}

		// subindo...
		else if($valor_txt < $valor_atual){
			$diferenca = $valor_atual - $valor_txt;
			$porcentagem = (100 * $diferenca) / $valor_atual;
			$porcentagem = number_format($porcentagem, 6);

			if($porcentagem > 2){
				if($this->envia_email($valor_atual, $valor_txt, $porcentagem, $diferenca, "subida")){
					echo "Email enviado | COD 2";
				}
			}

			else if($porcentagem < 2){
				//vida q segue mlk
				echo "uma subida tímida, de {$porcentagem}%";
			}

		}
	}


}


?>