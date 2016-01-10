module.exports = {

	all: {
		files: [{
			expand: true,
			cwd: '<%= config.src %>/images/',
			src: ['**/*.{png,jpg,jpeg,gif}'],
			dest: '<%= config.distTmp %>/images/'
		}]
	}

};