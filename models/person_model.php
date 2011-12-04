<?php

class Person_Model extends My_Model
{
	public function init()
	{
		$this->has_one('image', array(
			'as' => 'imageable'
		));
		
		$this->has_one('post', array(
			'foreign_key' => 'contributor_id'
		));
		
		$this->has_one('pending_post', array(
			'foreign_key' => 'editor_id',
			'class_name' => 'Post_Model'
		));
	}
}