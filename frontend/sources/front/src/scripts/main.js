$(document).ready(function() {
	$('a[data-lightbox]').fancybox({
		beforeLoad: function() {
			$img = $(this.element).find('img');

			if ($img.length > 0) {
				this.title = $img.attr('title');
			}

		},
		afterLoad : function() {
			this.title = (this.title ? '' + this.title : '');
		}
	});
});