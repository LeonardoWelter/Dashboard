<?php

    // Classe que representa a abstração do Dashboard
	// Possui Getters e Setters genéricos para recuperar os atributos,
	// embora os atributos estejam publicos para facilitar o retorno.
	class Dashboard {
		public $dataInicio;
		public $dataFim;
		public $numeroVendas;
		public $totalVendas;
		public $usuariosAtivos;
		public $usuariosInativos;
		public $totalReclamacoes;
		public $totalElogios;
		public $totalSugestoes;
		public $totalDespesas;


		public function getDataInicio()
		{
			return $this->dataInicio;
		}

		public function setDataInicio($dataInicio)
		{
			$this->dataInicio = $dataInicio;
		}

		public function getDataFim()
		{
			return $this->dataFim;
		}

		public function setDataFim($dataFim)
		{
			$this->dataFim = $dataFim;
		}

		public function getNumeroVendas()
		{
			return $this->numeroVendas;
		}

		public function setNumeroVendas($numeroVendas)
		{
			$this->numeroVendas = $numeroVendas;
		}

		public function getTotalVendas()
		{
			return $this->totalVendas;
		}

		public function setTotalVendas($totalVendas)
		{
			$this->totalVendas = $totalVendas;
		}

		public function getUsuariosAtivos()
		{
			return $this->usuariosAtivos;
		}

		public function setUsuariosAtivos($usuariosAtivos)
		{
			$this->usuariosAtivos = $usuariosAtivos;
		}

		public function getUsuariosInativos()
		{
			return $this->usuariosInativos;
		}

		public function setUsuariosInativos($usuariosInativos)
		{
			$this->usuariosInativos = $usuariosInativos;
		}

		public function getTotalReclamacoes()
		{
			return $this->totalReclamacoes;
		}

		public function setTotalReclamacoes($totalReclamacoes)
		{
			$this->totalReclamacoes = $totalReclamacoes;
		}

		public function getTotalElogios()
		{
			return $this->totalElogios;
		}

		public function setTotalElogios($totalElogios)
		{
			$this->totalElogios = $totalElogios;
		}

		public function getTotalSugestoes()
		{
			return $this->totalSugestoes;
		}

		public function setTotalSugestoes($totalSugestoes)
		{
			$this->totalSugestoes = $totalSugestoes;
		}

		public function getTotalDespesas()
		{
			return $this->totalDespesas;
		}

		public function setTotalDespesas($totalDespesas)
		{
			$this->totalDespesas = $totalDespesas;
		}
	}

	// Classe que representa a Conexão com o SGBD
	class Conexao {
		// Dados de conexão e autenticação com o SGBD
		private $host = 'localhost'; // Host
		private $dbname = 'dashboard'; // Database
		private $user = 'USER'; // Usuário
		private $pass = 'SENHA'; // Senha

		// Função que realiza a conexão com o SGBD, utilizando os parâmetros inseridos anteriormente
		// Retorna o objeto da conexão,
		public function conectar() {
			try {
				$conexao = new PDO("mysql:host=$this->host;dbname=$this->dbname", "$this->user", "$this->pass");

				$conexao->exec("SET charset SET utf8");

				return $conexao;

			} catch (PDOException $e){
				echo '<p>' . $e->getMessage(). '</p>';
			}
		}
	}

	// Classe BD implementa a camada Service da aplicação, responsável pela recuperação dos dados
	// que estão inseridos no SGBD
	class BD {
		private $conexao;
		private $dashboard;

		public function __construct(Conexao $conexao, Dashboard $dashboard)
		{
			$this->conexao = $conexao->conectar();
			$this->dashboard = $dashboard;
		}

		public function getNumeroVendas() {
			$query = 'SELECT COUNT(*) AS numeroVendas FROM tb_vendas WHERE data_venda BETWEEN :dataInicio AND :dataFim';

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':dataInicio', $this->dashboard->getDataInicio());
			$stmt->bindValue(':dataFim', $this->dashboard->getDataFim());
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->numeroVendas;
		}

		public function getTotalVendas() {
			$query = 'SELECT SUM(total) AS totalVendas FROM tb_vendas WHERE data_venda BETWEEN :dataInicio AND :dataFim';

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':dataInicio', $this->dashboard->getDataInicio());
			$stmt->bindValue(':dataFim', $this->dashboard->getDataFim());
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->totalVendas;
		}

		public function getClientesAtivos() {
			$query = 'SELECT COUNT(*) AS clientesAtivos FROM tb_clientes WHERE cliente_ativo = 1';

			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->clientesAtivos;
		}

		public function getClientesInativos() {
			$query = 'SELECT COUNT(*) AS clientesInativos FROM tb_clientes WHERE cliente_ativo = 0';

			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->clientesInativos;
		}

	    // 1 = reclamacao | 2 = sugestão | 3 = elogio
		public function getTotalReclamacoes() {
			$query = 'SELECT COUNT(*) as totalReclamacoes FROM tb_contatos WHERE tipo_contato = 1';

			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->totalReclamacoes;
		}

		public function getTotalElogios() {
			$query = 'SELECT COUNT(*) as totalElogios FROM tb_contatos WHERE tipo_contato = 3';

			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->totalElogios;
		}

		public function getTotalSugestoes() {
			$query = 'SELECT COUNT(*) as totalSugestoes FROM tb_contatos WHERE tipo_contato = 2';

			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->totalSugestoes;
		}

		public function getTotalDespesas() {
			$query = 'SELECT SUM(total) AS totalDespesas FROM tb_despesas WHERE data_despesa BETWEEN :dataInicio AND :dataFim';

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':dataInicio', $this->dashboard->getDataInicio());
			$stmt->bindValue(':dataFim', $this->dashboard->getDataFim());
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ)->totalDespesas;
		}
	}

	// Instanciação das classes Dashboard e Conexao
	$dashboard = new Dashboard();
	$conexao = new Conexao();

	// Separa a data recebida via GET para tratamento
	$competencia = explode('-',$_GET['competencia']);

	// Divide o array do Explode em variáveis separadas
	$ano = $competencia[0];
	$mes = $competencia[1];

	// Calcula quantos dias tem no mês selecionado
	$diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

	// Atribui na instancia de Dashboard a data de inicio e fim da competencia
	$dashboard->setDataInicio($ano.'-'.$mes.'-01');
	$dashboard->setDataFim($ano. '-'. $mes . '-'. $diasMes);

	// Instancia de BD para tratar das operações com o BD
	$bd = new BD($conexao, $dashboard);

	// Atribui na instancia de Dashboard o restante dos atributos através dos métodos
	// disponibilizados pela Classe BD;
	$dashboard->setNumeroVendas($bd->getNumeroVendas());
	$dashboard->setTotalVendas($bd->getTotalVendas());
	$dashboard->setUsuariosAtivos($bd->getClientesAtivos());
	$dashboard->setUsuariosInativos($bd->getClientesInativos());
	$dashboard->setTotalReclamacoes($bd->getTotalReclamacoes());
	$dashboard->setTotalElogios($bd->getTotalElogios());
	$dashboard->setTotalSugestoes($bd->getTotalSugestoes());
	$dashboard->setTotalDespesas($bd->getTotalDespesas());

	// Converte a instancia de Dashboard para JSON e imprime na tela para poder ser requisitado
	// pelo AJAX e aplicado no Front End;
	echo json_encode($dashboard);