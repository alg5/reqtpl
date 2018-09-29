<?php
/** 
*
* reqtpl [Russian]
*
* @package reqtpl
* @copyright (c) 2014 alg
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
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

$lang = array_merge($lang, array(
	'ACP_REQTPL'			                => 'Request template',
	'ACP_REQTPL_SETTINGS'				    => 'Setting',
	'ACP_REQTPL_EXPLAIN'				    => 'Request template setting',
	'ACP_REQTPL_SHOW_BUTTON'				    => 'Show button ',
	'ACP_REQTPL_SHOW_BUTTON_EXPLAIN'				    => 'This option does not change the display of the disabled template',
	'ACP_REQTPL__SHOW_BUTTON_OPTIONS_1'				    => 'Only when creating a new topic',
	'ACP_REQTPL__SHOW_BUTTON_OPTIONS_2'				    => 'For each post',

    'ACP_REQTPL_CANNOT_APPLY_TO_CATEGORY'	=> 'Request template cannot be set for category',
	'ACP_REQTPL_DELETE_FORUM_TPL'			=> 'Delete request template',
	'ACP_REQTPL_DELETED'					=> 'Request template was deleted',
	'ACP_REQTPL_SAVE_FORUM_TPL'				=> 'Save request template',
	'ACP_REQTPL_CREATE_FORUM_TPL'			=> 'Create request template',
	'ACP_REQTPL_ADD_FIELD'					=> 'Add field',
	'ACP_REQTPL_NO_TPL'						=> '',
	'ACP_REQTPL_SAVED'						=> 'Request template was saved',
	'ACP_REQTPL_FIELD_NOT_EXISTS'			=> 'Required field not exists',
	'ACP_REQTPL_FIELD_OPTIONS'				=> 'Field setting',
	'ACP_REQTPL_FIELD_OPTIONS_EXPLAIN'		=> '',
	'REQTPL_FIELD_NAME'						=> 'Name',
	'REQTPL_FIELD_NAME_EXPLAIN'				=> '',
	'REQTPL_FIELD_COMMENT'					=> 'Comment',
	'REQTPL_FIELD_COMMENT_EXPLAIN'			=> '',
	'REQTPL_FIELD_TYPE'						=> 'Type',
	'REQTPL_FIELD_TYPE_EXPLAIN'				=> '',
	'REQTPL_FIELD_OPTIONS'					=> 'Options',
	'REQTPL_FIELD_OPTIONS_EXPLAIN'			=> 'Create options for the drop-down list (each item on the new line) ',
	'REQTPL_FIELD_IMPORTANT'				=> 'Required field',
	'REQTPL_FIELD_IMPORTANT_EXPLAIN'		=> '',
	'REQTPL_FIELD_SIZE'						=> 'Field size',
	'REQTPL_FIELD_SIZE_EXPLAIN'				=> 'The maximum number of characters. The value must be greater than zero or zero if no restriction is required.',
	'REQTPL_FIELD_MATCH'					=> 'Field format',
	'REQTPL_FIELD_MATCH_EXPLAIN'			=> 'You can specify a regular expression, by which the format of the input value will be checked.Leave this field empty if format checking is not required',
	'REQTPL_FIELD_DEFAULT'					=> 'Default value',
	'REQTPL_FIELD_DEFAULT_EXPLAIN'			=> 'You can specify a value that will be displayed automatically in the template field when filling in',
	
	'REQTPL_FIELD_ADDED'					=> 'Field added',
	'REQTPL_FIELD_EDITED'					=> 'Field updated',
	
	'ACP_REQTPL_FIELD_TYPE_INPUT'			=> 'String',
	'ACP_REQTPL_FIELD_TYPE_TEXTAREA'		=> 'Textarea',
	'ACP_REQTPL_FIELD_TYPE_CHECKBOX'		=> 'Checkbox',
	'ACP_REQTPL_FIELD_TYPE_SELECT'			=> 'Dropdown',
	'ACP_REQTPL_FIELD_TYPE_IMAGE'			=> 'Image',
	
	'ACP_REQTPL_MAIN_OPTIONS'				=> 'General setting',
	'ACP_REQTPL_EDIT_FORUM'					=> 'Request template setting for the forum',
	'ACP_REQTPL_SELECT'						=> 'Укажите форум',
	'ACP_REQTPL_SELECT_FORUM'				=> 'Select forum',
	'ACP_REQTPL_NAME'						=> 'Template name',
	'ACP_REQTPL_NAME_EXPLAIN'				=> '',
	'ACP_REQTPL_COMMENT'					=> 'Template explain',
	'ACP_REQTPL_COMMENT_EXPLAIN'			=> '',
	'ACP_REQTPL_SHOW'						=> 'Display on post ',
	'ACP_REQTPL_SHOW_EXPLAIN'				=> 'You can temporarily hide the display of the request template without deleting it',
	
	'ACP_REQTPL_FIELD_NAME'					=> 'FIeld name',
	'ACP_REQTPL_FIELD_TYPE'					=> 'Field type',
	'ACP_REQTPL_FIELD_IMPORTANT'			=> 'Required field',
//    'LIVE_SEARCH_SHOW_FOR_GUEST'				=> 'Show for guests ',
));
