<?php
/**
*
* @package reqtpl
* @copyright (c) alg
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace alg\reqtpl\migrations;


class v_1_0_1 extends \phpbb\db\migration\migration
{
	const SHOW_FOR_EACH_POST = 0;
	const SHOW_ONLY_FOR_NEW_TOPIC = 1;
    
	const TOPIC_TITLE_OPTIONS_USERNAME = 0;

	static public function depends_on()
	{
		return array('\alg\reqtpl\migrations\v_1_0_0');
	}

	public function update_schema()
	{
		return 	array(
				'add_columns' => array (
					$this->table_prefix . 'reqtpl_templates' => array  (
                        'tpl_show_options'		=>array('TINT:1', v_1_0_1::SHOW_FOR_EACH_POST),
                        'tpl_topic_title'		=>array('VCHAR:60', ''),
                        'tpl_topic_title_options'		=>array('TINT:1', v_1_0_1::TOPIC_TITLE_OPTIONS_USERNAME),
					)
			)
        );
	}


}
