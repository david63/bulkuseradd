<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\bulkuseradd\migrations;

use phpbb\db\migration\migration;

class version_2_1_0 extends migration
{
	public function update_data()
	{
		return array(
			// Add the config variables
			array('config.add', array('bua_date_type', 1)),
			array('config.add', array('bua_email_column', '')),
			array('config.add', array('bua_first_data_row', 1)),
			array('config.add', array('bua_real_file_name', '')),
			array('config.add', array('bua_sheet_name', 'Sheet1')),
			array('config.add', array('bua_start_date_column', '')),
			array('config.add', array('bua_update_file_date', 0)),
			array('config.add', array('bua_upload_file_date', 0)),
			array('config.add', array('bua_upload_file_name', '')),
			array('config.add', array('bua_username_column', '')),

			// Add the ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'BULK_USER_ADD')),

			array('module.add', array(
				'acp', 'BULK_USER_ADD', array(
					'module_basename'	=> '\david63\bulkuseradd\acp\bulkuseradd_module',
					'modes'				=> array('manage', 'upload'),
				),
			)),

			array('custom', array(array($this, 'allow_documents'))),
		);
	}

		public function update_schema()
		{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_bulk_add'	=> array('BOOL', 0),
				),
			),
		);
	}

	/**
	* Drop the columns schema from the tables
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_bulk_add',
				),
			),
		);

	}

	/**
	* Enable the upload of Excel/CSV files
	*
	* return null
	* @access public
	*/
	public function allow_documents()
	{
		$sql = 'UPDATE ' . EXTENSION_GROUPS_TABLE . "
			SET allow_group = 1
			WHERE group_name = 'DOCUMENTS'";

		$this->db->sql_query($sql);
	}
}
