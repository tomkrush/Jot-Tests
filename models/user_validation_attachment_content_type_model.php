<?php

class User_Validation_Attachment_Content_Type_Model extends MY_Model
{
	public function init()
	{
		$this->table_name('users');
		$this->has_attached_file('avatar');
		
		$this->validates('avatar', array('attachment_content_type' =>  array('image/jpeg', 'image/png')
		));		
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