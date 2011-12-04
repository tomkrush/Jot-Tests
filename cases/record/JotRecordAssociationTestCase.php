<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotRecordAssociationTestCase extends JotUnitTestCase
{
	public function __construct()
	{		
		parent::__construct();
		
		$this->load->model(array(
			'blog_model', 
			'article_model', 
			'page_model',
			'company_model',
			'person_model',
			'image_model',
			'post_model'
		));
	}
	
	public function setup()
	{
		$this->truncate('blogs', 'posts', 'articles', 'pages', 'companies', 'people', 'images');
	}
	
	public function test_has_one_association()
	{
		$blog = $this->blog_model->create(array(
			'name' => 'Blog',
			'slug' => 'blog'
		));
		
		$page = new Page_Model(array(
			'name' => 'Page Name',
			'description' => 'Lorem ipsum dolor sit amet...'
		));
						
		$blog->page = $page;
		
		$page->save();

		$this->assertTrue($blog->page, 'Association exists');
		$this->assertEquals('Lorem ipsum dolor sit amet...', $blog->page->description, 'Contents should be correct');
		$this->assertEquals('Lorem ipsum dolor sit amet...', $blog->webpage->description, 'Contents should be correct');
	}
	
	public function test_has_one_foreign_key_association()
	{
		$person = $this->person_model->create(array(
			'name' => 'John doe'
		));
		
		$post = $this->post_model->create(array(
			'title' => 'Post'
		));
		
		$pending = $this->post_model->create(array(
			'title' => 'Pending'
		));
		
		$person->post = $post;
		$person->pending_post = $post;
		
		$this->assertEquals($post->name, $person->post->name, 'Post name should be correct');
		$this->assertEquals($pending->name, $post->pending->name, 'Pending post name should be correct');	
	}
	
	public function test_belongs_to_association()
	{
		$page = $this->page_model->create(array(
			'name' => 'Page',
			'slug' => 'page' 
		));
	
		$blog = new Blog_Model(array(
			'name' => 'blog',
			'slug' => 'blog'
		));	
		$blog->save();
	
		$page->blog = $blog;
		
		$this->assertEquals('blog', $page->blog->name, 'Names should be the same');
		$this->assertEquals('blog', $page->blog->slug, 'Slugs should be the same');
		
		$this->assertEquals($page->blog->id, $page->weblog->id, 'Class name should return a blog_model');
	}
	
	public function test_belongs_to_custom_foreign_key()
	{
		$editor = $this->person_model->create(array(
			'name' => 'John doe'
		));
		
		$person = $this->person_model->create(array(
			'name' => 'Jane doe'
		));
		
		$post = $this->post_model->create(array(
			'editor_id' => $editor->id,
			'contributor_id' => $person->id
		));
				
		$this->assertEquals($editor->name, $post->editor->name, 'Editor name should be correct');
		$this->assertEquals($person->name, $post->person->name, 'Person name should be correct');
	}
	
	public function test_polypmorphic_has_one_association()
	{
		$person = $this->person_model->create(array(
			'name' => 'John Doe'
		));
				
		$person->image = $this->image_model->create(array('image' => 'image_1.png'));
				
		$image = $person->image;
	
		$person = $image->imageable;
		
		$this->assertEquals('John Doe', $person->name, 'Polymorphic object retrieves parent');
	}
	
	public function test_polypmorphic_has_many_association()
	{
		$company = $this->company_model->create(array(
			'name' => 'Pet Store'
		));
		
		$company->images = array(
			$this->image_model->create(array('image' => 'image_1.png')),
			$this->image_model->create(array('image' => 'image_2.png')),
			$this->image_model->create(array('image' => 'image_3.png')),
		);
				
		$this->assertEquals(3, $company->images->count(), 'Correct number of images returned');
		
		$image = $company->images->first();
	
		$company = $image->imageable;
		
		$this->assertEquals('Company_Model', get_class($company), 'Association should return correct type.');
						
		$this->assertEquals('Pet Store', $company->name, 'Polymorphic object retrieves parent');
	}
	
	public function test_polymorphic_belongs_to_association()
	{
		$image = $this->image_model->create(array('image' => 'image_1.png'));
		
		$company = $this->company_model->create(array(
			'name' => 'Pet Store'
		));
		
		$image->imageable = $company;
		
		$this->assertEquals('Pet Store', $image->imageable->name, 'Polymorphic object retrieves parent');
	}
	
	public function test_association_belong_to_create()
	{
		$page = $this->page_model->create(array(
			'name' => 'Page',
			'slug' => 'Slug'
		));
	
		$this->assertTrue($page->id, 'Page should exist');
	
		$blog = $page->create_blog(array(
			'name' => 'Blog',
			'slug' => 'blog'
		));
		
		$this->assertTrue($blog->id, 'Blog should exist');
	}
	
	public function test_association_belong_to_custom_foreign_key_create()
	{
		$post = $this->post_model->create();
		$this->assertTrue($post->id, 'Page should exist');
		
		$editor = $post->create_editor(array(
			'name' => 'John Doe'
		));
		
		$this->assertTrue($editor->id, 'Editor should exist');
	}
	
	public function test_association_has_one_create()
	{
		$blog = $this->blog_model->create(array(
			'name' => 'Blog',
			'slug' => 'blog'
		));
	
		$this->assertTrue($blog->id, 'Blog should exist');
	
		$page = $blog->create_page(array(
			'name' => 'Page',
			'slug' => 'page'
		));
		
		$this->assertTrue($page->id, 'Page should exist');
	}
	
	public function test_association_has_one_custom_foreign_key_create()
	{
		$person = $this->person_model->create(array(
			'name' => 'John Doe'
		));
			
		$this->assertTrue($person->id, 'Person should exist');

		$pending_post = $person->create_pending_post(array(
			'name' => 'Pending'
		));
				
		$this->assertTrue($pending_post->id, 'Pending post should exist');
	}
	
	public function test_has_many_association()
	{	
		$blog = $this->blog_model->create(array(
			'name' => 'Blog #2',
			'slug' => 'blog' 
		));
		
		$article = $this->article_model->create(array(
			'title' => 'Lorem Ipsum'
		));
	
		$article2 = $this->article_model->create(array(
			'title' => 'Dolar'
		));
	
		$article3 = $this->article_model->create(array(
			'title' => 'Ipsum'
		));
		
		$blog->articles = array($article, $article2);
	
		$this->assertEquals(2, count($blog->articles->all()), 'Correct number of articles returned');
		$this->assertEquals(2, count($blog->posts->all()), 'Correct number of posts returned');
		
		$article = $blog->articles->first();
		
		$this->assertEquals('Article_Model', get_class($article), 'Association should return correct type.');
	}
	
	public function test_has_many_association_custom_foreign_key()
	{
		$blog = $this->blog_model->create(array(
			'name' => 'Blog',
			'slug' => 'blog' 
		));
		
		$article = $this->article_model->create(array(
			'title' => 'Lorem Ipsum'
		));
	
		$article2 = $this->article_model->create(array(
			'title' => 'Dolar'
		));
	
		$article3 = $this->article_model->create(array(
			'title' => 'Ipsum'
		));	
		
		$blog->shorts = array($article, $article2);
		
		$this->assertEquals(2, count($blog->shorts->all()), 'Correct number of posts returned');
	}
	
	public function test_has_many_association_order()
	{
		$blog = $this->blog_model->create(array(
			'name' => 'Blog',
			'slug' => 'blog' 
		));
		
		$article = $this->article_model->create(array(
			'title' => 'Apple'
		));
	
		$article2 = $this->article_model->create(array(
			'title' => 'Orange'
		));	
		
		$blog->articles = array($article, $article2);

		$this->assertEquals($article->title, $blog->desc->first()->title, 'Order works');
		$this->assertEquals($article2->title, $blog->asc->first()->title, 'Order works');

	}
}