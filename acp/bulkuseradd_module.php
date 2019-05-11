<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\bulkuseradd\acp;

class bulkuseradd_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container;

		$this->page_title = $phpbb_container->get('language')->lang('BULK_USER_ADD_UPLOAD');

		switch ($mode)
		{
			case 'manage':
				$this->tpl_name = 'bulkuseradd_manage';

				// Get an instance of the admin controller
				$admin_controller = $phpbb_container->get('david63.bulkuseradd.acp.manage.controller');

				// Make the $u_action url available in the admin controller
				$admin_controller->set_page_url($this->u_action);
				$admin_controller->display_options();
			break;

			case 'upload':
				$this->tpl_name = 'bulkuseradd_upload';

				// Get an instance of the admin controller
				$admin_controller = $phpbb_container->get('david63.bulkuseradd.acp.upload.controller');

				// Make the $u_action url available in the admin controller
				$admin_controller->set_page_url($this->u_action);
				$admin_controller->upload_file();
			break;
		}
	}
}
