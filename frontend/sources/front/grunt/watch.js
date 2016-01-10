module.exports = {

	options: {
	},

	grunt: {
		files: [
			'Gruntfile.js',
			'grunt/*.js'
		],
		options: {
			reload: true
		}
	},

	images: {
		options: {
			livereload: '<%= config.livereload %>',
			spawn: false
		},
		files: [
			'<%= config.src %>/images/**'
		],
		tasks: [
			'copy:watch',
			'shell:watch'
		]
	},

	scripts: {
		options: {
			livereload: '<%= config.livereload %>',
			spawn: false
		},
		files: [
			'<%= config.src %>/scripts/{,**/}*.js'
		],
		tasks: [
//			'jshint',
			'concat:dev',
			'copy:watch',
			'shell:watch'
		]
	},

	styles: {
		options: {
			livereload: '<%= config.livereload %>',
			spawn: false
		},
		files: [
			'<%= config.src %>/styles/{,**/}*.scss'
		],
		tasks: [
			'sass:dev',
			'concat_css',
			'copy:watch',
			'shell:watch'
		]
	},

	php: {
		options: {
			livereload: '<%= config.livereload %>',
			spawn: false
		},
		files: [
			'<%= config.php %>/{,**/}*.php'
		]
	},

	bower: {
		options: {
			livereload: '<%= config.livereload %>',
			spawn: false
		},
		files: [
			'bower.json'
		],
		tasks: [
			'copy:fonts',
			'concat:bower_components',
			'cssmin:bower_components',
			'copy:watch'
		]
	}

};