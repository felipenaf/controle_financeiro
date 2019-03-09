<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/model/db/Connection.class.php";
require $_SERVER['DOCUMENT_ROOT'] . "/model/Produto/ProdutoModel.class.php";

class ProdutoConsulta {

	private $db;

	public function __construct() {
		$this->db = parse_ini_file("config/database.ini");
	}

	public function insertProduto($produto) {
		try {
			$c = new Connection();
			$con = $c->getConnection();

			$itens = array(
				"descricao" => $produto->getDescricao(),
				"data_criacao" => $produto->getDataCriacao(),
				"id_grupo" => $produto->getIdGrupo(),
				"valor" => $produto->getValor(),
				"observacao" => $produto->getObservacao(),
			);

			$query = "INSERT INTO {$this->db['database']}.produto
						(descricao, data_criacao, id_grupo, valor, observacao)
					VALUES
						(:descricao, :data_criacao, :id_grupo, :valor, :observacao);";

			$con = $con->prepare($query);
			$con->execute($itens);

			include "pages/sucesso.src.php";

		} catch (Exception $e) {
			echo $e->getMessage();
		} finally {
			$c->closeAll();
		}
	}

	public function updateProduto($produto) {
		try {
			$c = new Connection();
			$con = $c->getConnection();

			$itens = array(
				"id_produto" => $produto->getIdProduto(),
				"descricao" => $produto->getDescricao(),
				"data_criacao" => $produto->getDataCriacao(),
				"id_grupo" => $produto->getIdGrupo(),
				"valor" => $produto->getValor(),
				"observacao" => $produto->getObservacao(),
			);

			$query = "UPDATE {$this->db['database']}.produto
						SET
							descricao = :descricao, data_criacao = :data_criacao, id_grupo = :id_grupo, valor = :valor, observacao = :observacao
						WHERE
							id_produto = :id_produto;";

			$con = $con->prepare($query);
			$con->execute($itens);

			include "pages/sucesso.src.php";

		} catch (Exception $e) {
			echo $e->getMessage();
		} finally {
			$c->closeAll();
		}
	}

	public function deleteProduto($produto) {
		try {
			$c = new Connection();
			$con = $c->getConnection();
			$id_produto = $produto->getIdProduto();
			$query = "DELETE FROM {$this->db['database']}.produto WHERE id_produto = :id_produto;";
			$stmt = $con->prepare($query);
			$stmt->bindValue(":id_produto", $id_produto);
			$stmt->execute();

			include "pages/produto/excluir-confirmar.html.php";

		} catch (Exception $e) {
			echo $e->getMessage();
		} finally {
			$c->closeAll();
		}
	}

	public function getProdutos() {
		try {
			$c = new Connection();
			$con = $c->getConnection();

			$query = "SELECT id_produto, id_grupo, descricao, valor, observacao, DATE_FORMAT(data_criacao, '%Y-%m-%d') as data_criacao, data_modificacao
						FROM
							{$this->db['database']}.produto
						ORDER BY
							data_criacao DESC;";

			$result = $con->query($query);

			return $result->fetchAll(PDO::FETCH_ASSOC);

		} catch (Exception $e) {
			echo $e->getMessage();
		} finally {
			$c->closeAll();
		}
	}

	public function getProdutoById($id_produto) {
		try {
			$c = new Connection();
			$con = $c->getConnection();

			$query = "SELECT * FROM {$this->db['database']}.produto WHERE id_produto = :id_produto;";

			$stmt = $con->prepare($query);
			$stmt->bindValue(":id_produto", $id_produto);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_ASSOC);

		} catch (Exception $e) {
			echo $e->getMessage();
		} finally {
			$c->closeAll();
		}
	}
}