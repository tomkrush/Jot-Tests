<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotRecordFindTestCase extends JotUnitTestCase
{
	public function __construct()
	{		
		parent::__construct();
		
		$this->load->model(array('blog_model', 'article_model', 'page_model'));

		$this->truncate('blogs', 'articles', 'pages');

		$this->build();
	}
	
	public function build()
	{
		for($i = 0; $i < 20; $i++)
		{
			$this->blog_model->create(array(
				'name' => 'Blog #'.$i,
				'slug' => 'blog_'.$i,
				'status' => $i != 0 && $i % 3 == 0 ? 'draft' : 'published' 
			));
		}
		
		$this->page_model->create(array(
			'name' => 'Homepage',
			'slug' => 'index'
		));
	}
	
	public function build_articles()
	{
		$blogs = $this->blog_model->all();
		
		foreach($blogs as $blog)
		{
			for ($i=0; $i < 5; $i++)
			{
				$this->article_model->create(array(
					'blog_id' => $blog->id
				));
			}
		}
	}
	
	public function test_exists()
	{
		$exists = $this->blog_model->exists(array(
			'name' => 'Blog #1'
		));
		
		$this->assertTrue($exists, 'Blog does exist');
	}
	
	public function test_not_exists()
	{
		$exists = $this->blog_model->exists(array(
			'name' => 'Blog'
		));
		
		$this->assertFalse($exists, 'Blog does not exist');
	}
	
	public function test_first()
	{
		$blog = $this->blog_model->first();
		$this->assertTrue($blog, 'Blog is returned');

		$blog = $this->blog_model->first(1);
		$this->assertEquals('Blog #0', $blog->name, 'Blog is returned with id');
		
		$blog = $this->blog_model->first(array('name' => 'Blog #1'));
		$this->assertTrue($blog, 'Blog is returned with conditions');
	}
	
	public function test_last()
	{
		$blog = $this->blog_model->first();
		$this->assertTrue($blog, 'Blog is returned');

		$blog = $this->blog_model->last(20);
		$this->assertEquals('Blog #19', $blog->name, 'Blog is returned with id');
		
		$blog = $this->blog_model->last(array('name' => 'Blog #1'));
		$this->assertTrue($blog, 'Blog is returned with conditions');		
	}
	
	public function test_all()
	{
		$blogs = $this->blog_model->all();

		$this->assertEquals(20, count($blogs), 'Blog should return specified number rows');
		
		$blogs = $this->blog_model->all(array('id <' => 4));
		$this->assertEquals(3, count($blogs), 'Blog should return specified number rows');
	}
	
	public function test_find()
	{
		$blogs = $this->blog_model->find(NULL, 0, 20);
		$this->assertEquals(20, count($blogs), 'Blog should return specified number rows');

		$blogs = $this->blog_model->find(NULL, 0, 10);
		$this->assertEquals(10, count($blogs), 'Limit affects return');

		$blogs = $this->blog_model->find(array('id <' => 7), 1, 5);
		$this->assertEquals(5, count($blogs), 'Condition and limit will affect returned result');
	}
	
	public function test_find_if_null()
	{
		$blogs = $this->blog_model->find(array(
			'description' => NULL
		));
		
		$this->assertTrue($blogs, 'NULL should act as SQL IS NULL');
	}
	
	public function test_includes()
	{
		$this->build_articles();
	
		JotIdentityMap::clear();
				
		$blogs = $this->blog_model->find(array(
			'includes'=>'articles'
		));
		
		$count = JotIdentityMap::object_count();
		
		$this->assertEquals(120, $count, 'Include should return correct number of objects.');
	}

	public function test_find_by()
	{
		$this->build_articles();
		JotIdentityMap::clear();
		
		$blogs = $this->blog_model->find_by_status('draft');

		$this->assertEquals(6, count($blogs), 'I want find by x to return rows without using extra syntax.');
	}
	
	public function test_first_by()
	{
		$blog = $this->blog_model->first_by_status('draft');
		
		$this->assertEquals('Blog #3', $blog->name, 'I want first by x to return rows without using extra syntax.');
	}
	
	public function test_last_by()
	{
		$blog = $this->blog_model->last_by_status('draft');
		
		$this->assertEquals('Blog #18', $blog->name, 'I want last by x to return rows without using extra syntax.');
	}
	
	public function test_order()
	{
		$blog = $this->blog_model->first(array('order'=>'slug ASC'));
		$this->assertEquals('blog_0', $blog->slug, 'I want slug to order ascending.');
	
		$blog = $this->blog_model->first(array('order'=>'slug DESC'));
		$this->assertEquals('blog_9', $blog->slug, 'I want slug to order descending.');			
	}
}