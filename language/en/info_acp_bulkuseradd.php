<?php
/**
*
* @package Bulk User Add Extension
* @copyright (c) 2019 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'BULK_USER_ADD'						=> 'Bulk user add',
	'BULK_USER_ADD_MANAGE'				=> 'Bulk user add settings',
	'BULK_USER_ADD_UPLOAD'				=> 'Bulk user add process',

	'LOG_BULK_USER_ADD'					=> '<strong>Bulk User Add settings updated</strong>',
	'LOG_BULK_USER_ADD_FAILED'			=> '<strong>Bulk User Add - this user already exists</strong><br>» %1$s',
	'LOG_BULK_USER_ADD_FILE_UPLOADED'	=> '<strong>Bulk User Add file uploaded</strong><br>» %1$s as %2$s',
	'LOG_BULK_USER_ADD_NO_EMAIL'		=> '<strong>Bulk User Add -there is no email address for username</strong><br>» %1$s',
	'LOG_BULK_USER_ADD_NO_USERNAME'		=> '<strong>Bulk User Add - there is no username for email</strong><br>» %1$s',
	'LOG_BULK_USER_ADD_PROCESSED'		=> '<strong>Bulk User Add file processed</strong><br>» %1$s<br> %2$s <strong>users added</strong>',
));
