function tinymce_init(textarea_id, inner_css) {
	tinymce.init({
		selector: '#' + textarea_id,
		setup : function(editor) {
			editor.on('keyup', function() {
				//детектим не были ли удалены все теги <p></p>

				var $content = $('<div class="wrap">' + editor.getContent() + '</div>');

				if ($('p', $content).length === 0) {
					$content.append('<p></p>');

					editor.setContent($content.html());
				}
			});
		},
		height: 200,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table contextmenu paste code',
			'autoresize'
		],
		autoresize_max_height: 500,
		toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		content_css: [
			'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
			'//www.tinymce.com/css/codepen.min.css',
			inner_css
		],
		body_class: 'mceContentBody'
	});
}