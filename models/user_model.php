<?php

class User_Model extends My_Model
{
	public function init()
	{
		$this->has_attached_file('avatar');
	}
	
	protected function write_file($file, $attachment)
	{
		
	}
	
	protected function _update() {}
	
	protected function _delete() {}
	
	protected function _create()
	{
		$this->new_record = FALSE;
	}
}