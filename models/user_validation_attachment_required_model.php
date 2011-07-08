<?php

class User_Validation_Attachment_Required_Model extends MY_Model
{
	public function init()
	{
		$this->table_name('users');
		$this->has_attached_file('avatar');
		
		$this->validates('avatar', 'attachment_required');
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