<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotIdentityMapMock
{
	protected $id;
	
	public function __construct($id)
	{
		$this->id = $id;
	}
	
	public function read_attribute()
	{
		return $this->id;
	}
	
	public function primary_key()
	{
		return 'id';
	}
}

class JotIdentityMapTestCase extends JotUnitTestCase
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('blog_model');
	}
	
	public function teardown()
	{
		$this->truncate('blogs');
	
		JotIdentityMap::clear();
	}
	
	public function test_add()
	{
		$original = new JotIdentityMapMock(1);

		$result = JotIdentityMap::add($original);
		
		$this->assertTrue($result, 'Object is added');
	}
	
	public function test_get()
	{
		$original = new JotIdentityMapMock(1);

		JotIdentityMap::add($original);
				
		$new = JotIdentityMap::get('JotIdentityMapMock', 1);
		$this->assertEquals($original, $new, 'Object is added');		
	}
	
	public function test_exists()
	{
		$original = new JotIdentityMapMock(1);
		
		JotIdentityMap::add($original);

		$this->assertTrue(JotIdentityMap::exists($original), 'Object exists in repository.');				
	}
	
	public function test_does_not_exist()
	{
		$original = new JotIdentityMapMock(1);
		
		$this->assertFalse(JotIdentityMap::exists($original), 'Object does not exist in repository.');		
	}
	
	public function test_count()
	{
		$original = new JotIdentityMapMock(1);

		JotIdentityMap::add($original);
		
		$this->assertEquals(1, JotIdentityMap::count(), 'Repository has 1 object.');				
	}
	
	public function test_duplication()
	{
		$original = new JotIdentityMapMock(1);

		JotIdentityMap::add($original);
		JotIdentityMap::add($original);	
		
		$this->assertNotEquals(2, JotIdentityMap::count(), 'Repository did not duplicate object');	
	}
	
	public function test_clear()
	{	
		$original = new JotIdentityMapMock(1);

		JotIdentityMap::clear();
		$this->assertEquals(0, JotIdentityMap::count(), 'Repository is cleared.');
	}

	public function test_remove_by_id()
	{
		$original = new JotIdentityMapMock(1);
	
		JotIdentityMap::add($original);
		JotIdentityMap::remove_by_id('JotIdentityMapMock', 1);
		$new = JotIdentityMap::get('JotIdentityMapMock', 1);
		$this->assertNotEquals($original, $new, 'Object is removed by id');		
	}

	public function test_remove()
	{	
		$original = new JotIdentityMapMock(1);
	
		JotIdentityMap::add($original);
		JotIdentityMap::remove($original);
		$new = JotIdentityMap::get('JotIdentityMapMock', 1);
		$this->assertNotEquals($original, $new, 'Object is removed');
	}
	
	public function test_object_create_with_no_attributes()
	{
		$blog = new Blog_Model;
				
		$this->assertFalse(JotIdentityMap::exists($blog), "Doesn't add objects without attributes.");		
	}
	
	public function test_object_create_with_no_id()
	{		
		$blog = new Blog_Model(array(
			'title' => 'test',
		));
			
		$this->assertFalse(JotIdentityMap::exists($blog), 'No object added because attributes are present but no id.');		
	}
	
	public function test_object_create_with_id()
	{		
		$blog = new Blog_Model(array(
			'title' => 'test',
			'id'	=> 1
		));
			
		$this->assertTrue(JotIdentityMap::exists($blog), 'Added object to identity map.');
	}
	
	public function test_object_build()
	{
		$createdBlog = new Blog_Model(array(
			'title' => 'test',
			'id'	=> 1
		));
		
		$builtBlog = $this->blog_model->build(array(
			'id' => 1,
			'title' => 'test'
		));
		
		$this->assertEquals($createdBlog, $builtBlog, 'I want build to retrieve object from identity map.');
	}
	
	public function test_object_find()
	{		
		$created_objects = array();
				
		for($i = 0; $i < 1; $i++)
		{
			$created_objects[] = $this->blog_model->create(array(
				'name' => 'Blog #'.$i
			));
		}	
				
		$created_objects[0]->name = 'Blog Awful';
				
		$found_objects = $this->blog_model->all();
		
		$exist = FALSE;
		
		$this->assertEquals($created_objects, (array)$found_objects, 'Find worked');
		$this->assertEquals('Blog Awful', $found_objects[0]->name, 'Yippy!');
	}
}