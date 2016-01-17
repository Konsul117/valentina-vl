(function($) {

	var settings = {};

	var $modal;

	var $previewPane;

	var methods = {
		init:               function(options) {
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
					params:            settings.params,
					uploadprogress:    methods.uploadProgress,
					sending:           methods.uploadSending,
					complete:          methods.uploadComplete
				});
			}

			if (settings.previewPaneId) {
				$previewPane = $('#'+settings.previewPaneId);
			}

			if ($previewPane.length > 0) {
				$previewPane.on('click', '.image-item', methods.imageItemAddToEditor);
			}

		},
		findStatusBlock:    function(filename) {
			return $('ul.uploading-images > li', $modal).filter(function() {
				return $(this).data('filename') === filename;
			});
		},
		uploadComplete:     function(file) {
			if (file.xhr.status !== 200) {
				methods.updateUploadStatus(file.name, false);
				alert('Произошла ошибка соединения с сервером');
			}

			var response = $.parseJSON(file.xhr.response);

			if (response.success === false) {
				methods.updateUploadStatus(file.name, false);
				alert(response.error ? response.error : 'Неизвестная ошибка');
				return;
			}

			if ($previewPane.length > 0) {
				$previewPane.append('<div class="image-item">'
					+'<img src="'+response.data.urls.thumb+'" data-medium-url="'+response.data.urls.medium+'" data-image-id="'+response.data.image_id+'"/>'
					+'</div>');

				var $form = $previewPane.closest('form');

				if ($form.length > 0) {
					$form.append('<input type="hidden" name="uploaded_images_ids[]" value="'+response.data.image_id+'"/>');
				}
			}

			methods.updateUploadStatus(file.name, true);
		},
		uploadSending:      function(file) {
			$('.uploading-images', $modal).append('<li data-filename="'+file.name+'">'
				+'<div class="lbl-progress">'
				+'<span class="filename">Загрузка файла '+file.name+'</span>'
				+'<span class="status glyphicon glyphicon-refresh"></span>'
				+'</div>'
				+'</li>'
			)
		},
		uploadProgress:     function(file, progress) {
			var $li = methods.findStatusBlock(file.name);

			if ($li.length > 0) {
				var $progressBar = $('.progress-bar', $li);
				if ($progressBar.length === 0) {
					$progressBar = $li.append('<div class="progress">'
						+'<div class="progress-bar" role="progressbar">'
						+'</div>'
						+'</div>').find('.progress-bar');
				}

				var width = $li.width();

				$progressBar.width(width*progress/100);
			}
		},
		updateUploadStatus: function(filename, success) {
			var $status = methods.findStatusBlock(filename)
				.find('.status')
				.removeClass('glyphicon-refresh');

			if (success) {
				$status.addClass('glyphicon glyphicon-ok');
			}
			else {
				$status.addClass('glyphicon glyphicon-remove');
			}
		},
		show:               function() {
			$modal.modal();
		},
		imageItemAddToEditor: function() {
			if (settings.editor !== null) {

				var $img = $('img', $(this));

				if ($img.length > 0) {
					var insertHtml = '<img src="'+$img.data('medium-url')+'" data-image-id="'+$img.data('image-id')+'"/>';

					var insetElement = CKEDITOR.dom.element.createFromHtml(insertHtml, settings.editor.document);
					settings.editor.insertElement(insetElement);
				}
			}
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