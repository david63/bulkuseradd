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
use phpbb\cache\service;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\log\log;
use phpbb\language\language;
use phpbb\db\driver\driver_interface;
use phpbb\passwords\manager;
use phpbb\files\factory;
use david63\bulkuseradd\classes\read_filter;
use david63\bulkuseradd\core\functions;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
* Admin upload controller
*/
class acp_upload_controller implements acp_upload_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\cache\service */
	protected $cache;

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

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpBB extension */
	protected $php_ext;

	/** @var factory */
	protected $files_factory;

	/** @var \phpbb\passwords\manager */
	protected $passwords_manager;

	/** @var \david63\bulkuseradd\classes\read_filter */
	protected $read_filter;

	/** @var \david63\bulkuseradd\core\functions */
	protected $functions;

	/** @var string phpBB tables */
	protected $tables;

	/** @var allowed_extensions */
	protected $allowed_extensions = array(
		'xls',
		'xlsm',
		'xlsx',
		'csv',
	);

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin upload controller
	*
	* @param \phpbb\config\config								$config				Config object
	* @param \phpbb\cache\service								$cache				Cache object
	* @param \phpbb\request\request								$request			Request object
	* @param \phpbb\template\template							$template			Template object
	* @param \phpbb\user										$user				User object
	* @param \phpbb\log\log										$log				Log object
	* @param \phpbb\language\language							$language			Language object
	* @param \phpbb_db_driver									$db					The db connection
	* @param string 											$root_path			phpBB root path
	* @param string												$php_ext			phpBB file extension
	* @param \phpbb\passwords\manager							$passwords_manager	Password object
	* @param factory											$files_factory		File object
	* @param \david63\david63\spreadsheet\classes\read_filter	read_filter			Methods for the extension
	* @param \david63\bulkuseradd\core\functions				functions			Functions for the extension
	* @param array												$tables				phpBB db tables
	*
	* @return \david63\bulkuseradd\controller\acp_upload_controller
	* @access public
	*/
	public function __construct(config $config, service $cache, request $request, template $template, user $user, log $log, language $language, driver_interface $db, $root_path, $php_ext, manager $passwords_manager, factory $files_factory, read_filter $read_filter, functions $functions, $tables)
	{
		$this->config				= $config;
		$this->cache				= $cache;
		$this->request				= $request;
		$this->template				= $template;
		$this->user					= $user;
		$this->log					= $log;
		$this->language				= $language;
		$this->db					= $db;
		$this->root_path 			= $root_path;
		$this->php_ext				= $php_ext;
		$this->passwords_manager	= $passwords_manager;
		$this->files_factory		= $files_factory;
		$this->read_filter			= $read_filter;
		$this->functions			= $functions;
		$this->tables				= $tables;
	}

	/**
	* Upload and/or process the file
	*
	* @return null
	* @access public
	*/
	public function upload_file()
	{
		// Add the language file
		$this->language->add_lang('acp_bulkuseradd', $this->functions->get_ext_namespace());

		// Create a form key for preventing CSRF attacks
		$form_key = 'bulkuseradd';
		add_form_key($form_key);

		$back = false;

		$submit = $this->request->variable('submit', '');

		// Is the form being submitted?
		if ($this->request->is_set_post('upload'))
		{
			// Is the submitted form is valid?
			if (!check_form_key($form_key))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Check that files can be uploaded
			if (!ini_get('file_uploads'))
			{
				trigger_error($this->language->lang('FILE_UPLOAD_NOT_AVAILABLE') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Add allowed extensions
			$allowed_extensions = $this->allowed_extensions;

			// Upload the file
			$upload_dir 	= $this->root_path . 'store/';
			$fileupload 	= $this->files_factory->get('upload')->set_allowed_extensions($allowed_extensions);
			$upload_file	= $fileupload->handle_upload('files.types.form', 'filename');

			// Has a file been selected?
			if (!$upload_file->get('realname'))
			{
				trigger_error($this->language->lang('NO_FILE_UPLOAD_SELECTED') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Can we write the file to the store folder?
			if (!is_writable(dirname($upload_dir)) || !file_exists($upload_dir))
			{
				trigger_error($this->language->lang('STORE_FOLDER_NOT_WRITABLE') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$upload_file->clean_filename('real');
			$upload_file->move_file(str_replace($this->root_path, '', $upload_dir), true, true, 0644);
			@chmod($upload_dir . $upload_file->get('real'), 0777);

			// Are there any other errors?
			if (sizeof($upload_file->error) && $upload_file->get('uploadname'))
			{
				trigger_error(implode('<br>', $upload_file->error));
			}

			// Update the config table
			$this->config->set('bua_real_file_name', $upload_file->get('realname'), true);
			$this->config->set('bua_upload_file_name', $upload_file->get('uploadname'), true);
			$this->config->set('bua_upload_file_date', time(), true);

			// Add action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_BULK_USER_ADD_FILE_UPLOADED', time(), array($upload_file->get('uploadname'), $upload_file->get('realname')));

			// Confirm the udate to the user and provide link back to previous page
			trigger_error($this->language->lang('FILE_UPLOADED') . adm_back_link($this->u_action));
		}

		// Process the file
		if ($this->request->is_set_post('update'))
		{
			// Is the file there?
			if (!$this->config['bua_real_file_name'])
			{
				trigger_error($this->language->lang('FILE_NOT_EXIST') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Check that update has not already been done
			if ($this->config['bua_update_file_date'] > $this->config['bua_upload_file_date'])
			{
				trigger_error($this->language->lang('UPDATE_ALREADY_DONE') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Let's start by getting the variables
			$input_file_name 	= $this->root_path . 'store/' . $this->config['bua_real_file_name'];
			$sheet_name 		= $this->config['bua_sheet_name'];
			$first_data_row 	= $this->config['bua_first_data_row'];
			$username_column 	= $this->config['bua_username_column'];
			$email_column 		= $this->config['bua_email_column'];
			$start_date_column	= $this->config['bua_start_date_column'];

			// Create the column array
			$columns = array(
				$username_column,
				$email_column,
			);

			$columns_add = ($start_date_column) ? array_push($columns, $start_date_column) : 0;

			if (file_exists($input_file_name))
			{
				$input_file_type = IOFactory::identify($input_file_name);
			}
			else
			{
				trigger_error($this->language->lang('FILE_IS_MISSING') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$reader 		= IOFactory::createReader($input_file_type);
			$reader->setLoadSheetsOnly($sheet_name);
			$reader->setReadDataOnly(true);
			$spreadsheet	= $reader->load($input_file_name);
			$filter_subset 	= new $this->read_filter($first_data_row, $spreadsheet->getActiveSheet()->getHighestDataRow(), $columns);
			$reader->setReadFilter($filter_subset);
			$sheet_data 	= $spreadsheet->getActiveSheet()->toArray('', true, true, true);

			// Load the user_add function to allow adding new users
			if (!function_exists('user_add'))
			{
				include_once($this->root_path . 'includes/functions_user.' . $this->php_ext);
			}

			// Find the group_id for the Registered Users group
			$sql = 'SELECT group_id
				FROM ' . $this->tables['groups'] . "
				WHERE group_name = '" . $this->db->sql_escape('REGISTERED') . "'
					AND group_type = " . GROUP_SPECIAL;

			$result 	= $this->db->sql_query($sql);
			$row 		= $this->db->sql_fetchrow($result);
			$group_id	= $row['group_id'];

			$this->db->sql_freeresult($result);

			$users_added = 0;

			// Add the user to the user table
			foreach ($sheet_data as $row => $data)
			{
				if ($row >= $first_data_row && $row <= $spreadsheet->getActiveSheet()->getHighestRow())
				{
					// Check that the username does not already exist in the db
					$username_clean = utf8_clean_string($data[$username_column]);

					$sql = 'SELECT username_clean
						FROM ' . $this->tables['users'] . "
						WHERE username_clean = '" . $this->db->sql_escape($username_clean) . "'";

					$result	= $this->db->sql_query($sql);
					$row 	= $this->db->sql_fetchrow($result);

					$this->db->sql_freeresult($result);

					// Let's validate the data
					if ($row['username_clean'])
					{
						// Add log entry if username exists
						$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_BULK_USER_ADD_FAILED', time(), array($data[$username_column]));
						continue;
					}
					else if (!$data[$username_column])
					{
						// Add log entry if no username present
						$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_BULK_USER_ADD_NO_USERNAME', time(), array($data[$email_column]));
						continue;
					}
					else if (!$data[$email_column])
					{
						// Add log entry if no email present
						$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_BULK_USER_ADD_NO_EMAIL', time(), array($data[$username_column]));
						continue;
					}
					else // We are OK to process the data
					{
						$user_row = array(
							'username'		=> $data[$username_column],
							'user_password'	=> $this->passwords_manager->hash($data[$username_column]),
							'user_email'	=> $data[$email_column],
							'group_id'		=> $group_id,
							'user_type'		=> USER_NORMAL,
							'user_new'		=> 1,
							// Set this to force password change when logging in
							'user_bulk_add'	=> 1,
						);

						//Let's deal with the start date
						if ($start_date_column && $data[$start_date_column])
						{
							// Is the date from a CSV or Excel file?
							if (is_string($data[$start_date_column]))
							{
								// Get the date into the correct format
								$data[$start_date_column] = ($this->config['bua_date_type']) ? str_replace('/', '-', $data[$start_date_column]) : str_replace('-', '/', $data[$start_date_column]);
								$start_date = strtotime($data[$start_date_column]);
							}
							else
							{
								// Convert the Excel date into Unix timestamp
								$start_date = Date::excelToTimestamp($data[$start_date_column]);
							}

							$user_row['user_regdate'] = $start_date;
							// We'll set passchg date as well just in case the board is using this
							$user_row['user_passchg'] = $start_date;
						}
						// Add the user
						user_add($user_row);
						$users_added++;
					}
				}
			}

			// Set the date/time of this file process
			$this->config->set('bua_update_file_date', time(), true);

			// Add action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_BULK_USER_ADD_PROCESSED', time(), array($this->config['bua_upload_file_name'], $users_added));

			// Confirm the update to the user and provide link back to previous page
			trigger_error($this->language->lang('FILE_UPDATED') . adm_back_link($this->u_action));

			// Let's just purge the cache in case we have any cached user data
			if ($users_added > 0)
			{
				$this->cache->purge();
			}
		}

		// Template vars for header panel
		$this->template->assign_vars(array(
			'HEAD_TITLE'		=> $this->language->lang('UPLOAD'),
			'HEAD_DESCRIPTION'	=> $this->language->lang('UPLOAD_EXPLAIN'),

			'NAMESPACE'			=> $this->functions->get_ext_namespace('twig'),

			'S_BACK'			=> $back,
			'S_VERSION_CHECK'	=> $this->functions->version_check(),

			'VERSION_NUMBER'	=> $this->functions->get_this_version(),
		));

		$form_enctype	= (@ini_get('file_uploads') == '0' || strtolower(@ini_get('file_uploads')) == 'off') ? '' : ' enctype="multipart/form-data"';
		$last_file 		= (!$this->config['bua_upload_file_name'] || !$this->config['bua_upload_file_date']) ? $this->language->lang('NO_FILE') : $this->language->lang('LAST_FILE', $this->config['bua_upload_file_name'], $this->user->format_date($this->config['bua_upload_file_date']));
		$last_update 	= (!$this->config['bua_update_file_date']) ? $this->language->lang('NO_UPDATE') : $this->language->lang('LAST_UPDATE', $this->user->format_date($this->config['bua_update_file_date']));

		$this->template->assign_vars(array(
			'LAST_FILE'			=> $last_file,
			'LAST_UPDATE'	   	=> $last_update,

			'S_FORM_ENCTYPE'	=> $form_enctype,
		));
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
