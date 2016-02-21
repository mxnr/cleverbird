module.exports = function(grunt) {

    grunt.loadNpmTasks('grunt-bower-task');

    grunt.initConfig({
        bower: {
            install: {
                options: {
                    targetDir: './web/vendor/',
                    install: true,
                    copy: true,
                    cleanBowerDir: true
                }
            }
        }
    });

};