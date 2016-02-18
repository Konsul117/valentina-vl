(function($) {

	var settings = {};

	var $modal;

	var $previewPane;

	/**
	 * Блокировщик закрытия окна
	 */
	function Unloader() {
		var o = this;
		this.unload = function(evt) {
			var message = "Вы действительно хотите уйти со страницы?";
			if (typeof evt == "undefined") {
				evt = window.event;
			}
			if (evt) {
				evt.returnValue = message;
			}
			return message;
		}

		this.resetUnload = function() {
			$(window).off('beforeunload', o.unload);

			setTimeout(function() {
				$(window).on('beforeunload', o.unload);
			}, 1000);
		}

		this.init = function() {
			$(window).on('beforeunload', o.unload);

			$('a').on('click', o.resetUnload);
			$(document).on('submit', 'form', o.resetUnload);
			// F5 и Ctrl+F5, Enter
			$(document).on('keydown', function(event) {
				if ((event.ctrlKey && event.keyCode == 116) || event.keyCode == 116 || event.keyCode == 13) {
					o.resetUnload();
				}
			});
		}
		this.init();
	}

	var methods = {
		init:                 function(options) {
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
				new Dropzone('#'+$modal.attr('id')+' .dropzone', {
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
				$previewPane.on('click', '.image-item img', methods.imageItemAddToEditor);
			}

			//инициализация блокировщика закрытия окна
			if (typeof window.obUnloader != 'object') {
				window.obUnloader = new Unloader();
			}

		},
		findStatusBlock:      function(filename) {
			return $('ul.uploading-images > li', $modal).filter(function() {
				return $(this).data('filename') === filename;
			});
		},
		uploadComplete:       function(file) {
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
					+'<input type="hidden" name="needWatermark['+response.data.image_id+']" value="0">'
					+'<label><input type="checkbox" name="needWatermark['+response.data.image_id+']" value="1" checked=""> Watermark</label>'
					+'</div>');

				var $form = $previewPane.closest('form');

				if ($form.length > 0) {
					$form.append('<input type="hidden" name="uploaded_images_ids[]" value="'+response.data.image_id+'"/>');
				}
			}

			methods.updateUploadStatus(file.name, true);
		},
		uploadSending:        function(file) {
			$('.uploading-images', $modal).append('<li data-filename="'+file.name+'">'
				+'<div class="lbl-progress">'
				+'<span class="filename">Загрузка файла '+file.name+'</span>'
				+'<span class="status glyphicon glyphicon-refresh"></span>'
				+'</div>'
				+'</li>'
			)
		},
		uploadProgress:       function(file, progress) {
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
		updateUploadStatus:   function(filename, success) {
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
		show:                 function() {
			$('.uploading-images', $modal).empty();
			$modal.modal();
		},
		imageItemAddToEditor: function() {
			if (settings.editor !== null) {

				var $img = $(this);

				if ($img.length > 0) {
					var insertHtml = '<img style="float:left" src="'+$img.data('medium-url')+'" data-image-id="'+$img.data('image-id')+'"/>';
					settings.editor.insertContent(insertHtml);
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