<?php

namespace common\base;
use common\components\BreadcrumbCollection;

/**
 * Расширение класса View
 */
class View extends \yii\web\View {

	/**
	 * @var BreadcrumbCollection
	 */
	public $breadcrumbs;

	/**
	 * @var string
	 */
	public $titleCustom;

	public function init() {
		parent::init();

		$this->breadcrumbs = new BreadcrumbCollection();
		$this->breadcrumbs->addBreadcrumb(['/'], 'Главная');
	}

}