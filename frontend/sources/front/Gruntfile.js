module.exports = function(grunt) {

	var path = require('path');
	var config = {
		livereload: 35727,

		src: 'src',
		distTmp: 'tmp/dist_tmp',
		dist: 'tmp/dist',
		tmp: 'tmp/tmp',
		php: '../../../themes/adaptive',
		assets: '../../../web/assets',

		sitePrefix: 'valentina-vl-'
	};

	require('time-grunt')(grunt);

	require('load-grunt-config')(grunt, {
		configPath: path.join(process.cwd(), 'grunt'),
		jitGrunt: true,
		data: {
			config: config
		}
	});

};