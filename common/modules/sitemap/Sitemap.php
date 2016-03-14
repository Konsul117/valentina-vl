<?php

namespace common\modules\sitemap;

use common\base\Module;
use common\components\Formatter;
use common\exceptions\ImageException;
use common\modules\blog\models\BlogPost;
use common\modules\image\models\ImageProvider;
use DOMDocument;
use DOMElement;
use frontend\modules\blogFront\components\PostOutHelper;
use yii\helpers\Url;

class Sitemap extends Module {

	/**
	 * Генерация sitemap
	 *
	 * @return DOMDocument
	 */
	public function generateSitemap() {
		$dom = new DOMDocument('1.0', 'utf-8');

		$urlset = $dom->createElement('urlset');
		$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$urlset->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
		$dom->appendChild($urlset);

		$this->generateBlogPosts($dom, $urlset);

		return $dom;
	}

	/**
	 * Генерация sitemap-элементов для постов блога
	 *
	 * @param DOMDocument $dom
	 * @param DOMElement  $urlset
	 * @throws \common\exceptions\ImageException
	 */
	protected function generateBlogPosts(DOMDocument $dom, DOMElement $urlset) {
		$formatter = new Formatter();

		/** @var BlogPost[] $posts */
		$posts = BlogPost::find()
			->orderBy([
				BlogPost::ATTR_INSERT_STAMP => SORT_DESC,
			])
			->all();

		foreach ($posts as $post) {
			$url = $dom->createElement('url');

			$url->appendChild($dom->createElement('loc'))
				->appendChild($dom->createTextNode(Url::to(['/blogFront/posts/view', 'title_url' => $post->title_url],
					true)));

			$url->appendChild($dom->createElement('lastmod'))
				->appendChild($dom->createTextNode($formatter->asLocalDate($post->update_stamp, 'Y-m-d')));

			foreach ($post->images as $image) {
				try {
					$t = $dom->createTextNode($image->getImageUrl(ImageProvider::FORMAT_FULL));
				}
				catch (ImageException $e) {
					continue;
				}

				$imageNode = $dom->createElement('image:image');

				$imageLocNode = $dom->createElement('image:loc');
				$imageLocNode->appendChild($dom->appendChild($t));
				$imageNode->appendChild($imageLocNode);

				if ($image->title) {
					$clearedTitle = PostOutHelper::clearString($image->title);
				}
				else {
					$clearedTitle = PostOutHelper::clearString($post->title);
				}

				if ($clearedTitle) {
					$imageCaptionNode = $dom->createElement('image:caption');
					$imageCaptionNode->appendChild($dom->appendChild(
						$dom->createTextNode($clearedTitle)
					));
					$imageNode->appendChild($imageCaptionNode);

					$imageTitleNode = $dom->createElement('image:title');
					$imageTitleNode->appendChild($dom->appendChild(
						$dom->createTextNode($clearedTitle)
					));
					$imageNode->appendChild($imageTitleNode);
				}

				$url->appendChild($imageNode);
			}

			$urlset->appendChild($url);
		}
	}

}