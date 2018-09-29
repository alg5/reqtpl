<?php
/** 
*
* liveSearch [Russian]
*
* @package liveSearch
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
	'REQTPL_BTN'			=> 'Шаблон запроса',
	'REQTPL_BTN'			=> 'Заполнить анкету',
	'REQTPL_IMPORTANT'		=> 'Обязательно для заполнения',
	'REQTPL_CLOSE'			=> 'Закрыть',
	'REQTPL_INCORRECT'		=> 'Неверный формат',
	'REQTPL_ADD_FIELD'		=> 'Добавить поле',
	'REQTPL_COMMENT'		=> 'Комментарий',
	'REQTPL_PREVIEW'		=> 'Предосмотр анкеты',
	'NOTIFICATION_REQTPL_MANAGER'		=> 'Пользователь %s опубликовал анкету',
	'NOTIFICATION_TYPE_REQTPL_MANAGER'		=> 'Пользователь опубликовал анкету',
    //email
	'REQTPL_SUBG'		=> 'Анкета от ',

    ));
