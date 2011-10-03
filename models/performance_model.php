<?php

class Performance_Model extends MY_Model 
{	
	public function init()
	{
		$this->belongs_to('blog');
//		$this->belongs_to('user');
//		$this->belongs_to('page');
//
//		$this->has_many('points');
//		$this->has_many('people');
	}
}