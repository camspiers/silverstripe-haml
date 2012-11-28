module.exports = function( grunt ) {

  var exec  = require('child_process').exec,
      argv  = require('optimist').argv,
      sys   = require('sys'),
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