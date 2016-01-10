module.exports = {

	app: {
		ignorePath: /^\/|\.\.\//,
		src: ['<%= config.app %>/index.html'],
		exclude: ['bower_components/bootstrap-sass/assets/javascripts/bootstrap.js']
	},
	sass: {
		src: ['<%= config.app %>/styles/{,*/}*.{scss,sass}'],
		ignorePath: /(\.\.\/){1,2}bower_components\//
	}

};