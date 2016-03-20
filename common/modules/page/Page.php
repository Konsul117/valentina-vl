<?php

namespace common\modules\page;

use common\base\Module;
use common\modules\page\models\Page as PageModel;
use Yii;
use yii\caching\TagDependency;

/**
 * Модуль "Страницы"
 */
class Page extends Module {

	/**
	 * Получить модель страницы по Id
	 *
	 * @param int  $id            Id страницы
	 * @param bool $onlyPublished Только опубликованная
	 *
	 * @return PageModel|null
	 */
	public function getPageById($id, $onlyPublished = true) {
		$query = PageModel::find()
			->where([PageModel::ATTR_ID => $id]);

		if ($onlyPublished) {
			$query->andWhere([PageModel::ATTR_IS_PUBLISHED => true]);
		}

		return $query->one();
	}

	/**
	 * Получение ЧПУ страницы по Id
	 *
	 * @param int $id Id страницы
	 *
	 * @return string|null
	 */
	public function getPageUrlById($id) {
		$cacheKey = __METHOD__ . '.id-' . $id .'.v3';
		$result   = Yii::$app->cache->get($cacheKey);

		if ($result === false) {
			$result = null;

			$queryResult = PageModel::find()
				->select(PageModel::ATTR_TITLE_URL)
				->where([PageModel::ATTR_ID => $id])
				->column();

			if (!empty($queryResult)) {
				$result = array_shift($queryResult);
			}

			Yii::$app->cache->set($cacheKey, $result, 3600 * 5, new TagDependency([
				'tags' => [
					PageModel::class,
				],
			]));
		}

		return $result;
	}

}