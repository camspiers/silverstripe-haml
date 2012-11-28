module.exports = function( grunt ) {

  var exec = require('child_process').exec,
      argv = require('optimist').argv,
      sys = require('sys');
  
  grunt.loadNpmTasks( 'grunt-contrib-watch' );

  grunt.initConfig( {
    watch: {
      haml: {
        files: [ '../themes/*/haml/**/*.ss.haml' ],
        tasks: [ 'haml' ],
        options: {
          debounceDelay: 1000
        }
      }
    },    
  } );
  
  grunt.registerTask( 'haml', function (deets) {

    var done = this.async(),
      args = [ 'haml/process' ];

    if ( argv.theme ) {
      args.push( '--theme ' + argv.theme );
    }

    exec(
      '../sapphire/sake ' + args.join( ' ' ),
      function ( error, stdout, stderr ) {
        if ( stdout ) {
          sys.puts( stdout );
        }
        if ( stderr ) {
          sys.puts( stderr );
        }
        done();
      }
    );

  } );

  grunt.registerTask( 'default', [ 'watch' ] );

};