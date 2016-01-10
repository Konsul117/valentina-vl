module.exports = {

	images: {
		expand: true,
		cwd: '<%= config.src %>/images',
		src: ['**'],
		dest: '<%= config.distTmp %>/images/',
		filter: 'isFile'
	},

	fonts: {
		expand: true,
		cwd: '<%= config.src %>/styles/fonts',
		src: ['**/*'],
		dest: '<%= config.distTmp %>/css/fonts'
	},

	css_map: {
		expand: true,
		cwd: '<%= config.tmp %>/css',
		src: ['main.css.map'],
		dest: '<%= config.distTmp %>/css'
	},

	watch: {
		expand: true,
		cwd: '<%= config.distTmp %>',
		src: ['**/*'],
		dest: '<%= config.dist %>'
	},


	bootstrap_fonts: {
		expand: true,
		cwd: 'bower_components/bootstrap-sass/assets/fonts/bootstrap',
		src: ['**/*'],
		dest: '<%= config.distTmp %>/css/fonts'
	}

};