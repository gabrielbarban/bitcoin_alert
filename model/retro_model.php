<?php


class retro{

	function __construct()
	{
		require_once("../database/crud.php");
	}

	public function verify_user()
	{
		$crud = new Crud();
		$query = "SELECT * FROM users WHERE email='".$email."';";
		$data = $crud->find($query);
		if(count($data) > 0){
			return 1;			
		} else{
			return 0;
		}
	}

	public function get_pontos($ano, $cod_empresa_beneficiario){
		$crud = new Crud();
		$query = "select cod_empresa_beneficiario, sum(pontuacao) as pontuacao
		  from (select mi.tipo_missao, mi.titulo_missao, mu.cod_empresa_beneficiario, mu.data_atribuicao, mu.pontuacao, mf.cod_missao
		  from vh_missao_usuario mu
		  join vh_fator_risco_missao mf on mf.cod_fator_missao = mu.cod_fator_missao
		  join (select mt.descricao as tipo_missao, mi.titulo as titulo_missao, m.cod_missao
		          from vh_missao m
		          join vh_missao_info mi ON mi.cod_missao_info = m.cod_missao_info
		          join vh_missao_tipo mt on m.cod_missao_tipo = mt.cod_missao_tipo) as mi
		    on mf.cod_missao = mi.cod_missao
		  where year(mu.data_atribuicao) = ".$ano.") as t
		  where cod_empresa_beneficiario = ".$cod_empresa_beneficiario.";";
		$data = $crud->find($query);

		return $data;
	}

	public function get_passos($ano, $cod_empresa_beneficiario){
		$crud = new Crud();
		$query = "select cod_empresa_beneficiario, sum(passos) as qtd_passos, sum(distancia_caminhada) as 			   distancia_caminhada,
		       sum(distancia_bike) as distancia_bike, sum(kcal) as kcal, sum(escadas) as lances_escada
		  from (select cod_empresa_beneficiario, passos, distancia_caminhada, distancia_bike, kcal, escadas
		  from vh_health_data
		  where year(data) = ".$ano.") as tab
		  where cod_empresa_beneficiario = ".$cod_empresa_beneficiario.";";
		$data = $crud->find($query);

		return $data;
	}

	public function get_push_recebidas($ano, $cod_empresa_beneficiario){
		$crud = new Crud();
		$query = "select cod_empresa_beneficiario, count(cod_notificacao_usuario) as qtd_notificacao
		  from (select cod_notificacao_usuario, cod_empresa_beneficiario
		  from vh_notificacao_usuario
		  where year(data_status) = 2020) as t
		  where cod_empresa_beneficiario = ".$cod_empresa_beneficiario.";";
		$data = $crud->find($query);

		return $data;
	}

	public function get_lembretes_remedios($ano, $cod_empresa_beneficiario){
		$crud = new Crud();
		$query = "select cod_empresa_beneficiario, count(cod_medicamento_usuario) as quantidade_medicamento
			from (select * from vh_medicamento_usuario where year(data_inicio) = ".$ano.") as t
			where t.cod_empresa_beneficiario = ".$cod_empresa_beneficiario.";";
		$data = $crud->find($query);

		return $data;
	}

	public function get_fotos_refeicao($ano, $cod_empresa_beneficiario){
		$crud = new Crud();
		$query = "select cod_empresa_beneficiario, descricao_refeicao, count(cod_refeicao_usuario) as qtd_foto_refeicao
		from (select rtp.descricao as descricao_refeicao, rf.* from vh_refeicao_usuario rf
		join vh_refeicao_tipo rtp on rtp.cod_refeicao_tipo = rf.cod_refeicao_tipo
		where year(data_cadastro) = ".$ano.") as t
		where cod_empresa_beneficiario = ".$cod_empresa_beneficiario."
		group by descricao_refeicao;";
		$data = $crud->find($query);

		return $data;
	}

	public function get_vacinas_programadas($ano, $cod_empresa_beneficiario){
		$crud = new Crud();
		$query = "select cod_empresa_beneficiario, nome, count(cod_vacina_usuario) as total_vacina
		from (select v.nome, vu.*
		  from vh_vacina_usuario vu
		  join vh_vacina v ON v.cod_vacina = vu.cod_vacina
		  where year(vu.data_programada) = ".$ano.") t
		  where cod_empresa_beneficiario=".$cod_empresa_beneficiario." group by nome;";
		$data = $crud->find($query);

		return $data;
	}
}


?>