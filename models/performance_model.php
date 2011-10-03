<?php

class Performance_Model extends MY_Model 
{	
	public function init()
	{
		$this->belongs_to('blog');
	}
}