module.exports = {

	options: {
		stderr: false
	},

	watch: {
		command: 'rm <%= config.dist %>/date || /bin/date > <%= config.dist %>/date'
	}
};