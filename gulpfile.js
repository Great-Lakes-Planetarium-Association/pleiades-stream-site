//Declare gulp requirements.
var	argv		=	require('yargs').argv;
var	autoprefix	=	require('gulp-autoprefixer');
var	cache		=	require('gulp-cache');
var	concat		=	require('gulp-concat');
var	fs			=	require('fs'); 
var	gulp		=	require('gulp');
var	gulpif		=	require('gulp-if');
var imagemin	=   require('gulp-imagemin');
var	jshint		=	require('gulp-jshint');
var	cssnano		=	require('gulp-cssnano');
var notify	  	=   require('gulp-notify');
var	rename		=	require('gulp-rename');
var replace		=	require('gulp-replace');
var	sass		=	require('gulp-sass');
var sourcemaps	=	require('gulp-sourcemaps');
var	uglify		=	require('gulp-uglify');
var yargs		=	require('yargs');

//Check if this is a development version. 
var	dev			=	(!argv.dev) ? false : true;

//Get the package.json version.
var	version		=	JSON.parse(fs.readFileSync('./package.json')).version.split('.');

//For each version value.
for(i = 0; i < version.length; i++) {
	//Parse the version as an integer type. 
	version[i]	=	parseInt(version[i]); 
}

//Get the increment value.
var	increment	=	(!dev) ? ((version[2] % 2 !== 0) ? 1 : 2) : ((version[2] % 2 !== 0) ? 2 : 1);

//Based on the existing version number.
if (version[2] + increment >= 10) {
	if (version[1] + 1 >= 10) {
		version[0]	+=	1;
		version[1]	=	0;
	} else {
		version[1]	+=	1;
	}
	
	version[2]		+=	increment - 10;
} else {
	version[2]		+=	increment;
}

//Rebuild the version. 
version	=	version.join('.'); 

//Copy CSS.
gulp.task('copy-css', function() {
	//Run Gulp.
	return gulp.src([
		'./node_modules/sobar/css/sobar.css'
	])
		.pipe(rename(function(path) {
			path.extname = '.scss'; 
		}))
		.pipe(gulp.dest('./src/scss/vendor'));
});

//Copy fonts.
gulp.task('copy-fonts', function() {
	//Run Gulp.
	return gulp.src([
	])
		.pipe(gulp.dest('./fonts'));
});

//Copy images.
gulp.task('copy-images', function() {
	//Run Gulp.
	return gulp.src([
		'./node_modules/sobar/images/*.svg'
	])
		.pipe(gulp.dest('./src/images'));
});

//Sass.
gulp.task('sass', function() {
	//Declare variables.
	var	die	= false;
	
	//Run Gulp.
	return gulp.src('./src/scss/stylesheet.scss')
		.pipe(gulpif(!die, gulpif(dev, sourcemaps.init())))
		.pipe(sass({
			sourcemap: true, 
			outputStyle: 'expanded',
			includePaths: ['./node_modules/foundation-sites/scss']
		}))
		.on('error', notify.onError(function(error) {
			//Set die as true.
			die	=	true;
			
			//Log to the console.
			console.error(error);
		}))
		.pipe(gulpif(!die, autoprefix({browsers: '> 5%'})))
		.pipe(gulpif(!die, gulpif(!dev, cssnano())))
		.pipe(gulpif(!die, gulpif(dev, sourcemaps.write())))
		.pipe(gulpif(!die, gulp.dest('./css/')))
});

//Hint.
gulp.task('hint', function() {
	//Run Gulp.
	return gulp.src('./src/js/**/*.js')
		.pipe(jshint())
		.pipe(notify(function(file) {
			//If not success.
			if (!file.jshint.success) {
				//Get the errors.
				var	errors	=	file.jshint.results.map(function(data) {
					//If there's an error.
					if (data.error) {
						//Increment the error.
						return "(" + data.error.line + ":" + data.error.character + ") " + data.error.reason;
					}
				}).join("\n");
				
				//Display the errors.
				return file.relative + "[" + file.jshint.results.length + " errors]\n" + errors;
			}
		}))
		.pipe(jshint.reporter('default')); 
});

//Hint.
gulp.task('js', ['hint'], function() {
	//Run Gulp.
	return gulp.src([
			'./node_modules/foundation-sites/dist/js/foundation.js',
			'./src/js/**/*.js'
		])
		.pipe(gulpif(!dev, uglify({'preserveComments': 'license'}).on('error', notify.onError(function(error) {
			//Log to the console.
			console.error(error);
			
			//Return the error.
			return error;
		}))))
		.pipe(concat('main.js'))
		.pipe(gulp.dest('./js/'));
});

//Images.
gulp.task('images', function() {
	return gulp.src('./src/images/**/*')
		.pipe(cache(imagemin({
			interlaced: true, 
			multipass: true, 
			optimizationLevel: 5, 
			progressive: true, 
			svgoPlugins: [{removeViewBox: false}]
		})))
		.pipe(gulp.dest('./images/'));
});

//Version control.
gulp.task('version', function() {
	//Run Gulp.
	gulp.src('./package.json')
		.pipe(replace(/(.*)(\"version\": \")(.*)(\".*)/g, '$1$2' + version + '$4'))
		.pipe(gulp.dest('./'));
});

//Watch for changes.
gulp.task('watch', function() {
	//Setup watch for Sass.
	gulp.watch(['./src/scss/**/*.scss'], ['sass']);
	
	//Setup watch for JS.
	gulp.watch(['./src/js/**/*.js'], ['js']);
	
	//Setup watch for images.
	gulp.watch(['./src/images/**/*'], ['images']);
});

//Task runner. 
gulp.task('default', ['copy-css', 'copy-fonts', 'copy-images', 'sass', 'js', 'images', 'watch', 'version']);