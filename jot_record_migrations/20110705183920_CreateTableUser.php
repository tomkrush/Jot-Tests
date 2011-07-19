<?php
class CreateTableUser
{
	function up()
	{
		create_table('users', array(
			array('name'=>'avatar_file_name', 'type'=>'string'),
			array('name'=>'avatar_content_type', 'type'=>'string'),
			array('name'=>'avatar_file_size', 'type'=>'integer'),
			array('name'=>'avatar_updated_at', 'type'=>'integer'),
			MIGRATION_TIMESTAMPS
		));
	}
}