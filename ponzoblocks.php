<?php
/*
Plugin Name: Ponzoblocks
Plugin URI:  https://ponzotheme.nl/
Description: Create page blocks with gutenberg and ponzoblocks
Version:     0.0.2
Author:      Buro Ponzo
Author URI:  https://www.buroponzo.nl
Text Domain: ponzoblocks
 */



defined('ABSPATH') or die('Really?');
require "core/blocks.php";
require "core/options.php";
require "core/register.php";
require "helpers/theme.php";
require "helpers/reset.php";

class Ponzoblocks
{

    private $file;
    private $path;
    private $url;
    private $assets;
    private $themedir;
    private $themeurl;

    /*
     *  init variables within construct
     */

    public function __construct()
    {
        $url = plugin_dir_url(__FILE__);
        // urls
        $this->file = __FILE__;
        $this->path = plugin_dir_path(__FILE__);
        $this->url = $url;
        $this->assets = $url . '/dist';
        $this->themedir = get_template_directory();
        $this->themeurl = get_template_directory_uri();
        $this->version = '0.0.4';
        $this->fieldsversion = 'v1';
    }

    /*
     *
     *  This function will enque all scripts or styles when the user is admin
     *
     *  @type    action (init)
     *  @date    8/05/19
     *  @since    0.0.1
     *
     *  @param    N/A
     *  @return    N/A
     */
    public function getAssets()
    {


        wp_enqueue_script(
            'ponzoblocks-script',
            $this->assets . '/ponzoblocks.bundle.js',
            array(),
            $this->version
        );
        wp_enqueue_style(
            'ponzoblocks-style',
            $this->assets . '/ponzoblocks.css',
            array(),
            $this->version
        );
    }

    /*
    *
    *  Init reset, gutenbergblocks and options
    */
    public function init()
    {
        if (is_admin()) {
            add_action('enqueue_block_assets', array($this, 'getAssets'));
        }
    }
}

$blocks = new Ponzoblocks();
$blocks->init();
