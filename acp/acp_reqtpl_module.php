<?php
/**
*
* @author Alg
* @version 1.0.0.0
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace alg\reqtpl\acp;

/**
* @package acp
*/
class acp_reqtpl_module
{
	var $u_action;
	var $new_config = array();
	const FIRST_POST_ONLY = 0;
	const EACH_POST = 1;


	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $request, $table_prefix, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		if (!defined('REQTPL_FIELDS_TABLE'))
		{
			define('REQTPL_FIELDS_TABLE', $table_prefix . 'reqtpl_fields');
		}
		if (!defined('REQTPL_OPTIONS_TABLE'))
		{
			define('REQTPL_OPTIONS_TABLE', $table_prefix . 'reqtpl_options');
		}
		if (!defined('REQTPL_TEMPLATES_TABLE'))
		{
			define('REQTPL_TEMPLATES_TABLE', $table_prefix . 'reqtpl_templates');
		}
		if (!defined('REQTPL_FIELD_TYPE_INPUT'))
		{
			define('REQTPL_FIELD_TYPE_INPUT', 1);
		}
		if (!defined('REQTPL_FIELD_TYPE_TEXTAREA'))
		{
			define('REQTPL_FIELD_TYPE_TEXTAREA', 2);
		}
		if (!defined('REQTPL_FIELD_TYPE_CHECKBOX'))
		{
			define('REQTPL_FIELD_TYPE_CHECKBOX', 3);
		}
		if (!defined('REQTPL_FIELD_TYPE_SELECT'))
		{
			define('REQTPL_FIELD_TYPE_SELECT', 4);
		}
		if (!defined('REQTPL_FIELD_TYPE_IMAGE'))
		{
			define('REQTPL_FIELD_TYPE_IMAGE', 5);
		}

		$this->tpl_name = 'acp_reqtpl';
		$this->page_title = 'ACP_REQTPL';
		$form_key = 'acp_reqtpl';
		add_form_key($form_key);
		$error = $notify = array();

		$action				= $request->variable('action', '');
		$forum_current_id	= $request->variable('forum_current_id', 0);
		$forum_id			= $request->variable('forum_id', 0);
		$field_id			= $request->variable('field_id', 0);
		$reqtpl_name		= utf8_normalize_nfc(htmlspecialchars_decode($request->variable('reqtpl_name', '', true)));
		$reqtpl_comment		= utf8_normalize_nfc(htmlspecialchars_decode($request->variable('reqtpl_comment', '', true)));
		$reqtpl_show		= $request->variable('reqtpl_show', false);
		$reqtpl_show_options		= $request->variable('reqtpl_show_options', 0);
		if (isset($_POST['submit_create_tpl']) || isset($_POST['create_tpl']))
		{
			$action = 'create_tpl';
		}
		else if (isset($_POST['submit_save_tpl']) || isset($_POST['save_tpl']))
		{
			$action = 'save_tpl';
		}
		else if (isset($_POST['submit_select_forum']))
		{
			$forum_current_id = 0;
		}
		$hidden_fields = '';

		if ($forum_current_id)
		{
			$forum_id = $forum_current_id;
		}
		switch ($mode)
		{
			case 'reqtpl':
				switch ($action)
				{
					case 'create_tpl':
						$back_link = $this->u_action;

						$template->assign_vars(array(
							'U_ACTION'			=> $this->u_action . '&amp;action=add',
							'U_ACTION_TPL'		=> $this->u_action,
							'S_FORUM_OPTIONS'	=> make_forum_select($forum_id, false, true, false, false)
						));

						if (!$forum_id)
						{
							trigger_error($user->lang['NO_FORUM'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$sql = 'SELECT forum_type, forum_name FROM ' . FORUMS_TABLE . ' WHERE forum_id = ' . (int) $forum_id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$back_link = $this->u_action;
						if (!$row)
						{
							trigger_error($user->lang['NO_FORUM'] . adm_back_link($back_link), E_USER_WARNING);
						}
						if ($row['forum_type'] == FORUM_CAT)
						{
							trigger_error($user->lang['ACP_REQTPL_CANNOT_APPLY_TO_CATEGORY'] . adm_back_link($back_link), E_USER_WARNING);
						}

						$template->assign_vars(array(
							'TPL_ID'		=> -1,
							'S_FORUM_NAME'	=> utf8_htmlspecialchars($row['forum_name']),
						));

						$hidden_fields .= build_hidden_fields(array(
							'forum_current_id'		=> $forum_id,
						));
						break;
					case 'save_tpl':
						$template->assign_vars(array(
							'U_ACTION'			=> $this->u_action . '&amp;action=add',
							'U_ACTION_TPL'		=> $this->u_action,
							'S_FORUM_OPTIONS'	=> make_forum_select($forum_id, false, true, false, false)
						));

						if (!$forum_id)
						{
							trigger_error($user->lang['NO_FORUM'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$sql = $db->sql_build_query('SELECT', array(
							'SELECT'	=> 't.tpl_id, f.forum_type, f.forum_name',
							'FROM'		=> array(
								FORUMS_TABLE		=> 'f'
							),
							'LEFT_JOIN'	=> array(
								array(
									'FROM'	=> array(REQTPL_TEMPLATES_TABLE => 't'),
									'ON'	=> 't.tpl_forum_id = f.forum_id'
								)
							),
							'WHERE'		=> 'f.forum_id = ' . (int) $forum_id
						));
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (!$row)
						{
							trigger_error($user->lang['NO_FORUM'] . adm_back_link($this->u_action), E_USER_WARNING);
						}
						if ($row['forum_type'] == FORUM_CAT)
						{
							trigger_error($user->lang['ACP_REQTPL_CANNOT_APPLY_TO_CATEGORY'] . adm_back_link($this->u_action), E_USER_WARNING);
						}
						if ((int) $row['tpl_id'] === 0)
						{
							$db->sql_query('INSERT INTO ' . REQTPL_TEMPLATES_TABLE . ' ' . $db->sql_build_array('INSERT', array(
								'tpl_forum_id'	=> (int) $forum_id,
								'tpl_show'		=> (int) $reqtpl_show,
								'tpl_show_options'		=> (int) $reqtpl_show_options,
								'tpl_name'		=> $reqtpl_name,
								'tpl_comment'	=> $reqtpl_comment
							)));
						}
						else
						{
							$db->sql_query('UPDATE ' . REQTPL_TEMPLATES_TABLE . ' SET ' . $db->sql_build_array('UPDATE', array(
								'tpl_show'		=> (int) $reqtpl_show,
								'tpl_show_options'		=> (int) $reqtpl_show_options,
								'tpl_name'		=> $reqtpl_name,
								'tpl_comment'	=> $reqtpl_comment
							)) . ' WHERE tpl_forum_id = ' . (int) $forum_id);
						}

						$cache->destroy('sql', REQTPL_TEMPLATES_TABLE);

						trigger_error($user->lang['ACP_REQTPL_SAVED'] . adm_back_link($this->u_action . "&amp;forum_id=$forum_id"));
						break;
					case 'edit':
						$sql = 'SELECT tpl_id, field_id, field_order, field_name, field_comment, field_type, field_important, field_size, field_match, field_default, field_pattern FROM ' . REQTPL_FIELDS_TABLE . ' WHERE field_id = ' . $field_id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (!$row)
						{
							trigger_error($user->lang['ACP_REQTPL_FIELD_NOT_EXISTS'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$_options = array();
						if ((int) $row['field_type'] == REQTPL_FIELD_TYPE_SELECT)
						{
							$sql = 'SELECT * FROM ' . REQTPL_OPTIONS_TABLE . ' WHERE field_id = ' . (int) $field_id . ' ORDER BY option_order';
							$field_options = $db->sql_query($sql);
							while ($field_option = $db->sql_fetchrow($field_options))
							{
								$_options[] = utf8_htmlspecialchars($field_option['option_text']);
							}
							$db->sql_freeresult($field_options);
						}

						$template->assign_vars(array(
							'REQTPL_FIELD_NAME'			=> utf8_htmlspecialchars($row['field_name']),
							'REQTPL_FIELD_COMMENT'		=> utf8_htmlspecialchars($row['field_comment']),
							'REQTPL_FIELD_TYPE'			=> $row['field_type'],
							'REQTPL_FIELD_OPTIONS'		=> (sizeof($_options)) ? implode("\n", $_options) : '',
							'REQTPL_FIELD_IMPORTANT'	=> ($row['field_important']) ? true : false,
							'REQTPL_FIELD_SIZE'			=> $row['field_size'],
							'REQTPL_FIELD_MATCH'		=> utf8_htmlspecialchars($row['field_match']),
							'REQTPL_FIELD_DEFAULT'		=> utf8_htmlspecialchars($row['field_default']),
						));

						//no break
					case 'add':
						$template->assign_vars(array(
							'S_EDIT_FIELD'		=> true,
							'U_ACTION'			=> $this->u_action . '&amp;action=' . (($action == 'add') ? 'create' : 'modify') . (($field_id) ? "&amp;field_id=$field_id" : ''),
						));

						$hidden_fields .= build_hidden_fields(array(
							'forum_id'	=> $forum_id,
						));
						break;
					case 'modify':
					case 'create':
						$template->assign_vars(array(
							'S_EDIT_FIELD'		=> true,
							'U_ACTION'			=> $this->u_action . '&amp;action=' . (($action == 'add') ? 'create' : 'modify') . (($field_id) ? "&amp;field_id=$field_id" : ''),
						));

						if (!$forum_id && !$field_id)
						{
							trigger_error($user->lang['NO_FORUM'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						if ($forum_id)
						{
							$sql = 'SELECT tpl_id FROM ' . REQTPL_TEMPLATES_TABLE . ' WHERE tpl_forum_id = ' . (int) $forum_id;
							$result = $db->sql_query($sql);
							$row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);
						}
						else if ($field_id)
						{
							$sql = $db->sql_build_query('SELECT', array(
								'SELECT'	=> 't.tpl_id, t.tpl_forum_id',
								'FROM'		=> array(
									REQTPL_FIELDS_TABLE		=> 'f'
								),
								'LEFT_JOIN'	=> array(
									array(
										'FROM'	=> array(REQTPL_TEMPLATES_TABLE => 't'),
										'ON'	=> 't.tpl_id = f.tpl_id'
									)
								),
								'WHERE'		=> 'f.field_id = ' . (int) $field_id
							));
							$result = $db->sql_query($sql);
							$row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);
							$forum_id = (int) $row['tpl_forum_id'];
						}

						$sql_ary = array(
							'tpl_id'			=> (int) $row['tpl_id'],
							'field_name'		=> htmlspecialchars_decode(utf8_normalize_nfc($request->variable('reqtpl_field_name', '', true))),
							'field_comment'		=> htmlspecialchars_decode(utf8_normalize_nfc($request->variable('reqtpl_field_comment', '', true))),
							'field_type'		=> $request->variable('reqtpl_field_type', 1),
							'field_important'	=> $request->variable('reqtpl_field_important', 0),
							'field_size'		=> $request->variable('reqtpl_field_size', 1),
							'field_match'		=> htmlspecialchars_decode(utf8_normalize_nfc($request->variable('reqtpl_field_match', '', true))),
							'field_default'		=> htmlspecialchars_decode(utf8_normalize_nfc($request->variable('reqtpl_field_default', '', true))),
						);

						$_options = array();
						switch ((int) $sql_ary['field_type'])
						{
							case REQTPL_FIELD_TYPE_IMAGE:
								$sql_ary += array(
									'field_pattern'	=> '[img]%s[/img]',
								);
								break;
							case REQTPL_FIELD_TYPE_SELECT:
								$reqtpl_options = utf8_normalize_nfc(htmlspecialchars_decode($request->variable('reqtpl_field_options', '', true)));
								$_options = explode("\n", trim($reqtpl_options));
								//no break
							default:
								$sql_ary += array(
									'field_pattern'	=> sprintf('[b]%s:[/b] ', $sql_ary['field_name']) . '%s',
								);
								break;
						}

						$db->sql_transaction('begin');

						if ($action == 'create')
						{
							$sql = 'SELECT MAX(field_order) as max_order FROM ' . REQTPL_FIELDS_TABLE . ' WHERE tpl_id = ' . (int) $row['tpl_id'];
							$result = $db->sql_query($sql);
							$_order_info = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);

							$sql_ary += array(
								'field_order'	=> (int) $_order_info['max_order'] + 1,
							);

							$db->sql_query('INSERT INTO ' . REQTPL_FIELDS_TABLE . $db->sql_build_array('INSERT', $sql_ary));
							$cache->destroy('sql', REQTPL_FIELDS_TABLE);
							$field_id = $db->sql_nextid();

							if (sizeof($_options))
							{
								$sql_ary = array();
								$order = 0;
								foreach ($_options as $_option_id => $_option)
								{
									$sql_ary[] = array(
										'field_id'		=> $field_id,
										'option_order'	=> $order++,
										'option_text'	=> $_option,
									);
								}

								$db->sql_multi_insert(REQTPL_OPTIONS_TABLE, $sql_ary);
								$cache->destroy('sql', REQTPL_OPTIONS_TABLE);
							}

							$lang = 'REQTPL_FIELD_ADDED';
						}
						else
						{
							$db->sql_query('UPDATE ' . REQTPL_FIELDS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . ' WHERE field_id = ' . $field_id);
							$cache->destroy('sql', REQTPL_FIELDS_TABLE);

							$db->sql_query('DELETE FROM ' . REQTPL_OPTIONS_TABLE . ' WHERE field_id = ' . $field_id);

							if (sizeof($_options))
							{
								$sql_ary = array();
								$order = 0;
								foreach ($_options as $_option_id => $_option)
								{
									$sql_ary[] = array(
										'field_id'		=> $field_id,
										'option_order'	=> $order++,
										'option_text'	=> trim($_option),
									);
								}

								$db->sql_multi_insert(REQTPL_OPTIONS_TABLE, $sql_ary);
								$cache->destroy('sql', REQTPL_OPTIONS_TABLE);
							}

							$lang = 'REQTPL_FIELD_EDITED';
						}

						$db->sql_transaction('commit');

						trigger_error($user->lang[$lang] . adm_back_link($this->u_action . '&amp;forum_id=' . $forum_id));

						break;
					case 'delete':
						$sql = 'SELECT * FROM ' . REQTPL_FIELDS_TABLE . " WHERE field_id = $field_id";
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (!$row)
						{
							trigger_error($user->lang['ACP_REQTPL_FIELD_NOT_EXISTS'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$sql = $db->sql_build_query('SELECT', array(
							'SELECT'	=> 't.tpl_forum_id',
							'FROM'		=> array(
								REQTPL_FIELDS_TABLE		=> 'f'
							),
							'LEFT_JOIN'	=> array(
								array(
									'FROM'	=> array(REQTPL_TEMPLATES_TABLE => 't'),
									'ON'	=> 't.tpl_id = f.tpl_id'
								)
							),
							'WHERE'		=> 'f.field_id = ' . (int) $field_id
						));
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (confirm_box(true))
						{
							$db->sql_query('DELETE FROM ' . REQTPL_FIELDS_TABLE . " WHERE field_id = $field_id");
							$cache->destroy('sql', REQTPL_FIELDS_TABLE);
						}
						else
						{
							confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
								'forum_id'		=> $forum_id,
							)));
						}

						redirect($this->u_action . '&amp;forum_id=' . (int) $row['tpl_forum_id']);

						break;
					case 'delete_tpl':
						if (!$forum_id)
						{
							trigger_error($user->lang['NO_FORUM'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						if (confirm_box(true))
						{
							$error = '';

							$sql = 'SELECT tpl_id FROM ' . REQTPL_TEMPLATES_TABLE . ' WHERE tpl_forum_id = ' . (int) $forum_id;
							$result = $db->sql_query($sql);
							$tpl_data = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);

							if ($tpl_data)
							{
								$sql = 'SELECT field_id FROM ' . REQTPL_FIELDS_TABLE . ' WHERE tpl_id = ' . (int) $tpl_data['tpl_id'];
								$db->sql_freeresult($result);
								$result = $db->sql_fetchrow($result);
								$fields_ids = array();
								while ($row = $db->sql_fetchrow($result))
								{
									$fields_ids[] = (int) $row['field_id'];
								}
								$db->sql_freeresult($result);

								$db->sql_query('DELETE FROM ' . REQTPL_TEMPLATES_TABLE . ' WHERE tpl_forum_id = ' . (int) $forum_id);
								$db->sql_query('DELETE FROM ' . REQTPL_FIELDS_TABLE . ' WHERE tpl_id = ' . (int) $tpl_data['tpl_id']);
								if (sizeof($fields_ids))
								{
									$db->sql_query('DELETE FROM ' . REQTPL_OPTIONS_TABLE . ' WHERE ' . $db->sql_in_set('field_id', $fields_ids));
								}
							}
							else
							{
								$error = 'ACP_REQTPL_NO_TPL';
							}

							$back_link = $this->u_action . '&amp;forum_id=' . $forum_id;

							if ($error)
							{
								trigger_error($user->lang[$error] . adm_back_link($back_link), E_USER_WARNING);
							}

							trigger_error($user->lang['ACP_REQTPL_DELETED'] . adm_back_link($back_link));
						}
						else
						{
							confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
								'forum_id'		=> $forum_id,
							)));
						}

						redirect($this->u_action . '&amp;forum_id=' . (int) $forum_id);

						break;

					case 'move_up':
					case 'move_down':
						$sql = "SELECT field_order as current_order FROM " . REQTPL_FIELDS_TABLE . " WHERE field_id = $field_id";
						$result = $db->sql_query($sql);
						$current_order = (int) $db->sql_fetchfield('current_order');
						$db->sql_freeresult($result);

						if ($current_order == 0 && $action == 'move_up')
						{
							break;
						}

						$switch_order_id = ($action == 'move_down') ? $current_order + 1 : $current_order - 1;

						$sql = "UPDATE " . REQTPL_FIELDS_TABLE . " SET field_order = $current_order WHERE field_order = $switch_order_id AND field_id <> $field_id";
						$db->sql_query($sql);

						if ($db->sql_affectedrows())
						{
							$sql = "UPDATE " . REQTPL_FIELDS_TABLE . " SET field_order = $switch_order_id WHERE field_order = $current_order AND field_id = $field_id";
							$db->sql_query($sql);
						}

						$sql = $db->sql_build_query('SELECT', array(
							'SELECT'	=> 't.tpl_forum_id',
							'FROM'		=> array(
								REQTPL_FIELDS_TABLE		=> 'f'
							),
							'LEFT_JOIN'	=> array(
								array(
									'FROM'	=> array(REQTPL_TEMPLATES_TABLE => 't'),
									'ON'	=> 't.tpl_id = f.tpl_id'
								)
							),
							'WHERE'		=> 'f.field_id = ' . (int) $field_id
						));
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						redirect($this->u_action . '&amp;forum_id=' . (int) $row['tpl_forum_id']);

						break;
					default:
						$template->assign_vars(array(
							'U_ACTION'			=> $this->u_action . '&amp;action=add',
							'U_ACTION_TPL'		=> $this->u_action,
							'S_FORUM_OPTIONS'	=> make_forum_select($forum_id, false, true, false, false)
						));

						$this->display_reqtpl_info($forum_id);

						$hidden_fields .= build_hidden_fields(array(
							'forum_current_id'	=> $forum_id,
						));
						break;
				}

				break;
		}

		$template->assign_vars(array(
			'S_HIDDEN_FIELDS'	=> $hidden_fields
		));

	///============================================================

	}

	function display_reqtpl_info($forum_id)
	{
		global $db, $user, $template;

		if ($forum_id)
		{
			$sql = $db->sql_build_query('SELECT', array(
				'SELECT'	=> 't.tpl_id, t.tpl_forum_id, t.tpl_show_options, t.tpl_show, t.tpl_name, t.tpl_comment, f.forum_type, f.forum_name',
				'FROM'		=> array(
					FORUMS_TABLE		=> 'f'
				),
				'LEFT_JOIN'	=> array(
					array(
						'FROM'	=> array(REQTPL_TEMPLATES_TABLE => 't'),
						'ON'	=> 't.tpl_forum_id = f.forum_id'
					)
				),
				'WHERE'		=> 'f.forum_id = ' . (int) $forum_id
			));
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$back_link = $this->u_action;
			if (!$row)
			{
				trigger_error($user->lang['NO_FORUM'] . adm_back_link($back_link), E_USER_WARNING);
			}
			if ($row && (int) $row['forum_type'] == FORUM_CAT)
			{
				trigger_error($user->lang['ACP_REQTPL_CANNOT_APPLY_TO_CATEGORY'] . adm_back_link($back_link), E_USER_WARNING);
			}

			if ((int) $row['tpl_id'])
			{
				$template->assign_vars(array(
					'TPL_ID'		=> $row['tpl_id'],
					'NAME'			=> utf8_htmlspecialchars($row['tpl_name']),
					'COMMENT'		=> utf8_htmlspecialchars($row['tpl_comment']),
					'SHOW_OPTIONS'			=> $row['tpl_show_options'],
					'SHOW'			=> $row['tpl_show'],
					'S_FORUM_NAME'	=> utf8_htmlspecialchars($row['forum_name']),
					'FIRST_POST_ONLY'	=> acp_reqtpl_module::FIRST_POST_ONLY,
					'EACH_POST'	=> acp_reqtpl_module::EACH_POST,
				));

				$sql = 'SELECT field_id, field_order, field_name, field_comment, field_type, field_important, field_size, field_match, field_default, field_pattern FROM ' . REQTPL_FIELDS_TABLE . ' WHERE tpl_id = ' . (int) $row['tpl_id'] . ' ORDER BY field_order';
				$fields_result = $db->sql_query($sql);
				while ($field_row = $db->sql_fetchrow($fields_result))
				{
					$template->assign_block_vars('fields', array(
						'NAME'				=> utf8_htmlspecialchars($field_row['field_name']),
						'TYPE'				=> utf8_htmlspecialchars($field_row['field_type']),
						'S_IMPORTANT'		=> utf8_htmlspecialchars($field_row['field_important']),
						'U_EDIT'			=> $this->u_action . '&amp;action=edit&amp;field_id=' . $field_row['field_id'],
						'U_DELETE'			=> $this->u_action . '&amp;action=delete&amp;field_id=' . $field_row['field_id'],
						'U_MOVE_UP'			=> $this->u_action . '&amp;action=move_up&amp;field_id=' . $field_row['field_id'],
						'U_MOVE_DOWN'		=> $this->u_action . '&amp;action=move_down&amp;field_id=' . $field_row['field_id'],
					));
				}
				$db->sql_freeresult($fields_result);
			}
			$template->assign_vars(array(
				'S_FORUM_ID'		=> $forum_id
			));
		}
	}
}
