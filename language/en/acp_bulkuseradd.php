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
	'BUA_SETTINGS'					=> 'Bulk user add settings',
	'BULK_USER_ADD_MANAGE_EXPLAIN'	=> 'Here you can enter the settings that are required to process the uploaded file.',

	'CONFIG_NOT_SET'				=> 'Config options not set',

	'DATE_TYPE'						=> 'Date format',
	'DATE_TYPE_EXPLAIN'				=> 'Is the date in European or US format?<br>This only applies to CSV files.',
	'DISALLOWED_EXTENSION'			=> 'The selected filetype is not allowed',

	'EMAIL_COLUMN'					=> 'Email column',
	'EMAIL_COLUMN_EXPLAIN'			=> 'The column letter of the spreadsheet that contians the user’s Email address.',
	'EUROPEAN'						=> 'European',

	'FILE_IS_MISSING'		   		=> 'Uploaded file is missing',
	'FILE_NOT_EXIST'				=> 'There is no uploaded file.',
	'FILE_UPDATED'					=> 'The data file has been updated.',
	'FILE_UPLOAD'					=> 'Select file',
	'FILE_UPLOADED'					=> 'The data file has been uploaded.',
	'FILE_UPLOAD_EXPLAIN'			=> 'Select the spreadsheet file that you want to upload.',
	'FILE_UPLOAD_NOT_AVAILABLE'		=> 'Your server settings do not allow file uploads',
	'FIRST_DATA_ROW'				=> 'First data row',
	'FIRST_DATA_ROW_EXPLAIN'		=> 'The first row on the spreadsheet that contains the data (do not include heading rows).',

	'GENERAL_UPLOAD_ERROR'			=> 'File upload error',

	'LAST_FILE'						=> '<strong>The last file uploaded was - %1$s on %2$s</strong>',
	'LAST_UPDATE'					=> '<strong>The last file update was on %1$s</strong>',

	'NEW_VERSION'					=> 'New Version',
	'NEW_VERSION_EXPLAIN'			=> 'There is a newer version of this extension available.',
	'NO_FILE'						=> '<strong>No file uploaded</strong>',
	'NO_FILE_UPLOAD_SELECTED'		=> 'No file selected to upload.',
	'NO_UPDATE'						=> '<strong>No update done.</strong>',

	'PHP_SIZE_NA'					=> 'The attachment’s file size is too large.<br>Could not determine the maximum size defined by PHP in php.ini.',
	'PHP_SIZE_OVERRUN'				=> 'The attachment’s file size is too large.<br>Please note this is set in php.ini and cannot be overridden.',

	'UPDATE_ALREADY_DONE'			=> 'The last uploaded file has already been updated.',
	'UPLOAD'						=> 'Upload & process file',
	'UPLOAD_EXPLAIN'				=> 'Here you can upload the spreadsheet file and/or process the uploaded file.',
	'UPLOAD_FILE'		   			=> 'Upload file',
	'UPLOAD_SUBMIT'					=> 'Upload',
	'USA'							=> 'US',
	'USERNAME_COLUMN'				=> 'Username column',
	'USERNAME_COLUMN_EXPLAIN'		=> 'The column letter of the spreadsheet that contians the user’s Username.',

	'PROCESS'						=> 'Process file',
	'PROCESS_FILE_EXPLAIN'		   	=> '<center>This option will add the details from the uploaded fie into the database.</center>',
	'PROCESS_FILE_WARNING'			=> '<center><strong>PLEASE NOTE:</strong> You are strongly urged to ensure that you have backed up your database before running this update process as there is no “undo” facility and you could have a corrupt database.</center>',
	'PROCESS_UPDATE'				=> 'Update',

	'SHEET_NAME'					=> 'Sheet name',
	'SHEET_NAME_EXPLAIN'			=> 'The spreadsheet sheet that contains the data.',
	'START_DATE_COLUMN'				=> 'Start date column',
	'START_DATE_COLUMN_EXPLAIN'		=> 'The column letter of the spreadsheet that contians the user’s Start Date.<br>If this field is left blank, or there is no entry for a user in this column, then the current date will be used.',
	'STORE_FOLDER_NOT_WRITABLE'		=> 'The store folder is either missing or is not writable',

	'VERSION'						=> 'Version',
));
