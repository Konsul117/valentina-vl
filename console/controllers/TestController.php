<?php

namespace console\controllers;

use common\models\Image;
use common\modules\blog\models\BlogPost;
use yii\console\Controller;

/**
 * Тесты
 */
class TestController extends Controller {

	public function actionIndex() {
		/** @var BlogPost $post */
		$post = BlogPost::findOne(2);


//		$post->tags = 'тег1 тег2';
		$post->tags = 'тег2 тег1 тегтри';
		$post->save();

//		for ($i = 1; $i < 10; $i++) {
//			$blogPostTag          = new BlogPostTag();
//			$blogPostTag->post_id = 3;
//			$blogPostTag->tag_id  = $i;
//			if ($blogPostTag->save() === false) {
//				print_r($blogPostTag->getErrors());
//			}
//		}
	}

	public function actionStr() {
		$strs = explode(' ', 'qwe  asd   tret ');
		var_dump($strs);
		$strs = array_filter($strs, function($item) {
			return $item !== '';
		});

		var_dump($strs);
	}

	public function actionImgClear() {
		/** @var Image $image */
		$image = Image::findOne(62);

		$image->delete();
	}
}