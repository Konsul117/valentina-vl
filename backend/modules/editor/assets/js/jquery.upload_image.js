(function($) {

	var settings = {};

	var $modal;

	var methods = {
		init: function(options) {
			settings = $.extend({
				editor:       null,
				showButtonId: null,
				uploadUrl:    null,
				params:       {}
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
					var response = $.parseJSON(file.xhr.response);
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