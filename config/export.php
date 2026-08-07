<?php

return [

    /*
     * If true, the exporter will crawl through your site's pages to determine
     * the paths that need to be exported.
     */
    'crawl' => true,

    /*
     * Add additional paths to be added to the export here.
     */
    'paths' => [],

    /*
     * Files and folders that should be included in the build.
     */
    'include_files' => [
        'public/build' => 'build',
        'public' => '',
    ],

    /*
     * File patterns that should be excluded from the included files.
     */
    'exclude_file_patterns' => [
        '/\.php$/',
        '/mix-manifest\.json$/',
        '/storage$/', // Excludes the Windows symlink inside public
    ],

    /*
     * Whether or not the destination folder should be emptied before starting
     * the export.
     */
    'clean_before_export' => true,

    'disk' => null,

    /*
     * Run Vite build automatically before exporting
     */
    'before' => [
        'assets' => 'npm run build',
    ],

    'after' => [],

];