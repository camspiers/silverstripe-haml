module.exports = function( grunt ) {

  var exec = require('child_process').exec,
      argv = require('optimist').argv,
      sys = require('sys');
  
  grunt.loadNpmTasks( 'grunt-contrib-watch' );

  grunt.initConfig( {
    watch: {
      files: [ '../themes/*/haml/**/*.ss.haml' ],
      tasks: [ 'haml' ]
    },    
  } );
  
  grunt.registerTask( 'haml', function (deets) {

    var done = this.async(),
      cmd = '../sapphire/sake haml' + ( argv.theme ? ' theme=' + argv.theme : '' );

    sys.puts( 'Running: ' + cmd );

    exec(
      cmd,
      function (error, stdout, stderr) {
        if (stdout) {
          sys.puts(stdout);
        }
        if (stderr) {
          sys.puts(stderr);
        }
        done();
      }
    );

  } );

  grunt.registerTask( 'default', [ 'watch' ] );

};