'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'library/assets/js/*.js',
        'library/assets/js/accessories/*.js',
        'library/assets/js/plugins/*.js',
        'library/assets/js/flatskin/*.js',
        'library/assets/js/fourseven/*.js',
        'library/assets/js/responsive/*.js',
        '!library/assets/js/history/ajaxify-html5.js',
        '!library/assets/js/history/history.js',
        '!library/assets/js/scripts.min.js'
      ]
    },
    recess: {
      dist: {
        options: {
          compile: true,
          compress: true
        },
        files: {
          'library/assets/css/main.min.css': [
          'library/assets/less/app.less'
          ]
        }
      }
    },
    uglify: {
      dist: {
        files: {
          'library/assets/js/scripts.min.js': [
            'library/assets/js/plugins/bootstrap/transition.js',
            'library/assets/js/plugins/bootstrap/alert.js',
            'library/assets/js/plugins/bootstrap/button.js',
            'library/assets/js/plugins/bootstrap/carousel.js',
            'library/assets/js/plugins/bootstrap/collapse.js',
            'library/assets/js/plugins/bootstrap/dropdown.js',
            'library/assets/js/plugins/bootstrap/modal.js',
            'library/assets/js/plugins/bootstrap/tooltip.js',
            'library/assets/js/plugins/bootstrap/popover.js',
            'library/assets/js/plugins/bootstrap/scrollspy.js',
            'library/assets/js/plugins/bootstrap/tab.js',
            'library/assets/js/plugins/bootstrap/affix.js',
            'library/assets/js/plugins/bootstrap/select.js',
            'library/assets/js/plugins/bootstrap/switch.js',
            'library/assets/js/plugins/bootstrap/typeahead.js',
            'library/assets/js/plugins/*.js',
            'library/assets/js/_*.js'
          ]
        }
      }
    },
    version: {
      options: {
        file: 'library/functions/scripts.php',
        css: 'library/assets/css/main.min.css',
        cssHandle: 'fourseven_main',
        js: 'library/assets/js/scripts.min.js',
        jsHandle: 'fourseven_scripts'
      }
    },
    watch: {
      less: {
        files: [
          'library/assets/less/*.less',
          'library/assets/less/bootstrap/*.less',
          'library/assets/less/flatskin/*.less',
          'library/assets/less/flatskin/icon/*.less',
          'library/assets/less/flatskin/modules/*.less'
        ],
        tasks: ['recess', 'version']
      },
      js: {
        files: [
          '<%= jshint.all --force %>'
        ],
        tasks: ['jshint', 'uglify', 'version']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          'library/assets/css/main.min.css',
          'library/assets/js/scripts.min.js',
          'templates/*.php',
          '*.php'
        ]
      }
    },
    clean: {
      dist: [
        'library/assets/css/main.min.css',
        'library/assets/js/scripts.min.js'
      ]
    }
  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-recess');
  grunt.loadNpmTasks('grunt-wp-version');

  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'recess',
    'uglify',
    'version'
  ]);
  grunt.registerTask('dev', [
    'watch'
  ]);

};
