module.exports = function( grunt ) {

  var exec  = require('child_process').exec,
      argv  = require('optimist').argv,
      theme = argv.theme ? argv.theme : false;
  
  grunt.loadNpmTasks( 'grunt-contrib-watch' );

  grunt.initConfig( {
    watch: {
      haml: {
        files: [ '../themes/' + ( theme ? theme : '*' ) + '/haml/**/*.ss.haml' ],
        tasks: [ 'haml' ],
        options: {
          debounceDelay: 2000
        }
      }
    },    
  } );
  
  grunt.registerTask( 'haml', function () {

    var done = this.async(),
        args = [ 'haml/process' ];

    if ( theme ) {
      args.push( '--theme ' + theme );
    }

    exec(
      '../sapphire/sake ' + args.join( ' ' ),
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