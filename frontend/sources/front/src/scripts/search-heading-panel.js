(function($) {

	var BUTTON_OPEN_SELECTOR = '[data-role=search-panel-open]';
	var SEARCH_PANEL_SELECTOR = '[data-role=search-panel]';
	var FADE_LAYER_SELECTOR = '#fade-layer';
	var CLASS_OBJECT_OPENED = 'opened';

	var $searchObject;

	/**
	 * Кнопка открытия-закрытия панели.
	 *
	 * @type {jQuery}
	 */
	var $buttonOpen;

	/**
	 * Панель поиска.
	 *
	 * @type {jQuery}
	 */
	var $searchPanel;

	/**
	 * Слой затемнения.
	 *
	 * @type {jQuery}
	 */
	var $fadeLayer;

	/**
	 * Статус показа панели поиска
	 * @type {boolean}
	 */
	var panelShowState = false;

	var methods = {
		init: function() {
			$searchObject = $(this);
			$buttonOpen = $(BUTTON_OPEN_SELECTOR, $searchObject);
			$searchPanel = $(SEARCH_PANEL_SELECTOR, $searchObject);
			$fadeLayer = $(FADE_LAYER_SELECTOR);

			$buttonOpen.click(function() {

				var newPanelShowState = !panelShowState;

				if (newPanelShowState) {
					$searchPanel.toggleClass('hidden', false);
					$searchObject.toggleClass(CLASS_OBJECT_OPENED, true);
					$fadeLayer.fadeIn('fast');
				}
				else {
					$searchPanel.toggleClass('hidden', true);
					$searchObject.toggleClass(CLASS_OBJECT_OPENED, false);
					$fadeLayer.fadeOut('fast');
				}

				panelShowState = newPanelShowState;

				return false;
			});
		}
	};

	$.fn.searchHeadingPanel = function(method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}
	}
})(jQuery);