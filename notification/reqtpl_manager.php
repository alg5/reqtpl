<?php
/**
*
* QUERY TEMPLATE extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace alg\reqtpl\notification;
/**
* Thanks for posts notifications class
* This class handles notifying users when they have been thanked for a post
*/

class reqtpl_manager extends  \phpbb\notification\type\base
{
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'alg.reqtpl.notification.type.reqtpl_manager';
	}

	/**
	* Language key used to output the text
	*
	* @var string
	*/
	protected $language_key = 'NOTIFICATION_REQTPL_MANAGER';
	/**
	* Inherit notification read status from post.
	*
	* @var bool
	*/
	protected $inherit_read_status = true;


	/**
	* Notification option data (for outputting to the user)
	*
	* @var bool|array False if the service should use it's default data
	* 					Array of data (including keys 'id', 'lang', and 'group')
	*/
	public static $notification_option = array(
		'lang'	=> 'NOTIFICATION_TYPE_REQTPL_MANAGER',
		'group'	=> 'NOTIFICATION_GROUP_ADMINISTRATION',
	);
  

	/** @var string */
	protected $notifications_table;

	/** @var \phpbb\user_loader */
	protected $user_loader;

	public function set_notifications_table($notifications_table)
	{
		$this->notifications_table = $notifications_table;
	}

	public function set_user_loader(\phpbb\user_loader $user_loader)
	{
		$this->user_loader = $user_loader;
	}
	/**
	* Is available
	*/
	public function is_available()
	{
		return true;
	}
	/**
	* Get the id of the parent
	*
	* @param array $$reqtpl_data The data from the commandcame actions
	*/
	public static function get_item_parent_id($reqtpl_data)
	{
		return (int) $reqtpl_data['topic_id'];
	}
	/**
	* Get the id of the item
	*
	* @param array $thanks_data The data from the thank
	*/
	public static function get_item_id($reqtpl_data)
	{
		return (int) $reqtpl_data['post_id'];
	}

	/**
	* Find the users who want to receive notifications
	*
	* @param array $thanks_data The data from the thank
	* @param array $options Options for finding users for notification
	*
	* @return array
	*/
    public function find_users_for_notification($reqtpl_data, $options = array())
    {
        $options = array_merge(array(
            'ignore_users'		=> array(),
        ), $options);

        $users = $reqtpl_data['members_ids'];
        return $this->check_user_notification_options($users, $options);
    }

	/**
	* Get the user's avatar
	*/
	public function get_avatar()
	{
		return $this->user_loader->get_avatar($this->get_data('poster_id'));
        
	}

	/**
	* Get the HTML formatted title of this notification
	*
	* @return string
	*/
	public function get_title()
	{
        $txt = $this->user->lang($this->language_key, $this->get_data('poster_name') );
        return $txt;
    }
	/**
	* Users needed to query before this notification can be displayed
	*
	* @return array Array of user_ids
	*/
	public function users_to_query()
	{
		$members = $this->get_data('members_ids');
		$users = array(
			$this->get_data('poster_id'),
		);
		if (is_array($members))
		{
			foreach ($members as $member)
			{
				$users[] = $member;
			}
		}

		return $users;

	}

	/**
	* Get the url to this item
	*
	* @return string URL
	*/
	public function get_url()
	{
		//return append_sid("{$this->phpbb_root_path}schedule1");  
		return append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext, "p={$this->item_id}#p{$this->item_id}");
	}

	/**
	* {inheritDoc}
	*/
	public function get_redirect_url()
	{
		return $this->get_url();
		//return append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext, "t={$this->item_parent_id}&amp;view=unread#unread");
	}

	/**
	* Get email template
	*
	* @return string|bool
	*/
	public function get_email_template()
	{
		return '@alg_reqtpl/mail_to_managers';
	}

	/**
	* Get the HTML formatted reference of the notification
	*
	* @return string
	*/
	public function get_reference()
	{
        //return  $this->get_data('signature_txt');
		return $this->user->lang(
			'NOTIFICATION_REFERENCE',
			censor_text($this->get_data('topic_title'))
		);
	}


	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()  //todo
	{
		//$username = $this->user_loader->get_username($this->get_data('user_id'), 'username');
		if ($this->get_data('poster_name'))
		{
			$username = $this->get_data('poster_name');
		}
		else
		{
			$username = $this->user_loader->get_username($this->get_data('poster_id'), 'username');
		}


		return array(
				'REQTPL_SUBG'	=> htmlspecialchars_decode($this->user->lang['REQTPL_SUBG'. $this->get_data('username')]),
				'USERNAME'		=> htmlspecialchars_decode($this->user->data['username']),
				'POST_SUBJECT'	=> htmlspecialchars_decode(censor_text($this->get_data('post_subject'))),
				'POSTER_NAME'	=> htmlspecialchars_decode($username),
				'U_REQTPL'	=> generate_board_url() . '/viewtopic.' . $this->php_ext . "?p={$this->item_id}#p{$this->item_id}",
		);
	}
        
        
        
        //return array(
        //        'COMMANDGAME_SUBJECT'	=> $this->get_data('email_subject'),
        //        'USERNAME'		=> htmlspecialchars($this->user->data['username']),
        //        'POST_SUBJECT'	=> htmlspecialchars(censor_text($this->get_data('email_subject'))),
        //        'POST_CONTENT'	=> htmlspecialchars($txt),
        //        'POSTER_NAME'	=> htmlspecialchars($username),
        //        'TEAM_NAME'	=> htmlspecialchars($this->get_data('team_forum_name')),
        //        //'U_POST_THANKS'	=> generate_board_url() . '/viewtopic.' . $this->php_ext . "?p={$this->item_id}#p{$this->item_id}",
        //);
//	}

	/**
	* Function for preparing the data for insertion in an SQL query
	* (The service handles insertion)
	*
	* @param array $thanks_data Data from insert_thanks
	* @param array $pre_create_data Data from pre_create_insert_array()
	*
	* @return array Array of data ready to be inserted into the database
	*/
	public function create_insert_array($reqtpl_data, $pre_create_data = array())
	{

        //$this->set_data('team_forum_id', $reqtpl_data['team_forum_id']);
        //$this->set_data('team_forum_name', $reqtpl_data['team_forum_name']);
        //$this->set_data('game_forum_name', $reqtpl_data['game_forum_name']);
        //$this->set_data('from', $reqtpl_data['from']);
        //$this->set_data('team_start_time_new', $reqtpl_data['team_start_time_new']);
        //$this->set_data('members_ids', $reqtpl_data['members_ids']);
        //$this->set_data('action_id', $reqtpl_data['action_id']);
        //$this->set_data('reference_txt', $reqtpl_data['reference_txt']);
        //$this->set_data('action_txt', $reqtpl_data['action_txt']);
        //$this->set_data('signature_txt', $reqtpl_data['signature_txt']);
		$this->set_data('post_id', $reqtpl_data['post_id']);
		$this->set_data('topic_id', $reqtpl_data['topic_id']);
		$this->set_data('topic_title', $reqtpl_data['topic_title']);
		$this->set_data('post_subject', $reqtpl_data['post_subject']);
		$this->set_data('poster_id', $reqtpl_data['poster_id']);
		$this->set_data('poster_name', $reqtpl_data['poster_name']);

		parent::create_insert_array($reqtpl_data, $pre_create_data);
	}

 	/**
	* Function for preparing the data for update in an SQL query
	* (The service handles insertion)
	*
	* @param array $thanks_data Data unique to this notification type
	* @return array Array of data ready to be updated in the database
	*/
	public function create_update_array($reqtpl_data)
	{
		$sql = 'SELECT notification_data
			FROM ' . $this->notifications_table . '
			WHERE notification_type_id = ' . (int) $this->notification_type_id . '
				AND item_id = ' . (int) self::get_item_id($reqtpl_data);
		$result = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($result))
		{
			$data = unserialize($row['notification_data']);
		}
		return $this->create_insert_array($reqtpl_data);
	}
   
}
