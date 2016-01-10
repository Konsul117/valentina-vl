module.exports = {

	options: {
		reporter: require('jshint-stylish'),
		curly: true,
		eqeqeq: true,
		eqnull: true,
		browser: true,
		globals: {
			jQuery: true
		}
	},

	main: [
		'<%= config.src %>/scripts/{,*/}*.js'
	]

};