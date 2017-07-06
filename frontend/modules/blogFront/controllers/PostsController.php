<?php

namespace frontend\modules\blogFront\controllers;

use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use common\modules\blog\models\BlogTag;
use frontend\modules\blogFront\BlogFront;
use frontend\modules\commentFront\CommentFront;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostsController extends Controller {

	/**
	 * Модуль Блога для фронта
	 *
	 * @var BlogFront
	 */
	protected $blogFrontModule;

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	public function init() {
		parent::init();
		$this->blogFrontModule = Yii::$app->modules['blogFront'];

		if ($this->blogFrontModule === null) {
			throw new InvalidConfigException('Отсутствует модуль BlogFront');
		}
	}

	/**
	 * Главная страница
	 *
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index', [
			'postsWidget' => $this->blogFrontModule->getPostsWidget(),
		]);
	}

	/**
	 * Страница поста блока (просмотр)
	 *
	 * @param string $title_url ЧПУ поста блога
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 * @throws InvalidConfigException
	 */
	public function actionView($title_url) {
		/** @var BlogPost $post */
		$post = BlogPost::findOne([BlogPost::ATTR_TITLE_URL => $title_url]);

		if ($post === null) {
			throw new NotFoundHttpException('Запись не найдена');
		}

		/** @var CommentFront $commentModule */
		$commentModule = Yii::$app->modules['commentFront'];

		if ($commentModule === null) {
			throw new InvalidConfigException('Отсутствует модуль комментариев');
		}

		return $this->render('view', [
			'post'          => $post,
//			'commentWidget' => $commentModule->getCommentWidget(Entity::ENTITY_BLOG_POST_ID, $post->id),
		]);
	}

	/**
	 * Фильтр списка постов по категории
	 *
	 * @param string $category_url ЧПУ категории
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionCategory($category_url) {
		/** @var BlogCategory $category */
		$category = BlogCategory::findOne([BlogCategory::ATTR_TITLE_URL => $category_url]);

		if ($category === null) {
			throw new NotFoundHttpException('Некорректная категория');
		}

		return $this->render('category', [
			'postsWidget' => $this->blogFrontModule->getPostsWidget(null, $category_url),
			'category'    => $category,
		]);
	}

	/**
	 * Поиск постов
	 *
	 * @param string $query Строка-запрос поиска
	 *
	 * @return string
	 */
	public function actionSearch($query) {

		$query = Html::encode($query);

		return $this->render('search', [
			'postsWidget' => $this->blogFrontModule->getSearchPostsWidget($query),
		]);
	}

	/**
	 * Вывод всех тегов
	 *
	 * @return string
	 */
	public function actionTags() {
		$tags = BlogTag::find()
			->orderBy([BlogTag::ATTR_NAME => SORT_ASC])
			->all();

		return $this->render('tags', [
			'tags' => $tags,
		]);
	}

	/**
	 * Поиск постов по тегам
	 *
	 * @param string $tag Тег
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionTag($tag) {
		$tag = Html::encode($tag);
		/** @var BlogTag $tagModel */
		$tagModel = BlogTag::findOne([BlogTag::ATTR_NAME_URL => $tag]);

		if ($tagModel === null) {
			throw new NotFoundHttpException('Тег ' . $tag . ' не существует');
		}

		return $this->render('tag', [
			'tag'         => $tagModel,
			'postsWidget' => $this->blogFrontModule->getTagPosts($tagModel->id),
		]);
	}

}