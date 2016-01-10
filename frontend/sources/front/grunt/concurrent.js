module.exports = {

	options: {
		limit: 10
	},

	//dev
	devStep1: [
		'clean:dev' //очищаем папку
	],
	devStep2: [
		'sass:dev', //компилим .scss в .css
		'copy:images', //копируем изображения
		'copy:fonts', //копируем изображения
		'concurrent:bower_components'
	],
	devStep3: [
		'concat:dev', //сливаем все js в один файл
		'concat_css', //сливаем все css в один файл
		'copy:css_map' //переносим map файл
	],

	// dist
	distStep1: [
		'clean:dist' //очищаем папку
	],
	distStep2: [
		'sass:dist', //компилим .scss в .css
		'uglify', //сжимаем .js
		'imagemin', //копируем изображения со сжатием
		'copy:fonts', //копируем изображения
		'concurrent:bower_components'
	],
	distStep3: [
		'concat:dist', //сливаем все js в один файл
		'cssmin:dist' //сжимаем и сливаем .css в один файл
	],

	assets: [
		'clean:assets' //очищаем папку assets yii2
	],

	renameDistTmp: [
		'rename' //очищаем папку assets yii2
	],

	bower_components: [
		'copy:bootstrap_fonts', //шрифты bootstrap
		'concat:bower_components', //объединяем js различных библиотек
		'cssmin:bower_components' //объединяем css различных библиотек
	]

};