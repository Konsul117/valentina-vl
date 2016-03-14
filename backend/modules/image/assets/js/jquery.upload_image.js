(function($) {

	/** настройки виджета */
	var settings = {};

	/** модалка загрузки изображений */
	var $uploadModal;

	/** панель загруженных изображений */
	var $previewPane;

	/** параметры модалки добавления изображений */
	var addImageModalOptions = {
		editorId:      false,
		positionId:    false,
		addSpaceAfter: false
	};

	var IMAGE_POSITION_TEXT = 'text';
	var IMAGE_POSITION_LEFT = 'left';
	var IMAGE_POSITION_RIGHT = 'right';

	//позиции размещения изображения в редакторе
	var addImagePositions = {
		text:  'В тексте',
		left:  'Слева',
		right: 'Справа'
	};

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
		};

		this.resetUnload = function() {
			$(window).off('beforeunload', o.unload);

			setTimeout(function() {
				$(window).on('beforeunload', o.unload);
			}, 1000);
		};

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
		};
		this.init();
	}

	var methods = {
		init:                 function(options) {

			settings = $.extend({
				editorsIds:       {},
				showButtonId:  null,
				previewPaneId: null,
				uploadUrl:     null,
				params:        {}
			}, options);

			$uploadModal = $(this);

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
				new Dropzone('#'+$uploadModal.attr('id')+' .dropzone', {
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
				$previewPane.on('click', '.image-item img', function() {
					methods.imageItemAddToEditor($(this));
				});
			}

			//инициализация блокировщика закрытия окна
			if (typeof window.obUnloader != 'object') {
				window.obUnloader = new Unloader();
			}

		},
		findStatusBlock:      function(filename) {
			return $('ul.uploading-images > li', $uploadModal).filter(function() {
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
				$previewPane.append('<div class="image-item" data-role="image-item-params">'
					+'<img src="'+response.data.urls.thumb+'" data-medium-url="'+response.data.urls.medium+'" data-image-id="'+response.data.image_id+'"/>'
					+'<input type="hidden" name="needWatermark['+response.data.image_id+']" value="0">'
					+'<input type="hidden" name="image-title['+response.data.image_id+']" value="">'
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
			$('.uploading-images', $uploadModal).append('<li data-filename="'+file.name+'">'
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
			$('.uploading-images', $uploadModal).empty();
			$uploadModal.modal();
		},
		imageItemAddToEditor: function($image) {

			//селектбокс для селектора редактора
			var editorsRadiobuttons = '<ul class="list-unstyled">';

			$.each(settings.editorsIds, function(key, editorId) {
				if (addImageModalOptions.editorId === false) {
					addImageModalOptions.editorId = editorId;
				}

				editorsRadiobuttons += '<li class="radio">'
						+ '<label><input name="editorId" type="radio" value="' + editorId + '"' + (addImageModalOptions.editorId === editorId ? ' checked' : '') + '>'
						+ $('#' + editorId).siblings('label').text() + '</label>'
						+ '</li>';
			});
			editorsRadiobuttons += '</ul>';

			//селектбокс для селектора позиции
			var positionRadiobuttons = '<ul class="list-unstyled">';

			$.each(addImagePositions, function(positionId, title) {
				if (addImageModalOptions.positionId === false) {
					addImageModalOptions.positionId = positionId;
				}

				positionRadiobuttons += '<li class="radio">'
					+ '<label><input name="positionId" type="radio" value="' + positionId + '"' + (addImageModalOptions.positionId === positionId ? ' checked' : '') + '>'
					+ title + '</label>'
					+ '</li>';
			});
			positionRadiobuttons += '</ul>';

			var modalHtml =
				'<div class="modal fade add-image-modal" id="addImageModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
					+ '<div class="modal-dialog">'
						+ '<div class="modal-content">'
							+ '<div class="modal-header">'
								+ '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
								+ '<h4 class="modal-title" id="myModalLabel">Добавить изображение</h4>'
							+ '</div>'
							+ '<div class="modal-body">'

								+ '<div class="form-group">'
									+ '<label>Блок</label>'
									+ editorsRadiobuttons
								+ '</div>'

								+ '<div class="form-group">'
									+ '<label>Размещение</label>'
									+ positionRadiobuttons
								+ '</div>'

								+ '<div class="form-group">'
									+ '<label>'
										+ '<input type="checkbox" name="addSpaceAfter" value="1"' + (addImageModalOptions.addSpaceAfter ? ' checked="checked"' : '') + '"/>'
										+ ' Добавлять пробел'
									+ '</label>'
								+ '</div>'

								+ '<div class="form-group">'
									+ '<label>Название</label>'
									+ '<input type="text" id="title" class="form-control">'
								+ '</div>'

							+ '</div>'
							+ ' <div class="modal-footer">'
								+ '<button type="button" class="btn btn-success" data-dismiss="modal">Добавить</button>'
								+ '<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>'
							+ ' </div>'
						+ '</div>'
					+ '</div>'
				+ '</div>';

			var $addModal = $(modalHtml);

			$addModal.find('#title').val(methods.getImageTitle($image.data('image-id')));

			$addModal.modal();

			$addModal.on('hidden.bs.modal', function() {
				$(this).remove();
			});

			$addModal.find('input[type=text]').keyup(function(event) {
				if(event.keyCode==13) {
					$addModal.find('button.btn-success').click();
				}
			});

			$addModal.find('button.btn-success').click(function() {
				var editorId = $addModal.find('input[name=editorId]:checked').val();
				var positionId = $addModal.find('input[name=positionId]:checked').val();
				var title = $addModal.find('#title').val();

				addImageModalOptions.editorId = editorId;
				addImageModalOptions.positionId = positionId;
				addImageModalOptions.addSpaceAfter = $addModal.find('input[name=addSpaceAfter]').prop('checked');
				methods.addImage($image, title);
			});


		},

		/**
		 * Получить название изображения
		 * @param {integer} imageId Id изображения
		 * @returns {string}
		 */
		getImageTitle: function(imageId) {
			return $('[data-role=image-item-params] input').filter(function(){
				return $(this).prop('name') === 'image-title[' +imageId + ']';
			}).val();
		},

		/**
		 * Установить название изображения
		 * @param {integer} imageId Id изображения
		 * @param {string} title Название
		 */
		setImageTitle: function(imageId, title) {
			$('[data-role=image-item-params] input').filter(function(){
				return $(this).prop('name') === 'image-title[' +imageId + ']';
			}).val(title);
		},

		addImage: function($image, title) {
			var editor = tinyMCE.get(addImageModalOptions.editorId);

			if (editor === null) {
				console.log('Ошибка при добавлении изображения: неизвестный редактор tinyMCE');
			}

			var $content = $('<div class="wrap">' + editor.getContent() + '</div>');

			if (typeof (addImagePositions[addImageModalOptions.positionId]) === 'undefined') {
				console.log('Ошибка при добавлении изображения: неизвестная позиция');
				return ;
			}

			methods.setImageTitle($image.data('image-id'), title);

			if (addImageModalOptions.positionId === IMAGE_POSITION_TEXT) {
				//добавление изображения в тексте
				editor.insertContent(methods.getAddImageHtml($image, title, false));

				return ;
			}
			else {
				//не в тексте
				var posClass = 'format-' + addImageModalOptions.positionId;

				var $imagesBlock =  $content.find('div.image-block.' + posClass);

				if ($imagesBlock.length === 0) {

					if (addImageModalOptions.positionId === IMAGE_POSITION_RIGHT) {
						//справа
						var $leftBlock = $content.find('div.image-block.format-left');
						if ($leftBlock.length > 0) {
							$leftBlock.after('<div class="image-block ' + posClass + '"></div>');
						}
						else {
							$content.prepend('<div class="image-block ' + posClass + '"></div>');
						}
					}
					else if (addImageModalOptions.positionId === IMAGE_POSITION_LEFT) {
						//слева
						$content.prepend('<div class="image-block ' + posClass + '"></div>');
					}

					$imagesBlock = $content.find('.image-block.' + posClass);
				}
				$imagesBlock.append(methods.getAddImageHtml($image, title, true));
			}

			if ($content.find('p').length === 0) {
				//если в контенте нет параграфа, то добавляем его в конец, чтобы можно было писать текст
				$content.append('<p></p>');
			}

			editor.setContent($content.html());
		},
		//получение html-кода для вставки изображения
		getAddImageHtml: function($image, title, wrapLabel) {

			if (typeof(wrapLabel) === 'undefined') {
				wrapLabel = false;
			}

			var returnHtml = '';

			if (wrapLabel === true) {
				returnHtml += '<div class="img-wrap">'
					+ '<img src="'+$image.data('medium-url')+'" data-image-id="'+$image.data('image-id')+'" />'
					+ ((title !== '') ? ('<div class="img-title">' + title + '</div>') : '')
					+ '</div>';
			}
			else {
				returnHtml += '<img src="'+$image.data('medium-url')+'" data-image-id="'+$image.data('image-id')+'" />';
			}

			if (addImageModalOptions.addSpaceAfter) {
				returnHtml += '&nbsp;';
			}

			return returnHtml;
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