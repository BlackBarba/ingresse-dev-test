<?php

namespace App\Http\Controllers\Traits;

trait Paginable
{
	/**
	 * Max itens per page
	 *
	 * @var int
	*/
	private $pageSizeLimit = 100;

	/**
	 * Default itens per page
	 *
	 * @var int
	*/
	private $defaultPageSize = 15;

	public function getPerPage()
	{
		$pageSize = request('perPage', $this->defaultPageSize);

		return min($pageSize, $this->pageSizeLimit);
	}

	public function getPage()
	{
		return request('page', 1);
	}
}