(function($) {

	var settings = {};

	var $modal;

	var methods = {
		init: function(options) {
			settings = $.extend({
				editor:        null,
				showButtonId:  null,
				previewPaneId: null,
				uploadUrl:     null,
				params:        {}
			}, options);

			this.editor = settings.editor;
			$modal = $(this);

			if (settings.showButtonId !== null) {
				var $button = $('#'+settings.showButtonId);

				if ($button.length > 0) {
					$button.click(function() {
						//this.show();
						methods.show();
					});
				}
			}

			if (settings.uploadUrl !== null) {
				var uploadDropzone = new Dropzone("#uploadModal #uploadDropzone", {
					url:               settings.uploadUrl,
					previewsContainer: false,
					params:            settings.params
				});

				uploadDropzone.on("complete", function(file) {
					if (file.xhr.status !== 200) {
						alert('Произошла ошибка соединения с сервером');
					}


					var response = $.parseJSON(file.xhr.response);

					if (response.success === false) {
						alert(response.error ? response.error : 'Неизвестная ошибка');

						return ;
					}

					if (settings.previewPaneId) {
						var $previewPane = $('#' + settings.previewPaneId);

						if ($previewPane.length > 0) {
							$previewPane.append('<div class="image-item"><img src="' + response.data.urls.thumb + '" data-medium-url="' + response.data.urls.medium + '"/></div>')

							var $form = $previewPane.closest('form');

							if ($form.length > 0) {
								$form.append('<input type="hidden" name="uploaded_images_ids[]" value="' + response.data.image_id + '"/>');
							}
						}
					}
					console.log(response);
				});
			}

		},
		show: function() {
			if (settings.editor !== null) {
				//var insertHtml = '<div>qwewe</div>';
				//
				//var insetElement = CKEDITOR.dom.element.createFromHtml(insertHtml, settings.editor.document);
				//settings.editor.insertElement(insetElement);
			}

			$modal.modal();
		}
	};

	$.fn.uploadImage = function(method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}
	}

})(jQuery);