<?php

class Page_Model extends My_Model 
{	
	public function init()
	{
		$this->belongs_to('blog');
		
		$this->belongs_to('weblog', array(
			'class_name' => 'Blog_Model'
		));
	}
}