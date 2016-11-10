<?php

return [
    /*
     * --------------------------------------------------------------------------
     * Path to the root directory
     * --------------------------------------------------------------------------
     *
     * This option determines the path to the root directory, it's where
     * the package will be looking for the env file. This file is
     * usually located in the root but you may change that.
     */

    'path' => realpath(base_path()),

    /*
     * --------------------------------------------------------------------------
     * Path to the public directory
     * --------------------------------------------------------------------------
     *
     * This option determines the path to the public directory, it's where
     * the package will be looking for the document root. This is
     * usually located in public but you may change that.
     */

    'public_path' => realpath(base_path('public_html')),
    
    /*
     * --------------------------------------------------------------------------
     * Environment file name
     * --------------------------------------------------------------------------
     *
     * This option determines the name or the env file. This file is
     * usually named .env you may change that.
     */

    'env-file' =>'.env',
    
    /*
     * --------------------------------------------------------------------------
     * Wordpress maintenance file
     * --------------------------------------------------------------------------
     *
     * This option determines the name or the maintenance file. This file is
     * usually named .maintenance you may change that.
     */

    'wp-maintenance-file' =>'wp/.maintenance',
];
