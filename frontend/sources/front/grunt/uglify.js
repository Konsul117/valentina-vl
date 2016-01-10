module.exports = {

	all: {
		files: [{
			expand: true,
			cwd: '<%= config.src %>/scripts',
			src: '**/*.js',
			dest: '<%= config.tmp %>/js',
			ext: '.js'
		}]
	}

};