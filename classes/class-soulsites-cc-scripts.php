<?php

if(!class_exists('SoulSites_CC_Scripts')){
    class SoulSites_CC_Scripts{
        
        public function __construct(){
            self::load_hooks();
        }
        
        public static function load_hooks(){
            add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_soulsites_cc_styles'));
            add_action('wp_enqueue_scripts', array(__CLASS__, 'output_soulsites_cc_css_variables'), 100);
            
            add_action('customize_controls_print_scripts', array(__CLASS__, 'output_soulsites_cc_customizer_scripts'), 40);
        }
        
        public static function enqueue_soulsites_cc_styles(){
            // todo replace current stylesheet with a more finished one
            wp_enqueue_style('soulsites-customized-customizer-styles', SOULSITES_CC_URL_PATH . 'assets/css/soulsites-customized-customizer-styles.css');
        }

        /**
         * Obtains the color settings the user has inputted in the Customizer,
         * and outputs CSS variables based on the settings.
         **/
        public static function output_soulsites_cc_css_variables(){
            // create the list of available color presets
            $available_presets = array( 'off_white_paper' => array('primary' => '#fdfbf1', 'secondary' => '#2C170B'), //primary == the background/body color, secondary == the text color
                                        'black_and_white' => array('primary' => '#ffffff', 'secondary' => '#000000'),
                                        'sunday_paper'    => array('primary' => '#F7FAFC', 'secondary' => '#1A202C'),
                                        'red_and_green'   => array('primary' => '#ff0000', 'secondary' => '#27ff00')
            );

            // get the user's selected color options
            $color_set = get_option('soulsites_available_color_presets', '');
            
            // if the user has selected a custom color scheme
            if($color_set === 'custom'){
                // load the custom colors
                $primary    = get_option('soulsites_custom_site_primary_color', '#ffffff');
                $secondary  = get_option('soulsites_custom_site_secondary_color', '#000000');
            }elseif(isset($available_presets[$color_set])){
                // if the user has selected from our list of preset colors, apply the preset
                $primary    = $available_presets[$color_set]['primary'];
                $secondary  = $available_presets[$color_set]['secondary'];
            }else{
                // if the user hasn't selected a custom color, a color preset, or the color preset no longer exists, fallback to our defauly color scheme
                $primary    = '#ffffff'; // todo come up with a default color scheme
                $secondary  = '#000000';
            }

            // output the CSS variables based on the user's input
            ?>
            <style type="text/css">
                :root {
                  --primary-color: <?php echo $primary; ?> !important; /* background */
                  --secondary-color: <?php echo $secondary; ?> !important; /* foreground */
                }
            </style>
            <?php
        }

        public static function output_soulsites_cc_customizer_scripts(){
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    
                    // setup a delay for the color scheme selector so that we're sure the selector has been created before trying to apply it's listening function
                    var loopCount = 0;
                    var colorChangeSelectListener = setTimeout(function(){
                        // if the color selector exists or we've been trying to find it for 5 seconds
                        var colorSelector = jQuery('#_customize-input-soulsites_available_color_presets')
                        if(colorSelector.length > 0 || loopCount > 20){
                            // try applying the listener function and exit the loop
                            jQuery('#_customize-input-soulsites_available_color_presets').on('change', showHideCustomColorInputs);
                            showHideCustomColorInputs();
                            clearTimeout(colorChangeSelectListener);
                        }else{
                            loopCount++;
                        }
                        
                    }, 250);

                    /**
                     * Displays or hides the custom primary and secondary color inputs depending on if
                     * the user has opted to color the soulsite with a custom color scheme
                     **/
                    function showHideCustomColorInputs(){
                        var selector = jQuery('#_customize-input-soulsites_available_color_presets');
                        var customColors = jQuery('#customize-control-soulsites_custom_site_primary_color, #customize-control-soulsites_custom_site_secondary_color');

                        if(selector.length > 0 && selector.val() === 'custom'){
                            customColors.css('display', 'list-item');
                        }else{
                            customColors.css('display', 'none');
                        }
                    }

                    // call showHide once at page load to setup the input display
                    showHideCustomColorInputs();
                });
            </script>
            <?php
        }
    }
    
    new SoulSites_CC_Scripts;
}
