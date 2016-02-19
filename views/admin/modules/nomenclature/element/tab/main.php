<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

/**** active ****/
	
	echo View_Admin::factory('form/checkbox', array(
		'field' => 'active',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm_helper' => $helper_orm,
	));
	
/**** code ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'code',
		'errors' =>	$errors,
		'labels' =>	$labels,
		'required' => $required,
		'controls' => Form::input('code', $orm->code, array(
			'id' => 'code_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** title ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'title',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('title', $orm->title, array(
			'id' => 'title_field',
			'class' => 'input-xxlarge',
		)),
	));
	

/**** sort ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'sort',
		'errors' =>	$errors,
		'labels' =>	$labels,
		'required' => $required,
		'controls' => Form::input('sort', $orm->sort, array(
			'id' => 'sort_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** image_1 ****/
	
	echo View_Admin::factory('form/image', array(
		'field' => 'image_1',
		'value' => $orm->image_1,
		'orm_helper' => $helper_orm,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
	));
	
/**** image_2 ****/
	
	echo View_Admin::factory('form/image', array(
		'field' => 'image_2',
		'value' => $orm->image_2,
		'orm_helper' => $helper_orm,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
	));
	

/**** additional params block ****/
	
	echo View_Admin::factory('form/seo', array(
		'item' => $orm,
		'errors' =>	$errors,
		'labels' => $labels,
		'required' => $required,
	));