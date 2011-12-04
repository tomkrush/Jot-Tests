<?php

class Blog_Model extends My_Model 
{	
	public function init()
	{
		$this->has_one('page');	
		
		$this->has_one('webpage', array(
			'class_name' => 'Page_Model'
		));
		
		$this->has_many('articles');
		
		$this->has_many('posts', array(
			'class_name' => 'Article_Model'
		));
		
		$this->has_many('shorts', array(
			'class_name' => 'Article_Model',
			'foreign_key' => 'weblog_id',
		));
		
		$this->has_many('desc', array(
			'class_name' => 'Article_Model',
			'order' => 'title ASC'
		));
		
		$this->has_many('asc', array(
			'class_name' => 'Article_Model',
			'order' => 'title DESC'
		));
	}
	
	public function get_popularity()
	{
		return 10;
	}
	
	public function set_category($attribute, $value)
	{
		$this->write_attribute($attribute, strtoupper($value));
	}
}