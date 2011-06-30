<?php

class Blog_Validation_Length_Model extends My_Model
{
	public function init()
	{
		$this->validates('name', array('length' => array(
			'minimum' => 2,
			'maximum' => 5
		)));
	}
}