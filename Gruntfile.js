module.exports = function (grunt) {
    'use strict';

    require('time-grunt')(grunt);

    // Project configuration
    grunt.initConfig({

        // Metadata
        pkg: grunt.file.readJSON('package.json'),

        // Task configuration
        files: {
            js: {
                app: [
                    'public/js/lib/application.js',
                    'public/js/lib/analytics.js',
                    'public/js/lib/facebook.js',
                    //'public/js/mock/mock.js',
                    'public/js/*.js'
                ],
                lib: [
                    'public/js/lib/angular/angular.js',
                    'public/js/lib/angular/angular-route.js',
                    //'public/js/lib/angular/angular-touch.js',
                    //'public/js/lib/angular/angular-resource.js',
                    //'public/js/lib/angular/angular-mocks.js'
                    'public/js/lib/angular/angular-animate.js'
                ],
                dist: 'public/js/bin'
            },
            css: {
                app: 'public/css/styles.css',
                lib: [
                    'public/css/reset.css'
                ],
                dist: 'public/css/bin'
            },
            fonts: {
                src: 'bower_components/bootstrap-css/fonts/',
                dist: 'public/css/fonts'
            }
        },

        // Tasks
        concat: {
            app: {
              src: '<%= files.js.app %>',
              dest: '<%= files.js.dist %>/app.min.js',
              options: {
                sourceMap: true
              }
            },
            lib: {
                src: '<%= files.js.lib %>',
                dest: '<%= files.js.dist %>/lib.min.js',
                options: {
                    stripBanners: false
                }
            }
        },
        ngAnnotate: {
            lib: {
                src: '<%= concat.lib.dest %>',
                dest: '<%= files.js.dist %>/lib.min.js'
            }
        },
        uglify: {
            options: {
                stripBanners: false,
                sourceMap: true,
                mangle: false,
                report: 'min'
            },
            lib: {
                options: {
                    stripBanners: true,
                    sourceMap: false,
                    mangle: true,
                    report: 'min'
                },
                src: '<%= concat.lib.dest %>',
                dest: '<%= files.js.dist %>/lib.min.js'
            }
        },
        copy: {
            fonts: {
                expand: true,
                cwd: '<%= files.fonts.src %>',
                src: '*.*',
                dest: '<%= files.fonts.dist %>'
            },
            assets: {
                src: 'application/Views/layout.php',
                dest: 'application/Views/layout.min.php'
            }
        },
        cssmin: {
            options: {
                keepSpecialComments: 0,
                root: ''
            },
            app: {
                files: {
                    '<%= files.css.dist %>/app.min.css': '<%= files.css.app %>'
                }
            },
            lib: {
                files: {
                    '<%= files.css.dist %>/lib.min.css': '<%= files.css.lib %>'
                }
            }
        },
        clean: {
            js: ['<%= files.js.dist %>/*.js', '<%= files.js.dist %>/*.map'],
            css: '<%= files.css.dist %>/*.css',
            minimizedPhp: ['application/Views/layout.min.php']
        },
        jshint: {
            options: {
                node: true,
                curly: true,
                eqeqeq: true,
                immed: true,
                latedef: true,
                newcap: true,
                noarg: true,
                sub: true,
                undef: true,
                unused: true,
                eqnull: true,
                browser: true,
                '-W116': true,
                globals: {
                    jQuery: true,
                    '_': true,
                    'angular': true
                },
                boss: true
            },
            gruntfile: {
                src: 'Gruntfile.js'
            },
            app: {
                src: '<%= files.js.app %>'
            }
        },
        jslint: {
            app: {
                src: '<%= files.js.app %>',
                directives: {
                    browser: true,
                    predef: ['jQuery', '_', 'angular'],
                    nomen: true,
                    unparam: true
                },
                options: {
                }
            }
        },
        watch: {
            gruntfile: {
                files: '<%= jshint.gruntfile.src %>',
                tasks: ['jshint:gruntfile', 'build']
            },
            js: {
                files: '<%= files.js.app %>',
                tasks: ['buildJs']
            },
            css: {
                files: '<%= files.css.app %>',
                tasks: ['buildCss']
            }
        },
        useminPrepare: {
            html: 'application/Views/layout.php',
            options: {
                root: 'public',
                dest: 'public',
                staging: 'application/tmp'
            }
        },
        usemin: {
            html: 'application/Views/layout.min.php',
            options: {
                assetsDirs: ['public']
            }
        }
    });

    // These plugins provide necessary tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    // Tasks
    grunt.registerTask('hint', ['jshint']);
    grunt.registerTask('lint', ['jshint', 'jslint']);
    grunt.registerTask('build', [
        'copy:assets',
        'useminPrepare',
        'concat:generated',
        'cssmin:generated',
        'uglify:generated',
        'usemin'
    ]);

    // Default task
    grunt.registerTask('default', 'watch');
};