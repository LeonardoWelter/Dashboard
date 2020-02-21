<?php

	class Dashboard {
		public $dataInicio;
		public $dataFim;
		public $numeroVendas;
		public $totalVendas;


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
	}

	class Conexao {
		private $host = 'localhost';
		private $dbname = 'dashboard';
		private $user = 'leonardo';
		private $pass = 'p4ss*';

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
	}

	$dashboard = new Dashboard();

	$conexao = new Conexao();

	$competencia = explode('-',$_GET['competencia']);

	$ano = $competencia[0];
	$mes = $competencia[1];

	$diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

	$dashboard->setDataInicio($ano.'-'.$mes.'-01');
	$dashboard->setDataFim($ano. '-'. $mes . '-'. $diasMes);

	$bd = new BD($conexao, $dashboard);

	$dashboard->setNumeroVendas($bd->getNumeroVendas());
	$dashboard->setTotalVendas($bd->getTotalVendas());
	echo json_encode($dashboard);