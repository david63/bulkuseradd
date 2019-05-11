<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\bulkuseradd\classes;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
* read_filter
*/
class read_filter implements IReadFilter
{
	public function __construct($start_row, $end_row, $columns)
	{
		$this->start_row	= $start_row;
		$this->end_row 		= $end_row;
		$this->columns 		= $columns;
	}

	public function readCell($column, $row, $worksheetName = '')
	{
		if ($row >= $this->start_row && $row <= $this->end_row)
		{
			if (in_array($column, $this->columns))
			{
				return true;
			}
		}
		return false;
	}
}
