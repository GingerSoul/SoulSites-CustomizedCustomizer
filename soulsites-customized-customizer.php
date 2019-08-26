<?php
/**
 * Plugin Name: SoulSites Customized Customizer
 * Plugin URI:	Todo fill out
 * Description: A plugin that removes the inputs from the WordPress customizer and replaces them with a number of custom inputs specifically designed for use on SoulSites
 * Author:		Soul Plugins
 * Author URI:	URI: https://soulplugins.co
 * Version:		1.1
 * Text Domain: soulsites-cc
 * Domain Path: /assets/lang/
 */

if( !class_exists( 'SoulSites_Customized_Customizer' ) ){
    
    class SoulSites_Customized_Customizer{
        
        function __construct(){
            self::load_constants();
            self::load_classes();
        }
        
        public static function load_constants(){
            define('SOULSITES_CC_PLUGIN_VERSION', '1.0');
            define('SOULSITES_CC_URL_PATH', trailingslashit(plugin_dir_url(__FILE__)));
            define('SOULSITES_CC_PATH', trailingslashit(plugin_dir_path(__FILE__)));
        }

        public static function load_classes(){
            require(SOULSITES_CC_PATH . 'classes/class-soulsites-cc-clear-customizer.php');
            require(SOULSITES_CC_PATH . 'classes/class-soulsites-cc-create-customizer-sections.php');
            require(SOULSITES_CC_PATH . 'classes/class-soulsites-cc-scripts.php');
        }
    }

    add_action('plugins_loaded', function(){
        new SoulSites_Customized_Customizer;
    }, 999);
}




































?>
