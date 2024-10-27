<?php

namespace Libs\MavCodes\DatabaseManager;

class Pagination
{
	/**
	 * Número máximo de registros por página
	 * @var integer
	 */
	private int $limit;

	/**
	 * Quantidade total de resultados do Banco de Dados
	 * @var integer
	 */
	private int $results;

	/**
	 * Quantidade de páginas
	 * @var integer
	 */
	private int $pages;

	/**
	 * Indicar a Página Atual
	 * @var integer
	 */
	private int $currentPage;

	/**
	 * Construtor da classe
	 * @param integer $results
	 * @param integer $currentPage
	 * @param integer $limit
	 */
	public function __construct(int $results, int $currentPage = 1, int $limit = 10)
  {
		$this->results = $results;
		$this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
		$this->limit = $limit;
		$this->calculatePages();
	}

	/**
	 * Calcula o total de páginas
	 */
	private function calculatePages(): void
  {
		$this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;
		$this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;   // Verifica se a página atual não excede o número de páginas
	}

	/**
	 * Mátodo responsável por retornar a cláusula LIMIT do SQL
	 * @return string
	 */
	public function getLimit(): string
  {
		$offset = ($this->limit * ($this->currentPage - 1));
		return $offset.','.$this->limit;
	}

	/**
	 * Método responsável por retornar as opções de páginas disponíveis
	 * @return array $limit
	 */
	public function getPages(): array
  {
		if ($this->pages == 1) return [];

		$paginas = [];
		for($i = 1; $i <= $this->pages; $i++){
			$paginas[] = [
				'page' => $i,
				'current' => $i == $this->currentPage
			];
		}

		return $paginas;
	}
}
