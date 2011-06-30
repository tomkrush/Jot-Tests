<?php

class Blog_Validation_Confirm_Model extends My_Model
{
	public function init()
	{
		$this->validates('password', 'confirm');
	}
}