module.exports = {

	options: {
		force: true
	},

	all: {
		files: [
			{src: ['<%= config.distTmp %>'], dest: '<%= config.dist %>'}
		]
	}
};