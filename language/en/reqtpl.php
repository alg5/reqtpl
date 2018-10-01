<?php
/**
*
* reqtpl [English]
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
	'REQTPL_BTN'			=> 'Request template',
	'REQTPL_BTN'			=> 'Fill request template',
	'REQTPL_IMPORTANT'		=> 'Required',
	'REQTPL_CLOSE'			=> 'Close',
	'REQTPL_INCORRECT'		=> 'Format is incorrect',
	'REQTPL_ADD_FIELD'		=> 'Add field',
	'REQTPL_COMMENT'		=> 'Comment',
	'REQTPL_PREVIEW'		=> 'Preview',
	'NOTIFICATION_REQTPL_MANAGER'		=> 'User %s created new request',
	'NOTIFICATION_TYPE_REQTPL_MANAGER'		=> 'New request',
	//email
	'REQTPL_SUBG'		=> 'Request from ',
));
