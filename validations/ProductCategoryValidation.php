<?php
$productCategoryValidation = [
	[
		'label' => 'Tên danh mục *',
		'name' => 'name',
		'type' => 'text',
		'rules' => [
			'required' => 'Bạn cần nhập tên danh mục',
			'maxlength' => [ 'value' => 150, 'message' => 'Tên không vượt quá 150 ký tự' ]
		]
	],
	[
		'label' => 'Mô tả',
		'name' => 'description',
		'type' => 'text',
		'rules' => [
			'maxlength' => [ 'value' => 255, 'message' => 'Mô tả tối đa 255 ký tự' ]
		]
	],
];

