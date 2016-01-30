module.exports = {

	dev: {
		options: {
			separator: ';\n\n',
			stripBanners: true,
			banner: '/*! <%= grunt.template.today("yyyy-mm-dd H:M:s") %> */\n',
			process: function(src, filepath) {
			  return '// Source: ' + filepath + '\n' +
				src.replace(/(^|\n)[ \t]*('use strict'|"use strict");?\s*/g, '$1');
			}
		},
		src: '<%= config.src %>/scripts/{,*/}*.js',
		dest: '<%= config.distTmp %>/js/<%= config.sitePrefix %>main.js'
	},

	dist: {
		options: {
			separator: ';',
			process: function(src, filepath) {
			  return src.replace(/(^|\n)[ \t]*('use strict'|"use strict");?\s*/g, '$1');
			}
		},
		src: '<%= config.tmp %>/js/{,*/}*.js',
		dest: '<%= config.distTmp %>/js/<%= config.sitePrefix %>main.js'
	},

	bower_components: {
		options: {
			separator: ';\n',
			process: function(src, filepath) {
			  return src.replace(/(^|\n)[ \t]*('use strict'|"use strict");?\s*/g, '$1');
			}
		},
		src: [
			'bower_components/jquery/dist/jquery.min.js',
			'bower_components/fancybox/source/jquery.fancybox.pack.js'
		],
		dest: '<%= config.distTmp %>/js/<%= config.sitePrefix %>vendor.js'
	}


};
