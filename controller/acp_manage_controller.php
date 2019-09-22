<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\bulkuseradd\controller;

use phpbb\config\config;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\log\log;
use phpbb\language\language;
use david63\bulkuseradd\core\functions;

/**
* Admin manage controller
*/
class acp_manage_controller implements acp_manage_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \david63\bulkuseradd\core\functions */
	protected $functions;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin manage controller
	*
	* @param \phpbb\config\config					$config			Config object
	* @param \phpbb\request\request					$request		Request object
	* @param \phpbb\template\template				$template		Template object
	* @param \phpbb\user							$user			User object
	* @param \phpbb\log\log							$log			Log object
	* @param \phpbb\language\language				$language		Language object
	* @param \david63\bulkuseradd\core\functions	functions		Functions for the extension
	*
	* @return \david63\bulkuseradd\controller\acp_manage_controller
	* @access public
	*/
	public function __construct(config $config, request $request, template $template, user $user, log $log, language $language, functions $functions)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->log			= $log;
		$this->language		= $language;
		$this->functions	= $functions;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Add the language files
		$this->language->add_lang('acp_bulkuseradd', $this->functions->get_ext_namespace());

		// Create a form key for preventing CSRF attacks
		$form_key = 'bulkuseradd';
		add_form_key($form_key);

		$back = false;

		// Is the form being submitted
		if ($this->request->is_set_post('submit'))
		{
			// Is the submitted form is valid
			if (!check_form_key($form_key))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_BULK_USER_ADD');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Template vars for header panel
		$this->template->assign_vars(array(
			'HEAD_TITLE'		=> $this->language->lang('BULK_USER_ADD_MANAGE'),
			'HEAD_DESCRIPTION'	=> $this->language->lang('BULK_USER_ADD_MANAGE_EXPLAIN'),

			'NAMESPACE'			=> $this->functions->get_ext_namespace('twig'),

			'S_BACK'			=> $back,
			'S_VERSION_CHECK'	=> $this->functions->version_check(),

			'VERSION_NUMBER'	=> $this->functions->get_this_version(),
		));

		$this->template->assign_vars(array(
			'DATE_TYPE'	   		=> isset($this->config['bua_date_type']) ? $this->config['bua_date_type'] : 1,

			'EMAIL_COLUMN'	   	=> isset($this->config['bua_email_column']) ? $this->config['bua_email_column'] : '',

			'FIRST_DATA_ROW'	=> isset($this->config['bua_first_data_row']) ? $this->config['bua_first_data_row'] : 1,

			'SHEET_NAME'		=> isset($this->config['bua_sheet_name']) ? $this->config['bua_sheet_name'] : 'Sheet1',
			'START_DATE_COLUMN'	=> isset($this->config['bua_start_date_column']) ? $this->config['bua_start_date_column'] : '',

			'USERNAME_COLUMN'	=> isset($this->config['bua_username_column']) ? $this->config['bua_username_column'] : '',
			'U_ACTION'			=> $this->u_action,
		));
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('bua_date_type', $this->request->variable('bua_date_type', 1));
		$this->config->set('bua_email_column', strtoupper($this->request->variable('bua_email_column', '')));
		$this->config->set('bua_first_data_row', $this->request->variable('bua_first_data_row', 0));
		$this->config->set('bua_username_column', strtoupper($this->request->variable('bua_username_column', '')));
		$this->config->set('bua_sheet_name', $this->request->variable('bua_sheet_name', ''));
		$this->config->set('bua_start_date_column', strtoupper($this->request->variable('bua_start_date_column', '')));
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		return $this->u_action = $u_action;
	}
}
