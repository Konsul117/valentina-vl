<?php

namespace frontend\modules\blogFront;

use common\models\Image;
use common\modules\blog\Blog;
use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use common\modules\blog\models\BlogPostTag;
use frontend\modules\blogFront\widgets\ImagePostsWidget;
use frontend\modules\blogFront\widgets\PostsWidget;
use frontend\modules\blogFront\widgets\SearchWidget;
use frontend\modules\blogFront\widgets\TagsCloudWidget;
use yii\base\InvalidParamException;

/**
 * Расширение модуля "Блог" для фронтэнда
 */
class BlogFront extends Blog {

	/**
	 * Получение виджета списка постов
	 *
	 * @param int    $limit
	 * @param string $categoryUrl
	 * @return PostsWidget
	 * @throws InvalidParamException
	 */
	public function getPostsWidget($limit = null, $categoryUrl = null) {
		$query = BlogPost::find()
			->where([BlogPost::ATTR_IS_PUBLISHED => true]);

		if ($categoryUrl !== null) {
			$query
				->innerJoinWith(BlogPost::REL_CATEGORY)
				->andWhere(BlogCategory::tableName() . '.' . BlogCategory::ATTR_TITLE_URL . ' = :categoryUrl',
				[':categoryUrl' => $categoryUrl]);
		}

		return new PostsWidget([
			'postsForPage' => $limit,
			'query'        => $query,
		]);
	}

	/**
	 * Получение виджета линейки главных изображений постов
	 *
	 * @param int $limit
	 * @return ImagePostsWidget
	 */
	public function getImagePostsWidget($limit = 10) {
		$query = Image::find()
			->where([Image::tableName() . '.' . Image::ATTR_IS_MAIN => true])
			->orderBy([Image::tableName() . '.' . Image::ATTR_INSERT_STAMP => SORT_DESC]);

		return new ImagePostsWidget([
			'query' => $query,
			'limit' => $limit,
		]);
	}

	/**
	 * Получение виджета поиска постов
	 *
	 * @param int    $limit
	 * @param string $query
	 * @return PostsWidget
	 * @throws InvalidParamException
	 */
	public function getSearchPostsWidget($query, $limit = null) {
		$query = BlogPost::find()
			->where(['like', BlogPost::tableName() . '.' . BlogPost::ATTR_TITLE, $query])
			->orWhere(['like', BlogPost::tableName() . '.' . BlogPost::ATTR_TAGS, $query])
			->andWhere([BlogPost::tableName() . '.' . BlogPost::ATTR_IS_PUBLISHED => true]);

		return new PostsWidget([
			'postsForPage'   => $limit,
			'query'          => $query,
			'showTotalCount' => true,
		]);
	}

	/**
	 * Получение виждета постов поиском по тегу
	 * @param string $tagUrl ЧПУ тега
	 * @param int $limit лимит
	 * @return PostsWidget виджет
	 */
	public function getTagPosts($tagId, $limit = null) {
		$query = BlogPost::find()
			->innerJoin(BlogPostTag::tableName(),
				BlogPostTag::tableName() . '.' . BlogPostTag::ATTR_POST_ID . ' = ' . BlogPost::tableName() . '.' . BlogPost::ATTR_ID
			)
			->where([BlogPostTag::tableName() . '.' . BlogPostTag::ATTR_TAG_ID => $tagId]);

		return new PostsWidget([
			'postsForPage'   => $limit,
			'query'          => $query,
			'showTotalCount' => true,
		]);
	}

	/**
	 * Получение виджета поиска
	 *
	 * @return SearchWidget
	 */
	public function getSearchWidget() {
		return new SearchWidget();
	}

	/**
	 * Получение виджета облака тегов
	 * @return TagsCloudWidget
	 */
	public function getTagsCloudWidget() {
		return new TagsCloudWidget();
	}

}