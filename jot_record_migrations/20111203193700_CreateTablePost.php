<?php
class CreateTablePost
{
	function up()
	{
		create_table('posts', array(
			array('name' => 'editor_id', 'type' => 'integer'),
			array('name' => 'contributor_id', 'type'=> 'integer'),
			array('name' => 'title', 'type'=> 'string'),
			MIGRATION_TIMESTAMPS
		));
	}
}