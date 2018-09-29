<?php
/**
 *
 * @package reqtpl
 * @copyright (c) 2014 alg
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace alg\reqtpl\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	const SHOW_ONLY_FOR_NEW_TOPIC = 0;
	const SHOW_FOR_EACH_POST = 1;
	const REQTPL_FIELD_TYPE_SELECT = 4;

	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request_interface $request, $phpbb_root_path, $php_ext, \phpbb\notification\manager $notification_manager, $notifications_table, $reqtpl_fields_table, $reqtpl_options_table, $reqtpl_templates_table)
	{
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->notification_manager = $notification_manager;
		$this->notifications_table = $notifications_table;
		$this->reqtpl_fields_table = $reqtpl_fields_table;
		$this->reqtpl_options_table = $reqtpl_options_table;
		$this->reqtpl_templates_table = $reqtpl_templates_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_get_post_data'			=> 'viewtopic_get_post_data',
			'core.posting_modify_template_vars'		=> 'posting_modify_template_vars',
			'core.posting_modify_submit_post_after'		=> 'posting_modify_submit_post_after',
			'core.user_setup'		=> 'user_setup',
			'core.submit_post_end'		=> 'submit_post_end',
			//'core.submit_post_end'		=> array('submit_post_end', -102),
			//array('posting_modify_template_vars', -102),
		);
	}
	//display_reqtpl
 public function user_setup($event)
{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
				'ext_name' => 'alg/reqtpl',
				'lang_set' => 'reqtpl',
		);
		$event['lang_set_ext'] = $lang_set_ext;
}

	public function posting_modify_template_vars($event)
	{
		$this->user->add_lang_ext('alg/reqtpl', 'reqtpl');	
		$forum_id = $event['forum_id'];
		$mode = $event['mode'];
		$page_data = $event['page_data'];
		$sql = 'SELECT * FROM ' . $this->reqtpl_templates_table . ' WHERE tpl_show = 1 AND tpl_forum_id = ' . (int)$forum_id;
		$result = $this->db->sql_query($sql);
		$tpl_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
        if (!$tpl_data)
        {
			return;
		}
		$show_only_for_new_topic = false;
        //if ($tpl_data)
		{
			if ((int) $tpl_data['tpl_show_options'] == listener::SHOW_ONLY_FOR_NEW_TOPIC)
			{
				$show_only_for_new_topic = $event['post_id'] == 0 && $event['mode'] == 'post';
				if ($show_only_for_new_topic)
				{
					$page_data['SUBJECT'] = $this->user->data['username'] . ' ' . $this->user->format_date(time() , "d/m/Y H:i");
					$event['page_data'] = $page_data;
				}
				
			}
			$this->template->assign_vars(array(
				'REQ_TPL_ID'		=> (int)$tpl_data['tpl_id'],
				'REQ_TPL_NAME'		=> utf8_htmlspecialchars($tpl_data['tpl_name']),
				'REQ_TPL_COMMENT'	=> utf8_htmlspecialchars($tpl_data['tpl_comment']),
				'REQ_TPL_SHOW_OPTIONS'	=> (int) ($tpl_data['tpl_show_options']),
				'S_SHOW_BUTTON'	=> $event['mode'] == 'edit' ? false : true,
				'S_SHOW_BUTTON_FOR_NEW_TOPIC'	=> (bool) $show_only_for_new_topic,
				'U_FORUM_PATH'	=>  append_sid("{$this->phpbb_root_path}viewforum.$this->php_ext", "f={$forum_id}"),
				
			));
		$sql = 'SELECT * FROM ' . $this->reqtpl_fields_table . ' WHERE tpl_id = ' . (int)$tpl_data['tpl_id'] . ' ORDER BY field_order';
		$result = $this->db->sql_query($sql);
		$display_prefix = '';   //??? todo
		while ($field = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars($display_prefix . 'reqtpl_fields', array(
				'ID'			=> (int)$field['field_id'],
				'NAME'			=> utf8_htmlspecialchars($field['field_name']),
				'COMMENT'		=> utf8_htmlspecialchars($field['field_comment']),
				'TYPE'			=> (int)$field['field_type'],
				'IMPORTANT'		=> (int)$field['field_important'],
				'SIZE'			=> (int)$field['field_size'],
				'MATCH'			=> str_replace(array('\\', "'"), array('\\\\', "\'"), $field['field_match']),
				'DEFAULT'		=> utf8_htmlspecialchars($field['field_default']),
				'PATTERN'		=> str_replace(array('\\', "'"), array('\\\\', "\'"), $field['field_pattern']),
			));
			switch((int)$field['field_type'])
			{
				case listener::REQTPL_FIELD_TYPE_SELECT:
					$sql = 'SELECT * FROM ' . $this->reqtpl_options_table . ' WHERE field_id = ' . (int)$field['field_id'] . ' ORDER BY option_order';
					$field_options = $this->db->sql_query($sql);
					while ($field_option = $this->db->sql_fetchrow($field_options))
					{
						$this->template->assign_block_vars($display_prefix . 'reqtpl_fields.field_options', array(
							'ID'			=> (int)$field_option['option_id'],
							'TEXT'			=> utf8_htmlspecialchars($field_option['option_text']),
							'SELECTED'		=> strtolower($field_option['option_text']) == strtolower($field['field_default']),
						));
					}
					$this->db->sql_freeresult($field_options);
					break;
				default:
					break;
			}
		}
		$this->db->sql_freeresult($result);

    }
   }
    public function submit_post_end($event)
    {
        $mode = $event['mode'];
		$data =  $event['data'];
		$tpl_received = $this->request->variable('tpl_received', 0);
			$user_id_ary = array();
			$usernames = array();
			$user_id_ary[] = (int) $data['poster_id'];
			if (!function_exists('user_get_id_name'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			$res = user_get_id_name($user_id_ary, $usernames);
            $notification_data = array(
					'post_id'   => (int) $data['post_id'],
					'topic_id'   => (int) $data['topic_id'],
					'topic_title'   =>  $data['topic_title'],
					'post_subject'   =>  $mode == 'post' ? $data['topic_title'] : $data['topic_title'],
					'poster_id'   => (int) $data['poster_id'],
					'poster_name'   =>sizeof($usernames) ? $usernames[(int) $data['poster_id']] : '',
					'members_ids'   => $this->set_receivers_ids($user_id_ary),
				);


	}

	public function posting_modify_submit_post_after($event)
	{
		$mode = $event['mode'];
		//print_r('mode = ' . $event['mode'] . '; $tpl_received = ' . $tpl_received);
		//print_r('posting_modify_submit_post_after:mode = ' . $event['mode'] );
		//print_r($event['data']);
		//print_r('post_id = ' . $event['post_id']);
		//print_r('topic_id = ' . $event['topic_id']);
		$tpl_received = $this->request->variable('tpl_received', 0);
		//$post_data = $event['post_data'];
		//print_r($tpl_received);
		//print_r($this->set_receivers_ids($post_data));
		if($tpl_received)
		{
			$post_data = $event['post_data'];
			$data = $event['data'];
			if (!function_exists('user_get_id_name'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			//user_get_id_name($user_id_ary, $usernames, array(USER_NORMAL, USER_FOUNDER, USER_INACTIVE));
			$user_id_ary = array();
			$usernames = array();
			$user_id_ary[] = (int) $data['poster_id'];
			$res = user_get_id_name($user_id_ary, $usernames);
			//print_r($res);
			//print_r($usernames[(int) $data['poster_id']]);
			$notification_data = array(
					'post_id'   => (int) $data['post_id'],
					'topic_id'   => (int) $data['topic_id'],
					'topic_title'   =>  $data['topic_title'],
					'post_subject'   =>  $mode == 'post' ? $data['topic_title'] : $data['topic_title'],
					'poster_id'   => (int) $data['poster_id'],
					'poster_name'   =>sizeof($usernames) ? $usernames[(int) $data['poster_id']] : '',
					'members_ids'   => $this->set_receivers_ids($post_data),
				);
				 
				$this->add_notification($notification_data);
		}
	}
	public function viewtopic_get_post_data($event)
	{
	//print_r('viewtopic_get_post_data');

		$this->user->add_lang_ext('alg/reqtpl', 'reqtpl');	
		$forum_id = $event['forum_id'];
		$sql = 'SELECT * FROM ' . $this->reqtpl_templates_table . ' WHERE tpl_show = 1 AND tpl_forum_id = ' . (int)$forum_id;
	   
		$result = $this->db->sql_query($sql);
		$tpl_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($tpl_data)
		{
			if ((int) $tpl_data['tpl_show_options'] == listener::SHOW_ONLY_FOR_NEW_TOPIC)
			{
				$this->template->assign_vars(array(
					'S_SHOW_BUTTON'	=> false,
				));
				return;
			}
			$this->template->assign_vars(array(
				'REQ_TPL_ID'		=> (int)$tpl_data['tpl_id'],
				'REQ_TPL_NAME'		=> utf8_htmlspecialchars($tpl_data['tpl_name']),
				'REQ_TPL_COMMENT'	=> utf8_htmlspecialchars($tpl_data['tpl_comment']),
				'REQ_TPL_SHOW_OPTIONS'	=> (int) ($tpl_data['tpl_show_options']),
				'S_SHOW_BUTTON'	=> true,
				'SUBJECT'	=> 'qwerty1',
			));
		$sql = 'SELECT * FROM ' . $this->reqtpl_fields_table . ' WHERE tpl_id = ' . (int)$tpl_data['tpl_id'] . ' ORDER BY field_order';
		$result = $this->db->sql_query($sql);
		$display_prefix = '';   //??? todo
		// print_r($sql);
		while ($field = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars($display_prefix . 'reqtpl_fields', array(
			//$this->template->assign_block_vars('reqtpl_fields', array(
				'ID'			=> (int)$field['field_id'],
				'NAME'			=> utf8_htmlspecialchars($field['field_name']),
				'COMMENT'		=> utf8_htmlspecialchars($field['field_comment']),
				'TYPE'			=> (int)$field['field_type'],
				'IMPORTANT'		=> (int)$field['field_important'],
				'SIZE'			=> (int)$field['field_size'],
				'MATCH'			=> str_replace(array('\\', "'"), array('\\\\', "\'"), $field['field_match']),
				'DEFAULT'		=> utf8_htmlspecialchars($field['field_default']),
				'PATTERN'		=> str_replace(array('\\', "'"), array('\\\\', "\'"), $field['field_pattern']),
			));
			switch((int)$field['field_type'])
			{
				case listener::REQTPL_FIELD_TYPE_SELECT:
					$sql = 'SELECT * FROM ' . $this->reqtpl_options_table . ' WHERE field_id = ' . (int)$field['field_id'] . ' ORDER BY option_order';
					$field_options = $this->db->sql_query($sql);
					while ($field_option = $this->db->sql_fetchrow($field_options))
					{
						$this->template->assign_block_vars($display_prefix . 'reqtpl_fields.field_options', array(
							'ID'			=> (int)$field_option['option_id'],
							'TEXT'			=> utf8_htmlspecialchars($field_option['option_text']),
							'SELECTED'		=> strtolower($field_option['option_text']) == strtolower($field['field_default']),
						));
					}
					$this->db->sql_freeresult($field_options);
					break;
				default:
					break;
			}
		}
		$this->db->sql_freeresult($result);

		}
	}

	// Add notifications
	public function add_notification($notification_data, $notification_type_name = 'alg.reqtpl.notification.type.reqtpl_manager')
	{
                    $this->notification_manager->add_notifications($notification_type_name, $notification_data);
	}

	public function notification_exists($reqtpl_data, $notification_type_name)
	{
		$notification_type_id = $this->notification_manager->get_notification_type_id($notification_type_name);
		$sql = 'SELECT notification_id FROM ' . $this->notifications_table . '
			WHERE notification_type_id = ' . (int) $notification_type_id . '
				AND item_id = ' . (int) $reqtpl_data['post_id'];
		$result = $this->db->sql_query($sql);
		$item_id = $this->db->sql_fetchfield('notification_id');
		$this->db->sql_freeresult($result);
		return ($item_id) ?: false;
	}

	public function notification_markread($item_ids)
	{
		// Mark post notifications read for this user in this topic
		$this->notification_manager->mark_notifications_read(array(
			'alg.reqtpl.notification.type.reqtpl_manager',
			'alg.reqtpl.notification.type.reqtpl_user',
		), $item_ids, $this->user->data['user_id']);
	}

    private function set_receivers_ids($post_data)
	{
		$ids = array();
		$result = '';
		if(isset($post_data['forum_type_ticket']) && $post_data['forum_type_ticket'] && isset($post_data['group_id_approve_ticket']) && $post_data['group_id_approve_ticket'])
		{
			$sql= "SELECT user_id FROM " . USER_GROUP_TABLE . " WHERE group_id=" . $post_data['group_id_approve_ticket'];
			$result = $this->db->sql_query($sql);
		}
		else
		{
			$sql = "SELECT user_id FROM " . USERS_TABLE . " WHERE user_type=" . USER_FOUNDER;
			$result = $this->db->sql_query($sql);
		}
		
		while ($row = $this->db->sql_fetchrow($result))
		{
			$ids[] = $row['user_id'];
		}
		$this->db->sql_freeresult($result);
		return $ids;

	}
}
