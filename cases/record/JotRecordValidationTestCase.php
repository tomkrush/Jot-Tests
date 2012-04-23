<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotRecordValidationTestCase extends JotUnitTestCase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('base_validation_model');
		$this->load->model('blog_validation_required_model'); 
		$this->load->model('blog_validation_confirm_model'); 
		$this->load->model('blog_validation_length_model');
	}
	
	public function test_basic_validate()
	{
		$blog = new Blog_Validation_Model;
		$blog->write_attribute('slug', 'required');
		$blog->write_attribute('title', 'test');
		$blog->write_attribute('status', 'draft');
				
		$this->assertTrue($blog->is_valid(), 'Blog should be valid');
		
		$blog->save();
	}
	
	public function test_no_validate()
	{
		$blog = new Blog_Validation_Model;
		$blog->write_attribute('slug', 'required');
		
		$blog->save(FALSE);
		$this->assertEquals(array(), $blog->errors(), 'There should not be errors');
	}
	
	public function test_required_fail()
	{
		$blog = new Blog_Validation_Required_Model;
		
		$this->assertFalse($blog->is_valid(), "I want validation to fail because name isn't present.");
	}
	
	public function test_required_pass()
	{
		$blog = new Blog_Validation_Required_Model;
		$blog->name = "test";
		
		$this->assertTrue($blog->is_valid(), "I want validation to pass because name is present.");
	}
	
	public function test_required_when_zero_pass()
	{
		$blog = new Blog_Validation_Required_Model;
		$blog->name = 0;
		
		$this->assertTrue($blog->is_valid(), "I want validation to pass because name is present.");
	}
	
	public function test_confirm_fail()
	{
		$blog = new Blog_Validation_Confirm_Model;
		$blog->password = 'missing_confirm';
		$blog->confirm_password = '';
		
		$this->assertFalse($blog->is_valid(), "I want validation to fail because password can't confirm.");
	}
	
	public function test_confirm_pass()
	{
		$blog = new Blog_Validation_Confirm_Model;
		$blog->password = "test";
		$blog->confirm_password = "test";
		
		$this->assertTrue($blog->is_valid(), "I want validation to pass because password can confirm.");
	}
	
	public function test_does_not_confirm_fail()
	{
		$blog = new Blog_Validation_Confirm_Model;
		$blog->password = "test";
		$blog->confirm_password = "test123";
		
		$this->assertFalse($blog->is_valid(), "I want validation to fail because password is not same as confirm.");
	}
	
	public function test_confirm_creates_transient_attribute()
	{
		$blog = new Blog_Validation_Confirm_Model;
		$blog->password = "test";
		$blog->confirm_password = "test";
		$blog->is_valid();

		$this->assertTrue($blog->has_transient('confirm_password'), 'I want the confirm field to be transient.');
	}

	public function test_length_fail()
	{
		$blog = new Blog_Validation_Length_Model;
		$blog->name = 'testtest';

		$this->assertFalse($blog->is_valid(), "I want validation to fail because name is not in range.");
	}
	
	public function test_length_pass()
	{
		$blog = new Blog_Validation_Length_Model;
		$blog->name = 'test';

		$this->assertTrue($blog->is_valid(), "I want validation to pass because name is in range.");
	}
}