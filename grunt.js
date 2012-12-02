module.exports = function( grunt ) {

  var exec  = require('child_process').exec,
      argv  = require('optimist').argv,
      pattern = argv.pattern ? argv.pattern : '../themes/*/haml/**/*.ss.haml';
  
  grunt.loadNpmTasks( 'grunt-contrib-watch' );

  grunt.initConfig( {
    watch: {
      haml: {
        files: [ pattern ],
        tasks: [ 'haml' ],
        options: {
          debounceDelay: 2000
        }
      }
    },    
  } );
  
  grunt.registerTask( 'haml', function () {

    var done = this.async();

    exec(
      '../sapphire/sake haml/process',
      function ( error, stdout, stderr ) {
        var hasErr = false;
        if ( stdout ) {
          grunt.log.write( stdout );
        }
        if ( stderr ) {
          grunt.log.error();
          grunt.log.error( stderr );
          hasErr = true;
        }
        done( !hasErr );
      }
    );

  } );

  grunt.registerTask( 'default', [ 'watch' ] );

};