<?php
/**
*
* @package reqtpl
* @copyright (c) 2014 alg 
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace alg\reqtpl;

/**
* Main extension class for this extension.
*/
class ext extends \phpbb\extension\base
{
	/**
	* Overwrite enable_step to enable notifications
	* before any included migrations are installed.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Enable notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->enable_notifications('alg.thanksforposts.notification.type.reqtpl_manager');
				$phpbb_notifications->enable_notifications('alg.thanksforposts.notification.type.reqtpl_user');
				return 'notifications';

			break;

			default:

				// Run parent enable step method
				return parent::enable_step($old_state);

			break;
		}
	}

	/**
	* Overwrite disable_step to disable notifications
	* before the extension is disabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Disable notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->disable_notifications('alg.thanksforposts.notification.type.reqtpl_manager');
				$phpbb_notifications->disable_notifications('alg.thanksforposts.notification.type.reqtpl_user');
				return 'notifications';

			break;

			default:

				// Run parent disable step method
				return parent::disable_step($old_state);

			break;
		}
	}

	/**
	* Overwrite purge_step to purge notifications before
	* any included and installed migrations are reverted.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function purge_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Purge notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->purge_notifications('alg.thanksforposts.notification.type.reqtpl_manager');
				$phpbb_notifications->purge_notifications('alg.thanksforposts.notification.type.reqtpl_user');
				return 'notifications';

			break;

			default:

				// Run parent purge step method
				return parent::purge_step($old_state);

			break;
		}
	}
}
