<?php

use common\modules\image\components\ImageThumbCreator;
use common\modules\image\Image;
use common\modules\image\models\ImageProvider;

return [
	'modules' => [
		'image' => [
			'class'      => Image::class,
			'components' => [
				'imageThumbCreator' => [
					'class'       => ImageThumbCreator::class,
					'thumbsSizes' => [

						ImageProvider::FORMAT_THUMB_FRONT_MAIN  => [
							'width'  => 50,
							'height' => 50,
							'crop'   => true,
						],
						ImageProvider::FORMAT_THUMB  => [
							'width'  => 90,
							'height' => 90,
							'crop'   => true,
						],
						ImageProvider::FORMAT_POST_MAIN => [
							'width'     => 170,
							'height'    => 170,
							'watermark' => true,
						],
						ImageProvider::FORMAT_MEDIUM => [
							'width'     => 300,
							'height'    => 300,
							'watermark' => true,
						],
						ImageProvider::FORMAT_FULL   => [
							'width'     => 1000,
							'height'    => 1000,
							'watermark' => true,
						],
					],
				],
			],
		],
	],
];