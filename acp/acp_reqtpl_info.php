<?php
/**
*
* @author Alg
* @version v 1.0.0.
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace alg\reqtpl\acp;

/**
* @ignore
*/
/*if (!defined('IN_PHPBB'))
{
	exit;
}*/

class acp_reqtpl_info
{
	function module()
	{
		return array(
			'filename'	=> '\alg\reqtpl\acp\acp_reqtpl_module',
			'title'		=> 'ACP_REQTPL_SETTINGS',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'reqtpl'			=> array('title' => 'ACP_REQTPL_SETTINGS', 'auth' => 'ext_alg/reqtpl && acl_a_forum', 'cat' => array('ACP_REQTPL')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
