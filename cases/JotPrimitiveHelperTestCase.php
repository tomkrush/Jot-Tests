<?php

class JotPrimitiveHelperTestCase extends UnitTestCase
{
	public function __construct()
	{
		$this->load->helper('jot_primitive');
	}
	
	public function test_zero()
	{
		$this->assertFalse(is_blank(0), '0 is not blank');
	}
	
	public function test_true()
	{
		$this->assertFalse(is_blank(true), 'TRUE is not blank');
	}

	public function test_string_with_size()
	{
		$this->assertFalse(is_blank('Test'), 'String "Test" is not blank');
	}
	
	public function test_empty_string()
	{
		$this->assertTrue(is_blank(''), 'Empty string is blank');
	}
	
	public function test_null()
	{
		$this->assertTrue(is_blank(null), 'NULL is blank');
	}
	
	public function test_false()
	{
		$this->assertTrue(is_blank(false), 'FALSE is blank');
	}
}