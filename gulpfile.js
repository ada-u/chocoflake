/**
 * gulpfile.js for php-library developement
 *
 * 1. install node.js & npm
 * 2. $ npm install
 * 3. $ gulp server
 * 4. open http://localhost:9000/ (livereload enabled)
 * 5. coding on src/*.php and tests/*.php
 *
 * enjoy!
 *
 * @license https://creativecommons.org/publicdomain/zero/1.0/ CC0-1.0 (No Rights Reserved.)
 */
var gulp = require('gulp');
var exec = require('child_process').exec;
var connect = require('gulp-connect');

gulp.task('default', ['test', 'inspect']);

gulp.task('help', function(){
    console.log('gulp test\t... kick vendor/bin/phpunit command');
    console.log('gulp inspect\t... kick vendor/bin/php-cs-fixer');
});

gulp.task('test', function(done){
    exec('vendor/bin/phpunit', function(err, stdout, stderr){
        console.log(stdout);
        console.error(stderr);
        done();
    });
});

gulp.task('inspect', function(done){
    exec('vendor/bin/php-cs-fixer fix src/ --verbose --diff', function(err, stdout, stderr){
        console.log(stdout);
        console.error(stderr);
        done();
    });
});

gulp.task('watch', function(){
    gulp.watch(['src/**', 'tests/**'], ['test', 'inspect']);
});
