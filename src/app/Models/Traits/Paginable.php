<?php

namespace App\Models\Traits;

trait Paginable
{
	/**
	 * @var int
	*/
	private $pageSizeLimit = 100;

	public function getPerPage()
	{
		$pageSize = request('perPage', $this->perPage);
		return min($pageSize, $this->pageSizeLimit);
	}
}