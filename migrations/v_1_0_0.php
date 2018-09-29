<?php
/**
*
* @package reqtpl
* @copyright (c) alg
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace alg\reqtpl\migrations;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	const OFF = 0;
	const ON = 1;

	public function effectively_installed()
	{
		return isset($this->config['reqtpl']) && version_compare($this->config['reqtpl'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return 	array(
		'add_tables' => array(
				$this->table_prefix . 'reqtpl_fields' => array(
					'COLUMNS'		=> array(
						'tpl_id'		=> array('UINT',  NULL),
						'field_id'		=>  array('UINT', NULL, 'auto_increment'),
						'field_order'		=> array('UINT', 0),
						'field_name'		=> array('VCHAR:60', ''),
						'field_comment'		=> array('VCHAR:500', ''),
						'field_type'		=> array('UINT', 0),
						'field_important'		=> array('TINT:1', 0),
						'field_size'		=> array('UINT', 0),
						'field_match'		=> array('VCHAR:200', ''),
						'field_default'		=> array('VCHAR:500', ''),
						'field_pattern'		=> array('VCHAR:1000', '%s'),
					),
					'PRIMARY_KEY'	=> array('field_id'),
					'KEYS'			=> array(
						//'field_id'			=> array('UNIQUE', 'field_id'),
						'tpl_id'			=> array('INDEX', 'tpl_id'),
					),
				),
				$this->table_prefix . 'reqtpl_options' => array(
					'COLUMNS'		=> array(
						'field_id'		=>  array('UINT', NULL),
						'option_id'		=>  array('UINT', NULL, 'auto_increment'),
						'option_order'		=> array('UINT', 0),
						'option_text'		=> array('VCHAR:100', ''),
					),
					'PRIMARY_KEY'	=> array('option_id'),
					'KEYS'			=> array(
						'field_id'			=> array('INDEX', 'field_id'),
					),
				),
				$this->table_prefix . 'reqtpl_templates' => array(
					'COLUMNS'		=> array(
						'tpl_id'		=>  array('UINT', NULL, 'auto_increment'),
						'tpl_forum_id'		=> array('UINT', NULL),
						'tpl_show'		=>array('BOOL', 1),
						'tpl_name'		=> array('VCHAR:60', ''),
						'tpl_comment'		=> array('VCHAR:500', ''),
					),
					'PRIMARY_KEY'	=> array('tpl_id'),
					'KEYS'			=> array(
						'f_id'			=> array('UNIQUE', 'tpl_forum_id'),
					),
				),
			),
		);
	}

	public function revert_schema()
	{
		return 	array(
		//    'drop_tables'	=> array($this->table_prefix . 'reqtpl_fields'),
		//    'drop_tables'	=> array($this->table_prefix . 'reqtpl_options'),
		//    'drop_tables'	=> array($this->table_prefix . 'reqtpl_templates'),
		);
	}

	public function update_data()
	{
		return array(
			//  Remove old config
			// Current version
			array('config.add', array('reqtpl', '1.0.0')),

			// Remove old ACP modules
			
			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REQTPL')),
			
			array('module.add', array('acp', 'ACP_REQTPL', array(
					'module_basename'	=> '\alg\reqtpl\acp\acp_reqtpl_module',
					'module_langname'	=> 'ACP_REQTPL_SETTINGS',
					'module_mode'		=> 'reqtpl',
					'module_auth'		=> 'ext_alg/reqtpl && acl_a_board',
			))),
		//    array('module.add', array(
		//        'acp',
		//        'ACP_CAT_DATABASE',
		//        array(
		//            'module_basename'    => '\alg\reqtpl\acp\acp_reqtpl_module',
		//            'module_langname'    => 'ACP_REQTPL',
		//            'module_mode'        => 'view',
		//            'module_auth'        => 'ext_alg/reqtpl && acl_a_board',
		//        )
		//)),

		);
	}
	public function revert_data()
	{
		return array(
			// remove from configs
			//// Current version
			array('config.remove', array('reqtpl')),

			// remove from ACP modules

			array('if', array(
				array('module.exists', array('acp', 'ACP_REQTPL', array(
					'module_basename'	=> '\alg\reqtpl\acp\acp_reqtpl_module',
					'module_langname'	=> 'ACP_REQTPL_SETTINGS',
					'module_mode'		=> 'reqtpl',
					'module_auth'		=> 'ext_alg/reqtpl && acl_a_board',
					),
				)),
				array('module.remove', array('acp', 'ACP_REQTPL', array(
					'module_basename'	=> '\alg\reqtpl\acp\acp_reqtpl_module',
					'module_langname'	=> 'ACP_REQTPL_SETTINGS',
					'module_mode'		=> 'reqtpl',
					'module_auth'		=> 'ext_alg/reqtpl && acl_a_board',
					),
				)),
			)),

			array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REQTPL')),
			

		);
	}
}
