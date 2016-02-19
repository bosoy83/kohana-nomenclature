<?php defined('SYSPATH') or die('No direct script access.');

class Model_Nomenclature extends ORM_Base {

	protected $_table_name = 'catalog_nomenclatures';
	protected $_sorting = array('product_id' => 'ASC', 'sort' => 'ASC', 'title' => 'ASC');
	protected $_deleted_column = 'delete_bit';
	protected $_active_column = 'active';

	protected $_belongs_to = array(
		'product' => array(
			'model' => 'catalog_Element',
			'foreign_key' => 'product_id',
		),
	);
	
	public function labels()
	{
		return array(
			'product_id' => 'Product',
			'code' => 'Article',
			'title' => 'Title',
			'image_1' => 'Image 1',
			'image_2' => 'Image 2',
			'announcement' => 'Announcement',
			'text' => 'Text',
			'active' => 'Active',
			'sort' => 'Sort',
			'title_tag' => 'Title tag',
			'keywords_tag' => 'Keywords tag',
			'description_tag' => 'Desription tag',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'product_id' => array(
				array('digit'),
			),
			'code' => array(
				array('max_length', array(':value', 255)),
			),
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'image_1' => array(
				array('max_length', array(':value', 255)),
			),
			'image_2' => array(
				array('max_length', array(':value', 255)),
			),
			'sort' => array(
				array('digit'),
			),
			'title_tag' => array(
				array('max_length', array(':value', 255)),
			),
			'keywords_tag' => array(
				array('max_length', array(':value', 255)),
			),
			'description_tag' => array(
				array('max_length', array(':value', 255)),
			),
		);
	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'title' => array(
				array('strip_tags'),
			),
			'active' => array(
				array(array($this, 'checkbox'))
			),
			'title_tag' => array(
				array('strip_tags'),
			),
			'keywords_tag' => array(
				array('strip_tags'),
			),
			'description_tag' => array(
				array('strip_tags'),
			),
		);
	}
	
}
