<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\bulkuseradd\controller;

/**
* Interface for our admin controller
*
* This describes all of the methods we'll use for the admin front-end of this extension
*/
interface acp_manage_interface
{
	/**
	* Display the output for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options();

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action);
}
