<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

function set_http_files($object, $attribute, $name, $type, $tmp_name, $error, $size)
{
	$_FILES = array(
		$object => array(
			'name' => array(
				$attribute => $name
			),
			'type' => array(
				$attribute => $type
			),
			'tmp_name' => array(
				$attribute => $tmp_name
			),
			'error' => array(
				$attribute => $error
			),
			'size' => array(
				$attribute => $size
			)
		)
	);	
}

class JotRecordUploadTestCase extends JotUnitTestCase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('user_validation_attachment_required_model');
		$this->load->model('user_validation_attachment_content_type_model');
	}
	
	public function test_upload()
	{
		set_http_files('user', 'avatar', 'avatar.png', 'image/png', '...', 0, '23123');
		
		$file = new User_Model;
		$file->save();
		
		$this->assertEquals('avatar.png', $file->read_attribute('avatar_file_name'), 'I want avatar_file_name to be set.');
		$this->assertEquals('image/png', $file->read_attribute('avatar_content_type'), 'I want avatar_content_type to be set.');
		$this->assertEquals('23123', $file->read_attribute('avatar_file_size'), 'I want avatar_file_size to be set.');
		$this->assertTrue($file->read_attribute('avatar_updated_at'), 'I want avatar_updated_at to be set.');
	}
	
	public function test_validation_required_fail()
	{
		set_http_files('user', 'avatar', NULL, NULL, NULL, 4, NULL);
		
		$file = new User_Validation_Attachment_Required_Model;
		
		$this->assertFalse($file->is_valid() , 'I want validation to fail because attachment is required');
	}

	public function test_validation_required_pass()
	{
		set_http_files('user', 'avatar', 'avatar.png', 'image/png', '...', 0, '23123');
				
		$file = new User_Validation_Attachment_Required_Model;
		
		$this->assertTrue($file->is_valid() , 'I want validation to pass.');
	}
	
	public function test_validation_content_type_fail()
	{
		set_http_files('user', 'avatar', 'file.txt', 'text/plain', '...', 0, '23123');
		
		$file = new User_Validation_Attachment_Content_Type_Model;
		
		$this->assertFalse($file->is_valid() , 'I want validation to fail because attachment is wrong type');
	}

	public function test_validation_content_type_pass()
	{
		set_http_files('user', 'avatar', 'avatar.png', 'image/png', '...', 0, '23123');
		
		$file = new User_Validation_Attachment_Content_Type_Model;
		
		$this->assertTrue($file->is_valid() , 'I want validation to pass.');
	}
}