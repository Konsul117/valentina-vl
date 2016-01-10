module.exports = {

	options: {
		force: true
	},

	dev: {
		files: [{
			dot: true,
			src: [
				'<%= config.tmp %>/*',
				'<%= config.distTmp %>/*'
			]
		}]
	},

//	watch: {
//		files: [{
//			dot: true,
//			src: [
//				'<%= config.dist %>/images/*'
//			]
//		}]
//	},

	dist: {
		files: [{
			dot: true,
			src: [
				'<%= config.tmp %>/*',
				'<%= config.distTmp %>/*'
			]
		}]
	},

	renameDistTmp: {
		files: [{
			dot: true,
			src: [
				'<%= config.dist %>'
			]
		}]
	},

	assets: {
		files: [{
			dot: true,
			src: [
				'<%= config.assets %>/*',
				'!<%= config.assets %>/.git*'
			]
		}]
	}

};