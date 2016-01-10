module.exports = {

	dist: {
		files: {
			'<%= config.distTmp %>/css/<%= config.sitePrefix %>main.css': [
//				'<%= config.tmp %>/css/{,*/}*.css'
				'<%= config.tmp %>/css/main.css'
			]
		}
	},

	bower_components: {
		files: {
			'<%= config.distTmp %>/css/<%= config.sitePrefix %>vendor.css': [
				//'bower_components/fancybox/source/jquery.fancybox.css'
			]
		}
	}

};