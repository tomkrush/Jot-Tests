<?php

require_once APPPATH.'third_party/jot/classes/jot_unit_test_case.php';

class JotFormTestCase extends JotUnitTestCase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('blog_model', 'article_model', 'page_model'));
		$this->load->helper('jot_form');
	}
	
	public function test_form_open()
	{
		$blog = $this->blog_model->build(array(
			'name' => 'Blog #2',
			'slug' => 'blog' 
		));
		
		$html = form_for($f, $blog, 'http://example.com');
		$expects = '<form action="http://example.com" accept-charset="utf-8" id="blog_form" method="POST">';
		
		$this->assertEquals(htmlentities($expects), htmlentities($html), 'Form open tag');
	}

	public function test_checkbox()
	{
		$blog = $this->blog_model->build(array(
			'name' => 'Blog #2',
			'slug' => 'blog' 
		));
		
		form_for($f, $blog, 'http://example.com');
		
		// Without Attributes
		$html = $f->check_box('name');	
		$expects = "\n".'<input type="hidden" name="blog[name]" value="0" />'."\n".'<input type="checkbox" name="blog[name]" value="1" id="blog_name_field"  />';
		
		$this->assertEquals(htmlentities($expects), htmlentities($html), 'Checkbox tag (no attributes)');

		// With Attributes
		$html = $f->check_box('name', array('class'=>'test'));	
		$expects = "\n".'<input type="hidden" name="blog[name]" value="0" />'."\n".'<input type="checkbox" name="blog[name]" value="1" class="test" id="blog_name_field"  />';
		
		$this->assertEquals(htmlentities($expects), htmlentities($html), 'Checkbox tag (attributes)');
	}
		
	public function test_file_field()
	{
		$blog = $this->blog_model->build(array(
			'name' => 'Blog #2',
			'slug' => 'blog' 
		));
		
		form_for($f, $blog, 'http://example.com');
		
		$html = $f->file_field('name');
		$expects = '<input type="file" name="blog[name]" value="" id="blog_name_field"  />';
		
		$this->assertEquals(htmlentities($expects), htmlentities($html), 'File field');		
	}
	
	public function test_select_default_0()
	{
		$blog = $this->blog_model->build(array(
			'name' => 'Blog #2'
		));
	
		form_for($f, $blog, 'http://example.com');
		
		$html = $f->select('type', array(
			'0' => 'bar',
			'1' => 'foo'
		), array(), 0);
		
		$expects = '<select name="blog[type]" id="blog_type_field" >
<option value="0" selected="selected">bar</option>
<option value="1">foo</option>
</select>';
		
		$this->assertEquals(htmlentities($expects), htmlentities($html), 'Select field with default of 0');		
	}

	public function test_hidden_field()
	{	
		$blog = $this->blog_model->build(array(
			'name' => 'Blog #2',
			'slug' => 'blog' 
		));
		
		form_for($f, $blog, 'http://example.com');
		
		$html = $f->hidden_field('name');
		$expects = '<input type="hidden" name="blog[name]" value="Blog #2" />';
		
		$this->assertEquals(htmlentities($expects), trim(htmlentities($html)), 'Hidden field');		
	}
}