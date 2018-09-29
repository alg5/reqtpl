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
	'ACP_REQTPL'			                => 'Шаблон запроса',
	'ACP_REQTPL_SETTINGS'				    => 'Настройки шаблона запроса',
	'ACP_REQTPL_EXPLAIN'				    => 'Настройки шаблона',
	'ACP_REQTPL_SHOW_BUTTON'				    => 'Показывать кнопку шаблона',
	'ACP_REQTPL_SHOW_BUTTON_EXPLAIN'				    => 'Эта опция не меняет отображение отключенного шаблона',
	'ACP_REQTPL__SHOW_BUTTON_OPTIONS_1'				    => 'Только при создании новой темы',
	'ACP_REQTPL__SHOW_BUTTON_OPTIONS_2'				    => 'Для каждого поста',

    'ACP_REQTPL_CANNOT_APPLY_TO_CATEGORY'	=> 'Шаблон запроса невозможно задать для категории',
	'ACP_REQTPL_DELETE_FORUM_TPL'			=> 'Удалить шаблон запроса',
	'ACP_REQTPL_DELETED'					=> 'Шаблон запроса удалён',
	'ACP_REQTPL_SAVE_FORUM_TPL'				=> 'Сохранить шаблон',
	'ACP_REQTPL_CREATE_FORUM_TPL'			=> 'Создать шаблон запроса',
	'ACP_REQTPL_ADD_FIELD'					=> 'Добавить поле',
	'ACP_REQTPL_NO_TPL'						=> '',
	'ACP_REQTPL_SAVED'						=> 'Шаблон запроса сохранён',
	'ACP_REQTPL_FIELD_NOT_EXISTS'			=> 'Поле отсутствует',
	'ACP_REQTPL_FIELD_OPTIONS'				=> 'Настройки поля',
	'ACP_REQTPL_FIELD_OPTIONS_EXPLAIN'		=> '',
	'REQTPL_FIELD_NAME'						=> 'Название',
	'REQTPL_FIELD_NAME_EXPLAIN'				=> '',
	'REQTPL_FIELD_COMMENT'					=> 'Комментарий',
	'REQTPL_FIELD_COMMENT_EXPLAIN'			=> '',
	'REQTPL_FIELD_TYPE'						=> 'Тип',
	'REQTPL_FIELD_TYPE_EXPLAIN'				=> '',
	'REQTPL_FIELD_OPTIONS'					=> 'Варианты',
	'REQTPL_FIELD_OPTIONS_EXPLAIN'			=> 'Укажите варианты для выпадающего списка (каждый пункт на новой строчке)',
	'REQTPL_FIELD_IMPORTANT'				=> 'Обязательно для заполнения',
	'REQTPL_FIELD_IMPORTANT_EXPLAIN'		=> '',
	'REQTPL_FIELD_SIZE'						=> 'Размер поля',
	'REQTPL_FIELD_SIZE_EXPLAIN'				=> 'Максимальное количество символов. Значение должно быть больше нуля или нулём, если ограничение не требуется.',
	'REQTPL_FIELD_MATCH'					=> 'Шаблон формата',
	'REQTPL_FIELD_MATCH_EXPLAIN'			=> 'Вы можете указать регулярное выражение, по которому будет проверятся формат вводимого значения. Оставьте поле пыстум, если проверка формата не требуется.',
	'REQTPL_FIELD_DEFAULT'					=> 'Значение по-умолчанию',
	'REQTPL_FIELD_DEFAULT_EXPLAIN'			=> 'Вы можете указать значение, которое будет выведено автоматически в поле шаблона при заполнении',
	
	'REQTPL_FIELD_ADDED'					=> 'Поле добавлено',
	'REQTPL_FIELD_EDITED'					=> 'Поле отредактировано',
	
	'ACP_REQTPL_FIELD_TYPE_INPUT'			=> 'Текстовая строка',
	'ACP_REQTPL_FIELD_TYPE_TEXTAREA'		=> 'Текстовое поле',
	'ACP_REQTPL_FIELD_TYPE_CHECKBOX'		=> 'Галочка',
	'ACP_REQTPL_FIELD_TYPE_SELECT'			=> 'Выпадающий список',
	'ACP_REQTPL_FIELD_TYPE_IMAGE'			=> 'Изображение',
	
	'ACP_REQTPL_MAIN_OPTIONS'				=> 'Основные параметры',
	'ACP_REQTPL_EDIT_FORUM'					=> 'Настройки шаблона запроса для форума',
	'ACP_REQTPL_SELECT'						=> 'Укажите форум',
	'ACP_REQTPL_SELECT_FORUM'				=> 'Выбрать форум',
	
	'ACP_REQTPL_NAME'						=> 'Название',
	'ACP_REQTPL_NAME_EXPLAIN'				=> '',
	'ACP_REQTPL_COMMENT'					=> 'Описание',
	'ACP_REQTPL_COMMENT_EXPLAIN'			=> '',
	'ACP_REQTPL_SHOW'						=> 'Отображать на форме ответа',
	'ACP_REQTPL_SHOW_EXPLAIN'				=> 'Вы можете временно отключить отображение шаблона запроса без его удаления',
	
	'ACP_REQTPL_FIELD_NAME'					=> 'Название',
	'ACP_REQTPL_FIELD_TYPE'					=> 'Тип',
	'ACP_REQTPL_FIELD_IMPORTANT'			=> 'Обязательно',
//    'LIVE_SEARCH_SHOW_FOR_GUEST'				=> 'Показывать для гостей ',
));
