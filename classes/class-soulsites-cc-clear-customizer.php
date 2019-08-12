<?php



if( !class_exists( 'SoulSites_CC_Clear_Customizer' ) ){
    class SoulSites_CC_Clear_Customizer{
        
        function __construct(){
            self::load_hooks();
        }

        public static function load_hooks(){
            add_filter('customize_loaded_components', array(__CLASS__, 'remove_core_customizer_elements'), 999, 2);
            add_action('customize_register', array(__CLASS__, 'clear_the_customizer'), 9999, 1);
        }

        /**
         * Filters out the core WP_Customizer sections so their functionality isn't available to the user in the Customizer
         **/
        public static function remove_core_customizer_elements($core_sections, $wp_customizer){

            // don't remove the sections if the user is a superadmin
            if(current_user_can('setup_network')){
                return $core_sections;
            }
            
            return array();
        }

        /**
         * Removes all sections and panels from the WP_Customizer that haven't been whitelisted
         **/
        public static function clear_the_customizer($wp_customizer){

            // don't remove the sections if the user is a superadmin
            if(current_user_can('setup_network')){
                return;
            }

            // exclude the default customizer items and the Soul™ brand control elements
            $exceptions = array('widgets', 'nav_menus', 'soultype2-control-panel', 'soultype2-font-presets');

            // remove any customizer panels that haven't been whitelisted
            $panels = $wp_customizer->panels();
            foreach($panels as $key => $panel){
                if(in_array($key, $exceptions)){
                    continue;
                }
                $wp_customizer->remove_panel($key);
            }

            // remove any customizer sections that haven't been whitelisted
            $sections = $wp_customizer->sections();
            foreach($sections as $key => $section){
                if(in_array($key, $exceptions)){
                    continue;
                }
                $wp_customizer->remove_section($key);
            }
        }
    }

    new SoulSites_CC_Clear_Customizer;
}

