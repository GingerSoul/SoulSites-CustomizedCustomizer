<?php

if(!class_exists('SoulSites_CC_Create_Customizer_Sections')){
    
    class SoulSites_CC_Create_Customizer_Sections{
        
        function __construct(){
            self::load_hooks();
        }

        public static function load_hooks(){
            add_action('customize_register', array(__CLASS__, 'create_the_soulsite_customizer_sections'), 99999, 1);
        }

        /**
         * Creates the SoulSite Customizer sections
         **/
        public static function create_the_soulsite_customizer_sections($wp_customizer){
            // create the SoulSites color selector sections
            self::create_soulsites_color_control_sections($wp_customizer);

            // create the SoulSites font controls
            self::create_soulsites_font_controls($wp_customizer);
        }

        /**
         * Creates the SoulSites color controls in the WP_Customizer 
         **/
        private static function create_soulsites_color_control_sections($wp_customizer){
            // create the SoulSites color selection section
            $wp_customizer->add_section( 'soulsites_color_selection', array(
                                                                        'title' => __('SoulSites Color Selection', 'soulsites-cc'),
                                                                        'description' => __('Control your site\s color scheme from this panel of options.', 'soulsites-cc'),
                                                                        'priority' => 160,
                                                                        'capability' => 'edit_theme_options') //todo make sure these are the appropriate permissions for a soulsite user
            );

            // create the setting to store the selected preset option in
            $wp_customizer->add_setting(
                'soulsites_available_color_presets',
                array(
                    'default'           => '',
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );

            // create the dropdown selector for picking a color preset and populate it with the preset options
            $wp_customizer->add_control(
                'soulsites_available_color_presets',
                array(
                    'label'       => __('Select a color preset for your SoulSite', 'soulsites-cc'),
                    'description' => __('Choose a color scheme from our carefully created selection of options', 'soulsites-cc'),
                    'section'     => 'soulsites_color_selection',
                    'settings'    => 'soulsites_available_color_presets',
                    'type'        => 'select',
                    'choices'     => array(
                        'off_white_paper'       => __('Off White Paper', 'soulsites-cc'),
                        'black_and_white'       => __('Black and White', 'soulsites-cc'),
                        'sunday_paper'          => __('Sunday Paper', 'soulsites-cc'),
                        'dark_mode'             => __('Dark Mode', 'soulsites-cc'),
                        'tailwind_gray_dark'    => __('Tailwind Gray (Dark)', 'soulsites-cc'),
                        'solarized_red_light'   => __('Solarized Red (Light)', 'soulsites-cc'),
                        'solarized_red_dark'    => __('Solarized Red (Dark)', 'soulsites-cc'),
                        'custom'                => __('Use Custom Colors', 'soulsites-cc'),
                    ),
                )
            );

            // create the setting for storing a custom primary color option
            $wp_customizer->add_setting(
                'soulsites_custom_site_primary_color',
                array(
                    'default'           => '',
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options', //todo make sure these are the appropriate permissions for a soulsite user
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );

            // create the colorpicker to allow the user to select a custom primary color
            $wp_customizer->add_control(
                new WP_Customize_Color_Control(
                    $wp_customizer,
                    'soulsites_custom_site_primary_color',
                    array(
                        'label'       => __('Select a Primary Color', 'soulsites-cc'),
                        'description' => __('The site\'s primary color is the color used for backgrounds and wide open spaces without text or images. It\'s the primary color since the site\'s visitors will be seeing it a lot, and will likely be the color they remember your site for.', 'soulsites-cc'),
                        'section'     => 'soulsites_color_selection',
                        'settings'    => 'soulsites_custom_site_primary_color',
                    )
                )
            );

            // create the setting for storing a custom secondary color option
            $wp_customizer->add_setting(
                'soulsites_custom_site_secondary_color',
                array(
                    'default'           => '',
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options', //todo make sure these are the appropriate permissions for a soulsite user
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );

            // create the colorpicker for selecting a custom secondary color option
            $wp_customizer->add_control(
                new WP_Customize_Color_Control(
                    $wp_customizer,
                    'soulsites_custom_site_secondary_color',
                    array(
                        'label'       => __('Select a Secondary Color', 'soulsites-cc'),
                        'description' => __('The site\'s secondary color is the color used for text, borders and separators. It\'s called the secondary color since it\'s less immediately impacting than the primary color, but it\'s extremely important since it will be the color used for your important content.', 'soulsites-cc'),
                        'section'     => 'soulsites_color_selection',
                        'settings'    => 'soulsites_custom_site_secondary_color',
                    )
                )
            );

            /** 
             * Allows the user to flip which color is primary and which is secondary
             **/
            // create the setting to store the if the user wants to flip the primary and secondary colors around
            $wp_customizer->add_setting(
                'soulsites_flip_color_order',
                array(
                    'default'           => '',
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
            
            // Register controls.
            $wp_customizer->add_control(
                'soulsites_flip_color_order',
                array(
                    'label'         => __('Reverse the Primary and Secondary site colors?', 'soulsites-cc'),
                    'description'   => __('You can choose to reverse the Primary and Secondary colors with this checkbox. Reversing the colors means that the Secondary color will be used for the site body and open spaces, and the Primary color will be used for text, borders, and separators.', 'soulsites-cc'),
                    'section'       => 'soulsites_color_selection',
                    'settings'      => 'soulsites_flip_color_order',
                    'type'          => 'checkbox'
                )
            );
        }

        /**
         * Creates the special font combination selector in the SoulSites WP_Customizer 
         **/
        private static function create_soulsites_font_controls($wp_customizer){
            // create the SoulSites font combination selector section
            $wp_customizer->add_section( 'soulsites_font_selection', array(
                                                                        'title' => __('SoulSites Font Selection', 'soulsites-cc'),
                                                                        'description' => __('Control your site\s fonts from this selector', 'soulsites-cc'),
                                                                        'priority' => 170,
                                                                        'capability' => 'edit_theme_options') //todo make sure these are the appropriate permissions for a soulsite user
            );

            // create the setting to store the selected font combination in
            $wp_customizer->add_setting(
                'soulsites_available_font_presets',
                array(
                    'default'           => 'default',
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );

            // create the dropdown selector for picking a font preset and populate it with the preset options
            $wp_customizer->add_control(
                'soulsites_available_font_presets',
                array(
                    'label'       => __('Select a font combination preset for your SoulSite', 'soulsites-cc'),
                    'description' => __('Choose a font combination from our carefully created selection of compatible fonts', 'soulsites-cc'),
                    'section'     => 'soulsites_font_selection',
                    'settings'    => 'soulsites_available_font_presets',
                    'type'        => 'select',
                    'choices'     => array(
                        'default'                           => __('IBM Plex', 'soulsites-cc'),
                        'system_ui'                         => __('System UI', 'soulsites-cc'),
                        'alegreya'                          => __('Alegreya', 'soulsites-cc'),
                        'dm'                                => __('DM', 'soulsites-cc'),
                        'libre_franklin_libre_baskerville'  => __('Libre Franklin / Libre Baskerville', 'soulsites-cc'),
                        'merriweather_cabin'                => __('Merriweather / Cabin', 'soulsites-cc'),
                        'proza_libre_cormorant_garamond'    => __('Proza Libre / Cormorant Garamond', 'soulsites-cc'),
                        'roboto_roboto_slab'                => __('Roboto / Roboto Slab', 'soulsites-cc'),
                        'source_sans_pro_playfair_display'  => __('Source Sans Pro / Playfair Display', 'soulsites-cc'),
                        'titillum_web_dosis'                => __('Titillum Web / Dosis', 'soulsites-cc'),
                        'work_sans_taviraj'                 => __('Work Sans / Taviraj', 'soulsites-cc'),
                    ),
                )
            );

            /*** FONT EXCLUSIVITY CONTROLS ***/
            /** 
             * Allows the user to set one of the fonts, either Primary or Secondary, as the only font that's used in the site.
             **/
            // create the setting to store the if the user wants to set one of the fonts as the exclusive one
            $wp_customizer->add_setting(
                'soulsites_use_one_font_exclusively',
                array(
                    'default'           => '',
                    'type'              => 'option',
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
            
            // Register controls.
            $wp_customizer->add_control(
                'soulsites_use_one_font_exclusively',
                array(
                    'label'         => __('Set one font in the combination as the exclusive one?', 'soulsites-cc'),
                    'description'   => __('You can set one of the fonts offered in the combination as the only one to be used on the site. This is handy for "minimalist" style sites, but removes the contrast that comes from having a font combination.', 'soulsites-cc'),
                    'section'       => 'soulsites_font_selection',
                    'settings'      => 'soulsites_use_one_font_exclusively',
                    'type'          => 'select',
                    'choices'       => array(
                        ''          => __('Don\'t use a font exclusively', 'soulsites-cc'),
                        'primary'   => __('Use the Primary font exclusively', 'soulsites-cc'),
                        'secondary' => __('Use the Secondary font exculsively', 'soulsites-cc'),
                    ),
                )
            );
            
        }
    }
    new SoulSites_CC_Create_Customizer_Sections;
}
