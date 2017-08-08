var gulp 		= require('gulp'),
	sourcemaps 	= require('gulp-sourcemaps'),
	source 		= require('vinyl-source-stream'),
	buffer 		= require('vinyl-buffer'),
	uglify 		= require( 'gulp-uglify' ),
	browserify	= require('browserify'),
	watchify 	= require('watchify'),
	babel 		= require('babelify'),
	sass 		= require('gulp-sass'),
	cssnano 	= require('gulp-cssnano'),
	cssbyebye 	= require('css-byebye')
;

var postcss      = require('gulp-postcss'),
	autoprefixer = require('autoprefixer'),
	flexbugfixes = require('postcss-flexbugs-fixes')
;

function compile(watch) {
	var bundler = watchify(
		browserify('./lib/js/index.js', { debug: true })
			.transform(babel, {presets: ["es2015"]})
			.transform('browserify-shim', {global: true})
		);

	function rebundle() {
		bundler.bundle()
			.on('error', function(err) { console.error(err); this.emit('end'); })
			.pipe(source('build.js'))
			.pipe(buffer())
			.pipe(uglify())
			.pipe(sourcemaps.init({ loadMaps: true }))
			.pipe(sourcemaps.write('./'))
			.pipe(gulp.dest('./public/js'));
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

	var cbbOptions = {
		rulesToRemove: [ '.slick-loading .slick-list' ]
	}

  return gulp
	.src('./lib/scss/app.scss')
	.pipe( sass().on('error', sass.logError) )
	.pipe( postcss([ autoprefixer(), flexbugfixes, cssbyebye( cbbOptions ) ] ) )
	.pipe( cssnano( {safe:true} ) )
	.pipe( gulp.dest( './public/css' ) );
});

gulp.task('sass:watch', function () {
  gulp.watch('./lib/scss/*.scss', ['sass']);
});
