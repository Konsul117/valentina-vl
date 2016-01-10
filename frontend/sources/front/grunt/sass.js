module.exports = {

	// Development settings
	dev: {
		options: {
			loadPath: ['bower_components'],
			compass: true,
			trace: true,
//			lineNumbers: true,
			style: 'expanded'
		},
		files: [{
			expand: true,
			cwd: '<%= config.src %>/styles',
			dest: '<%= config.tmp %>/css',
			src: ['*.{scss,sass}'],
			ext: '.css'
		}]
	},

	// Production settings
	dist: {
		options: {
			loadPath: ['bower_components'],
			compass: true,
			sourcemap: 'none',
			style: 'compressed'
		},
		files: [{
			expand: true,
			cwd: '<%= config.src %>/styles',
			src: ['*.{scss,sass}'],
			dest: '<%= config.tmp %>/css',
			ext: '.css'
		}]
	}

};