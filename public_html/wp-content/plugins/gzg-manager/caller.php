<?php
/**
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 8/24/15
 * Time: 11:34 AM
 */
/*
Plugin Name: Gigazone Gaming Manager
Plugin URI: http://www.paulbunyan.net
Description:  Loader for Gigazone Gaming Championship games manager app
Author: Nelson Castillo
Version: 0.01
*/

require_once __DIR__ . '/../../../../vendor/autoload.php';
global $db_functions;

function gzLoaderAddCss()
{
    echo '<link rel="stylesheet" media="all" href="' . plugin_dir_url(__FILE__) . 'css/gzg-manager.css" type="text/css" />';
}

add_action('admin_head', 'gzLoaderAddCss');

function gzLoader()
{
    $title = __(get_plugin_data(__FILE__)['Name']);
    $url = 'http://'.$_SERVER['HTTP_HOST']."/app/manage/";
    echo
        '<div class="wrap gzg-wrap">
            <h1>' . esc_html($title) . '</h1>
            <div class="gzg-iframe-wrap"> 
                <iframe src="/app/manage/game" id="theIframe" class="gzg-iframe"></iframe>
            </div>
        </div>
        ';

    return null;
}

function gzgPluginMenu()
{
    $icon = '';
    if (file_exists(get_template_directory() . '/images/logos/molecule.svg')) {
        $icon = 'data:image/svg+xml;base64,' . base64_encode(
                file_get_contents(
                    get_template_directory() . '/images/logos/molecule.svg'
                )
            );

    }

    add_menu_page(
        'Gigazone Gaming Manager',
        'Gaming Manager',
        'manage_options',
        'gzg-loader',
        'gzLoader',
        $icon
    );
}

add_action('admin_menu', 'gzgPluginMenu');
