<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotCollectionTestCase extends JotUnitTestCase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('blog_model'));
		$this->load->helper('jot_form');
		
		$this->blog_model->create(array(
			'name' => 'Blog #1'
		));
		
		$this->blog_model->create(array(
			'name' => 'Blog #2'
		));
	}
	
	public function test_to_string()
	{		
		$blogs = $this->blog_model->all();
		$this->assertTrue($blogs, 'Jot Collection returned as string');
	}
	
	public function test_to_json()
	{		
		$blogs = $this->blog_model->all();
		$this->assertTrue($blogs->to_json(), 'Jot Collection returned as json');
	}
	
	public function test_map()
	{
		$blogs = $this->blog_model->all();		
	
		$map = $blogs->map('id', 'name');
		
		$this->assertEquals(array(1=>'Blog #1', 2=>'Blog #2'), $map, 'Map should be created');
	}
}