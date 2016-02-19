<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules_Nomenclature extends Controller_Admin_Front {

	protected $module_config = 'nomenclature';
	protected $menu_active_item = 'modules';
	protected $title = 'Nomenclature';
	protected $sub_title = 'Nomenclature';
	protected $product_id;
	
	protected $controller_name = array(
		'element' => 'nomenclature_element',
	);
	
	public function before()
	{
		parent::before();
	
		$request = $this->request;
		$query_controller = $request->query('controller');
		if ( ! empty($query_controller) AND is_array($query_controller)) {
			$this->controller_name = $request->query('controller');
		}
		$this->template
			->bind_global('CONTROLLER_NAME', $this->controller_name);
		
		$this->product_id = (int) $request->query('product');
		$this->template
			->bind_global('PRODUCT_ID', $this->product_id);
			
		$this->title = __($this->title);
		$this->sub_title = __($this->sub_title);
	}
	
	protected function layout_aside()
	{
		$menu_items = array_merge_recursive(
			Kohana::$config->load('admin/aside/nomenclature')->as_array(),
			$this->menu_left_ext
		);
		
		return parent::layout_aside()
			->set('menu_items', $menu_items)
			->set('replace', array(
				'{PRODUCT_ID}' => $this->product_id,
			));
	}

	protected function left_menu_element_list()
	{
		if (empty($this->back_url)) {
			$query_array = array(
				'product' => $this->product_id,
			);
			$link = Route::url('modules', array(
				'controller' => $this->controller_name['element'],
				'query' => Helper_Page::make_query_string($query_array),
			));
		} else {
			$link = $this->back_url;
		}
		
		$this->menu_left_add(array(
			'nomenclature' => array(
				'title' => __('Nomenclature list'),
				'link' => $link,
				'sub' => array(),
			),
		));
	}
	
	protected function left_menu_element_add()
	{
		$query_array = array(
			'product' => $this->product_id,
		);
		$this->menu_left_add(array(
			'nomenclature' => array(
				'sub' => array(
					'add' => array(
						'title' => __('Add nomenclature'),
						'link' => Route::url('modules', array(
							'controller' => $this->controller_name['element'],
							'action' => 'edit',
							'query' => Helper_Page::make_query_string($query_array),
						)),
					),
				),
			),
		));
	}
	
	protected function _get_breadcrumbs()
	{
		if (empty($this->back_url)) {
			$query_array = array(
				'product' => $this->product_id,
			);
			$link = Route::url('modules', array(
				'controller' => $this->controller_name['element'],
				'query' => Helper_Page::make_query_string($query_array),
			));
		} else {
			$link = $this->back_url;
		}
		
		return array(
			array(
				'title' => __('Nomenclature'),
				'link' => $link,
			)
		);
	}
}

