var gulp 		= require('gulp'),
	sourcemaps 	= require('gulp-sourcemaps'),
	source 		= require('vinyl-source-stream'),
	buffer 		= require('vinyl-buffer'),
	browserify	= require('browserify'),
	watchify 	= require('watchify'),
	babel 		= require('babelify'),
	sass 		= require('gulp-sass'),
	cssnano 	= require( 'gulp-cssnano' )
;

var postcss      = require('gulp-postcss'),
	autoprefixer = require('autoprefixer'),
	flexbugfixes = require('postcss-flexbugs-fixes')
;

function compile(watch) {
	var bundler = watchify(
		browserify('./lib/js/index.js', { debug: true })
			.transform(babel, {presets: ["es2015"]})
		);

	function rebundle() {
		bundler.bundle()
			.on('error', function(err) { console.error(err); this.emit('end'); })
			.pipe(source('build.js'))
			.pipe(buffer())
			.pipe(sourcemaps.init({ loadMaps: true }))
			.pipe(sourcemaps.write('./'))
			.pipe(gulp.dest('./assets/js'));
	}

	if (watch) {
		bundler.on('update', function() {
			console.log('-> bundling...');
			rebundle();
			console.log('->Done');
		});
	}

	rebundle();
}

function watch() {
	return compile(true);
};

gulp.task('build', function() { return compile(); });
gulp.task('watch', ['sass:watch'], function() { return watch(); });
gulp.task('default', ['watch', 'sass']);

gulp.task('sass', function () {
  return gulp
	.src('./lib/scss/app.scss')
	.pipe( sass().on('error', sass.logError) )
	.pipe( postcss([ autoprefixer(), flexbugfixes ]) )
	.pipe( cssnano( {safe:true} ) )
	.pipe( gulp.dest( './assets/css' ) );
});

gulp.task('sass:watch', function () {
  gulp.watch('./lib/scss/app.scss', ['sass']);
});
