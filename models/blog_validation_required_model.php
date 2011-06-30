<?php

class Blog_Validation_Required_Model extends My_Model
{
	public function init()
	{
		$this->validates('name', 'required');
	}
}