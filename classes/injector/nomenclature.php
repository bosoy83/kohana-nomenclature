<?php defined('SYSPATH') or die('No direct script access.');

class Injector_Nomenclature extends Injector_Base {
	
	private $controller_name = 'nomenclature_element';
	private $tab_code = 'nomenclature';
	
	protected function init() {
		$module_config = Helper_Module::load_config('nomenclature');
		$helper_acl = new Helper_ACL($this->acl);
		$helper_acl->inject(Arr::get($module_config, 'a2'));
	}
	
	public function get_hook($orm)
	{
		return array(
			array($this, 'hook_callback'),
			array($orm->id)
		);
	}
	
	public function hook_callback($content, $product_id)
	{
		$request = $this->request;
		$back_url = $request->url();
		$query_array = $request->query();
		if ( ! empty($query_array)) {
			$back_url .= '?'.http_build_query($query_array);
		}
		$back_url .= '#tab-'.$this->tab_code;
		unset($query_array);
	
		$query_array = array(
			'product' => $product_id,
			'back_url' => $back_url,
			'content_only' => TRUE
		);
		$query_array = Paginator::query($request, $query_array);
		$link = Route::url('modules', array(
			'controller' => $this->controller_name,
			'query' => Helper_Page::make_query_string($query_array),
		));
		
		$html = Request::factory($link)
			->execute()
			->body();
	
		$tab_nav_html = View_Admin::factory('layout/tab/nav', array(
			'code' => $this->tab_code,
			'title' => '<b>'.__('Nomenclature').'</b>',
		));
		$tab_pane_html = View_Admin::factory('layout/tab/pane', array(
			'code' => $this->tab_code,
			'content' => $html
		));
	
		return str_replace(array(
			'<!-- #tab-nav-insert# -->', '<!-- #tab-pane-insert# -->'
		), array(
			$tab_nav_html.'<!-- #tab-nav-insert# -->', $tab_pane_html.'<!-- #tab-pane-insert# -->'
		), $content);
	}
	
	public function menu_list($orm, $tab_mode = TRUE)
	{
		if ($tab_mode) {
			$link = '#tab-'.$this->tab_code;
			$class = 'tab-control';
		} else {
			$link = Route::url('modules', array(
				'controller' => $this->controller_name,
				'query' => 'product='.$orm->id
			));
			$class = FALSE;
		}
		return array(
			'nomenclature' => array(
				'title' => __('Nomenclature'),
				'link' => $link,
				'class' => $class,
				'sub' => array(),
			),
		);
	}
	
	public function menu_add($orm)
	{
		if ($this->acl->is_allowed($this->user, $orm, 'edit') ) {
			$back_url = urlencode($_SERVER['REQUEST_URI'].'#tab-'.$this->tab_code);
	
			return array(
				'nomenclature' => array(
					'sub' => array(
						'add' => array(
							'title' => __('Add nomenclature'),
							'link' => Route::url('modules', array(
								'controller' => $this->controller_name,
								'action' => 'edit',
								'query' => 'product='.$orm->id.'&back_url='.$back_url
							)),
						),
					),
				),
			);
		}
	}
	
}