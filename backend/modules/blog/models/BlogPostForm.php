<?php


namespace backend\modules\blog\models;


use common\exceptions\ModelSaveException;
use common\models\Image;
use common\modules\blog\models\BlogPost;
use common\modules\blog\models\BlogPostTag;
use phpQuery;
use Yii;
use yii\base\Exception;

class BlogPostForm extends BlogPost {

	const SCENARIO_UPDATE = 'scenarioUpdate';

	public static function tableName() {
		return BlogPost::tableName();
	}

	public function scenarios() {
		return [
			static::SCENARIO_UPDATE => [
				static::ATTR_TITLE,
				static::ATTR_CONTENT,
				static::ATTR_TAGS,
				static::ATTR_IS_PUBLISHED,
			],
		];
	}

	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if ($this->isNewRecord || ($this->oldAttributes[static::ATTR_TITLE] !== $this->title)) {
			$this->title_url = $this->generateTitleUrl($this->title);
		}

		return true;
	}

	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);

		try {
			BlogPostTag::bindPostTags($this);
		}
		catch (ModelSaveException $e) {
			throw new Exception('Исключение при сохранении тегов поста: ' . $e->getMessage(), 0, $e);
		}

		$this->updateMainImage();
	}

	protected function generateTitleUrl($title) {
		$title = preg_replace('/\[([^\]]+)\]/u', '', $title);
		$title = preg_replace('/\(([^\)]+)\)/u', '', $title);

		$translit = [
			'/'  => '-',
			'\\' => '-',
			' '  => '-',
			'а'  => 'a',
			'б'  => 'b',
			'в'  => 'v',
			'г'  => 'g',
			'д'  => 'd',
			'е'  => 'e',
			'ё'  => 'yo',
			'ж'  => 'zh',
			'з'  => 'z',
			'и'  => 'i',
			'й'  => 'j',
			'к'  => 'k',
			'л'  => 'l',
			'м'  => 'm',
			'н'  => 'n',
			'о'  => 'o',
			'п'  => 'p',
			'р'  => 'r',
			'с'  => 's',
			'т'  => 't',
			'у'  => 'u',
			'ф'  => 'f',
			'х'  => 'x',
			'ц'  => 'c',
			'ч'  => 'ch',
			'ш'  => 'sh',
			'щ'  => 'shh',
			'ы'  => 'y',
			'э'  => 'e',
			'ю'  => 'yu',
			'я'  => 'ya',
			'ь'  => '',
			'ъ'  => '',
			'-'  => '-',
		];

		$title = mb_strtolower($title, 'UTF-8');

		$title = str_replace(array_keys($translit), array_values($translit), $title);

		$title = preg_replace('/[^\-_A-z0-9]/u', '', $title);
		$title = str_replace('-[]', '', $title);
		$title = str_replace('[', '', $title);
		$title = str_replace(']', '', $title);
		$title = trim($title, '-');
		$title = preg_replace('/-+/', '-', $title);

		return $title;
	}

	/**
	 * Обновление главного изображения поста
	 */
	protected function updateMainImage() {
		$images = $this->images;

		if (empty($images)) {
			return ;
		}

		$doc = phpQuery::newDocumentHTML($this->content);

		$imagesIds = [];

		foreach ($doc->find('img[data-image-id]') as $imgEl) {
			$imageId = (int) $imgEl->getAttribute('data-image-id');
			if (!$imageId) {
				continue;
			}

			$imagesIds[] = $imageId;
		}

		if (empty($imagesIds)) {
			return ;
		}

		foreach($images as $image) {
			//удаляем те картинки, которых нет в посте
			if (!in_array($image->id, $imagesIds)) {
				$image->delete();
			}
		}

		reset($imagesIds);

		$firstImageId = current($imagesIds);

		if (isset($images[$firstImageId])) {
			$id = $this->id;
			$command = Yii::$app->db->createCommand('UPDATE ' . Image::tableName()
				. ' SET ' . Image::ATTR_IS_MAIN . ' = 0'
				. ' WHERE ' . Image::ATTR_RELATED_ENTITY_ITEM_ID . ' = :post_id')
				->bindParam(':post_id', $id);

			$command->execute();
			$images[$firstImageId]->is_main = true;
			$images[$firstImageId]->save();
		}
	}

}