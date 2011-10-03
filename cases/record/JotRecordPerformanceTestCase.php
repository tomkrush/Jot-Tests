<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotRecordPerformanceTestCase extends JotUnitTestCase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('performance_model');
		$this->load->model('article_model');
	}
	
	public function test_init()
	{
		for($i = 0; $i < 1000; $i++)
		{
			new performance_model;
		}
	
		$this->assertTrue(TRUE, 'Init Speed');
	}
}