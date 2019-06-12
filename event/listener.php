<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\bulkuseradd\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use phpbb\db\driver\driver_interface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpBB extension */
	protected $php_ext;

	/** @var string phpBB tables */
	protected $tables;

	/**
	* Constructor for listener
	*
	* @param \phpbb_db_driver	$db			The db connection
	* @param string 			$root_path	phpBB root path
	* @param string				$php_ext	phpBB file extension
	* @param array				$tables		phpBB db tables
	*
	* @access public
	*/
	public function __construct(driver_interface $db, $root_path, $php_ext, $tables)
	{
		$this->db			= $db;
		$this->root_path	= $root_path;
		$this->php_ext		= $php_ext;
		$this->tables		= $tables;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.session_create_after'				=> 'update_password',
			'core.update_session_after'				=> 'check_password',
			'core.ucp_profile_reg_details_sql_ary'	=> 'update_user_setting',
		);
	}

	/**
	* Check if the password needs to be updated
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function update_password($event)
	{
		$session_user_id = $event['session_data']['session_user_id'];

		$this->check_bulk_add($session_user_id);
	}

	/**
	* Ensure that the password is updated
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function check_password($event)
	{
		$session_id = $event['session_id'];

		// Get user id from the current session
		$sql = 'SELECT session_user_id
			FROM ' . $this->tables['sessions'] . '
			WHERE session_id = "' . $session_id . '"';

		$result 	= $this->db->sql_query($sql);
		$row 		= $this->db->sql_fetchrow($result);
		$user_id	= $row['session_user_id'];

		$this->db->sql_freeresult($result);

		$this->check_bulk_add($user_id);
	}

	/**
	* Set the marker to say that password has been updated
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function update_user_setting($event)
	{
		$sql_ary					= $event['sql_ary'];
		$sql_ary['user_bulk_add']	= false;
		$event['sql_ary'] 			= $sql_ary;
	}

	/**
	* Redirect to password change if necessary
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function check_bulk_add($user_id)
	{
		// Is this a bulk added user?
		$sql = 'SELECT user_bulk_add
			FROM ' . $this->tables['users'] . '
			WHERE user_id = ' . $user_id;

		$result 		= $this->db->sql_query($sql);
		$row 			= $this->db->sql_fetchrow($result);
		$user_bulk_add	= $row['user_bulk_add'];

		$this->db->sql_freeresult($result);

		// Do they need to change their password?
		if ($user_bulk_add)
		{
			redirect(append_sid("{$this->root_path}ucp.$this->php_ext", 'i=profile&amp;mode=reg_details'));
		}
	}
}
