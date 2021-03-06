// Require our dependencies
var autoprefixer = require('autoprefixer');
var cheerio = require('gulp-cheerio');
var concat = require('gulp-concat');
var cssnano = require('gulp-cssnano');
var del = require('del');
var gulp = require('gulp');
var gutil = require('gulp-util');
var mqpacker = require('css-mqpacker');
var notify = require('gulp-notify');
var plumber = require('gulp-plumber');
var postcss = require('gulp-postcss');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sassLint = require('gulp-sass-lint');
var sort = require('gulp-sort');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');

// Set assets paths.
var paths = {
	css: ['./styles/*.css', '!./styles/*.min.css'],
	php: ['./*.php', './**/*.php'],
	sass: './styles/**/*.scss',
	concat_scripts: 'js/concat/*.js',
	scripts: ['js/*.js', '!js/*.min.js']
};

/**
 * Handle errors and alert the user.
 */
function handleErrors () {
	var args = Array.prototype.slice.call(arguments);

	notify.onError({
		title: 'Task Failed [<%= error.message %>',
		message: 'See console.',
		sound: 'Sosumi' // See: https://github.com/mikaelbr/node-notifier#all-notification-options-with-their-defaults
	}).apply(this, args);

	gutil.beep(); // Beep 'sosumi' again

	// Prevent the 'watch' task from stopping
	this.emit('end');
}

/**
 * Delete style.css and style.min.css before we minify and optimize
 */
gulp.task('clean:styles', function() {
	return del(['./styles/style.css', './styles/style.min.css']);
});

/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/css-mqpacker
 */
gulp.task('postcss', ['clean:styles'], function() {
	return gulp.src('./styles/*.scss', paths.css)

	// Deal with errors.
	.pipe(plumber({ errorHandler: handleErrors }))

	// Wrap tasks in a sourcemap.
	.pipe(sourcemaps.init())

		// Compile Sass using LibSass.
		.pipe(sass({
			includePaths: [],
			errLogToConsole: true,
			outputStyle: 'expanded' // Options: nested, expanded, compact, compressed
		}))

		// Parse with PostCSS plugins.
		.pipe(postcss([
			autoprefixer({
				browsers: ['last 2 versions']
			}),
			mqpacker({
				sort: true
			}),
		]))

	// Create sourcemap.
	.pipe(sourcemaps.write())

	// Create style.css.
	.pipe(gulp.dest('./styles/'));
});

/**
 * Minify and optimize style.css.
 *
 * https://www.npmjs.com/package/gulp-cssnano
 */
gulp.task('cssnano', ['postcss'], function() {
	return gulp.src('./styles/style.css')
	.pipe(plumber({ errorHandler: handleErrors }))
	.pipe(cssnano({
		safe: true // Use safe optimizations
	}))
	.pipe(rename('style.min.css'))
	.pipe(gulp.dest('./styles/'));
});

/**
 * Sass linting
 *
 * https://www.npmjs.com/package/sass-lint
 */
gulp.task('sass:lint', ['cssnano'], function() {
	gulp.src([
		'./styles/**/*.scss'
	])
	.pipe(sassLint())
	.pipe(sassLint.format())
	.pipe(sassLint.failOnError());
});



/**
 * Delete scripts before we concat and minify
 */
gulp.task('clean:scripts', function() {
	return del(['js/script.js', 'js/script.min.js']);
});

/**
 * Concatenate javascripts after they're clobbered.
 * https://www.npmjs.com/package/gulp-concat
 */
gulp.task('concat', ['clean:scripts'], function() {
	return gulp.src(paths.concat_scripts)
	.pipe(plumber({ errorHandler: handleErrors }))
	.pipe(sourcemaps.init())
	.pipe(concat('script.js'))
	.pipe(sourcemaps.write())
	.pipe(gulp.dest('js'));
});

 /**
  * Minify javascripts after they're concatenated.
  * https://www.npmjs.com/package/gulp-uglify
  */
gulp.task('uglify', ['concat'], function() {
	return gulp.src(paths.scripts)
	.pipe(rename({suffix: '.min'}))
	.pipe(uglify({
		mangle: false
	}))
	.pipe(gulp.dest('js'));
});



/**
 * Process tasks on file changes.
 *
 */
gulp.task('watch', function() {

	// Run tasks when files change.
	gulp.watch(paths.sass, ['styles']);
	gulp.watch(paths.scripts, ['scripts']);
	gulp.watch(paths.concat_scripts, ['scripts']);
});

/**
 * Create indivdual tasks.
 */
gulp.task('scripts', ['uglify']);
gulp.task('styles', ['cssnano']);
gulp.task('default', ['styles', 'scripts']);
