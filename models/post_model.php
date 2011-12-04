<?php

class Post_Model extends My_Model
{
	public function init()
	{
		$this->belongs_to('person', array(
			'foreign_key' => 'contributor_id'
		));
		
		$this->belongs_to('editor', array(
			'class_name' => 'Person_Model',
			'foreign_key' => 'editor_id'
		));
	}
}