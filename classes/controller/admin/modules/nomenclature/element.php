<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules_Nomenclature_Element extends Controller_Admin_Modules_Nomenclature {

	public function action_index()
	{
		$orm = ORM::factory('nomenclature');
		
		if ( ! empty($this->product_id)) {
			$orm
				->where('product_id', '=', $this->product_id);
		}
		
		$paginator_orm = clone $orm;
		$paginator = new Paginator('admin/layout/paginator');
		$paginator
			->per_page(20)
			->count($paginator_orm->count_all());
		unset($paginator_orm);
		
		$list = $orm
			->paginator($paginator)
			->find_all();
		
		$this->template
			->set_filename('modules/nomenclature/element/list')
			->set('list', $list)
			->set('paginator', $paginator);
			
		$this->left_menu_element_list();
		$this->left_menu_element_add();
		$this->sub_title = __('List');;
	}

	public function action_edit()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$helper_orm = ORM_Helper::factory('nomenclature');
		$orm = $helper_orm->orm();
		if ( (bool) $id) {
			$orm
				->where('id', '=', $id)
				->find();
		
			if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
				throw new HTTP_Exception_404();
			}
			$this->title = __('Edit nomenclature');
		} else {
			$this->title = __('Add nomenclature');
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'product' => $this->product_id,
			);
			$query_array = Paginator::query($request, $query_array);
			$this->back_url = Route::url('modules', array(
				'controller' => $this->controller_name['element'],
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		
		if ($this->is_cancel) {
			$request
				->redirect($this->back_url);
		}
		
		if (empty($orm->sort)) {
			$orm->sort = 500;
		}

		$errors = array();
		$submit = $request->post('submit');
		if ($submit) {
			try {
				if ( (bool) $id) {
					$orm->updater_id = $this->user->id;
					$orm->updated = date('Y-m-d H:i:s');
					$reload = FALSE;
				} else {
					$orm->creator_id = $this->user->id;
					$reload = TRUE;
				}
				
				$values = $request->post();
				$helper_orm->save($values + $_FILES);
				
				if ($reload) {
					if ($submit != 'save_and_exit') {
						$this->back_url = Route::url('modules', array(
							'controller' => $request->controller(),
							'action' => $request->action(),
							'id' => $orm->id,
							'query' => Helper_Page::make_query_string($request->query()),
						));
					}
						
					$request
						->redirect($this->back_url);
				}
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
			}
		}

		// If add action then $submit = NULL
		if ( ! empty($errors) OR $submit != 'save_and_exit') {
			$properties = $helper_orm->property_list();
			$this->template
				->set_filename('modules/nomenclature/element/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm)
				->set('properties', $properties);
			
			$this->left_menu_element_list();
			$this->left_menu_element_add();
		} else {
			$request
				->redirect($this->back_url);
		}
	}
	
	public function action_delete()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		
		$helper_orm = ORM_Helper::factory('nomenclature');
		$orm = $helper_orm->orm();
		$orm
			->and_where('id', '=', $id)
			->find();
		
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
		
		if ($this->element_delete($helper_orm)) {
			if (empty($this->back_url)) {
				$query_array = array(
					'product' => $this->product_id,
				);
				$query_array = Paginator::query($request, $query_array);
				$this->back_url = Route::url('modules', array(
					'controller' => $this->controller_name['element'],
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
		
			$request
				->redirect($this->back_url);
		}
	}
	
	public function action_dyn_sort()
	{
		$this->auto_render = FALSE;
	
		$request = $this->request->current();
		$id = (int) $request->post('id');
		$field = $request->post('field');
		$value = $request->post('value');
	
		$orm = ORM::factory('nomenclature', $id);
		if (empty($field) OR ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
		try {
			$orm->values(array(
				$field => $value
			))->save();
		} catch (ORM_Validation_Exception $e) {
			throw new HTTP_Exception_500();
		}
	
		Ku_AJAX::send('json', $orm->$field);
	}

	protected function _get_breadcrumbs()
	{
		$breadcrumbs = parent::_get_breadcrumbs();
		
		$request = $this->request->current();
		$action = $request
			->action();
		if (in_array($action, array('edit'))) {
			$id = (int) $request->param('id');
			$element_orm = ORM::factory('nomenclature')
				->where('id', '=', $id)
				->find();
			if ($element_orm->loaded()) {
				switch ($action) {
					case 'edit':
						$_str = ' ['.__('edition').']';
						break;
					case 'view':
						$_str = ' ['.__('viewing').']';
						break;
					default:
						$_str = '';
				}
				
				$breadcrumbs[] = array(
					'title' => $element_orm->title.$_str,
				);
			} else {
				$breadcrumbs[] = array(
					'title' => ' ['.__('new nomenclature').']',
				);
			}
		}
		
		return $breadcrumbs;
	}
} 
