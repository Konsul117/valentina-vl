<?php

namespace common\base;
use common\components\BreadcrumbCollection;
use common\components\MetaTagContainer;

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

	/** @var MetaTagContainer */
	public $metaTagContainer;

	public function init() {
		parent::init();

		$this->breadcrumbs = new BreadcrumbCollection();
		$this->breadcrumbs->addBreadcrumb(['/'], 'Главная');

		$this->metaTagContainer = new MetaTagContainer();
	}

}