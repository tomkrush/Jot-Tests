<?php

class Blog_Serialize_Model extends My_Model 
{	
	public function init()
	{
		$this->validates('title', 'required');
	}
	
	protected function _update() {}
	
	protected function _create()
	{
		$this->new_record = FALSE;
	}
}