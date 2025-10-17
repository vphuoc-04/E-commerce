<?php
$productValidation = [
	[
		'label' => 'Tên sản phẩm *',
		'name' => 'name',
		'type' => 'text',
		'rules' => [
			'required' => 'Bạn cần nhập tên sản phẩm',
			'maxlength' => [ 'value' => 200, 'message' => 'Tên không vượt quá 200 ký tự' ]
		]
	],
	[
		'label' => 'Mô tả',
		'name' => 'description',
		'type' => 'text',
		'rules' => [ 'maxlength' => [ 'value' => 500, 'message' => 'Mô tả tối đa 500 ký tự' ] ]
	],
	[
		'label' => 'Giá *',
		'name' => 'price',
		'type' => 'text',
		'rules' => [
			'required' => 'Bạn cần nhập giá',
			'pattern' => [ 'value' => '[0-9]+(\.[0-9]{1,2})?', 'message' => 'Giá không hợp lệ' ]
		]
	],
	[
		'label' => 'Danh mục *',
		'name' => 'category_id',
		'type' => 'select',
		'options' => [],
		'placeholder' => 'Chọn danh mục',
		'rules' => [ 'required' => 'Bạn cần chọn danh mục' ]
	],
	[
		'label' => 'Ảnh sản phẩm',
		'name' => 'image',
		'type' => 'file',
		'rules' => []
	],
];

