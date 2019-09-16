<?php

if(!class_exists('SoulSites_CC_Scripts')){
    class SoulSites_CC_Scripts{
        
        public function __construct(){
            self::load_hooks();
        }
        
        public static function load_hooks(){
            // output the selected font's src link in the page head
            add_action('wp_head', array(__CLASS__, 'output_soulsites_cc_font_link'));

            // output the user's selected font and color choices
            add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_soulsites_cc_styles'), 100);
            add_action('wp_enqueue_scripts', array(__CLASS__, 'output_soulsites_cc_color_css_variables'), 100);
            add_action('wp_enqueue_scripts', array(__CLASS__, 'output_soulsites_cc_font_css_variables'), 100);

            // create the bit of JS that shows/hides the custom site colors in the WP Customtizer
            add_action('customize_controls_print_scripts', array(__CLASS__, 'output_soulsites_cc_customizer_scripts'), 40);
        }

        /**
         * Outputs the style link for the selected font preset.
         **/
        public static function output_soulsites_cc_font_link(){

            // create the list of available font links by preset
            $available_font_src_links = array( 
            'default' => 'https://fonts.googleapis.com/css?family=IBM+Plex+Sans:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i|IBM+Plex+Serif:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i&display=swap',
            'system_ui'   => '',
            'alegreya'   => 'https://fonts.googleapis.com/css?family=Alegreya+Sans:100,100i,300,300i,400,400i,500,500i,700,700i,800,800i,900,900i|Alegreya:400,400i,500,500i,700,700i,800,800i,900,900i&display=swap',
            'dm'   => 'https://fonts.googleapis.com/css?family=DM+Sans:400,400i,500,500i,700,700i|DM+Serif+Display:400,400i&display=swap',
            'libre_franklin_libre_baskerville'   => 'https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700|Libre+Franklin:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap',
            'merriweather_cabin'   => 'https://fonts.googleapis.com/css?family=Cabin|Merriweather:300,300i,400,400i,700,700i,900,900i&display=swap',
            'proza_libre_cormorant_garamond'   => 'https://fonts.googleapis.com/css?family=Cormorant+Garamond:300,300i,400,400i,500,500i,600,600i,700,700i|Proza+Libre:400,400i,500,500i,600,600i,700,700i,800,800i&display=swap',
            'roboto'   => 'https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap'
            );

            // get the user's selected font schema
            $font_set = get_option('soulsites_available_font_presets', 'default');

            // if we have a link for the selected font schema
            if(isset($available_font_src_links[$font_set])){
                // output the style link for the font(s)
                ?>
                <link rel='stylesheet' id='soulsites-cc-font-choice-1'  href="<?php echo esc_url($available_font_src_links[$font_set]); ?>" type='text/css' media='all' />
                <?php
            }
        }
        
        public static function enqueue_soulsites_cc_styles(){
            // todo replace current stylesheet with a more finished one
            wp_enqueue_style('soulsites-customized-customizer-styles', SOULSITES_CC_URL_PATH . 'assets/css/soulsites-customized-customizer-styles.css');
            // todo it would probably be a good idea to merge the stylesheets when they start getting bigger and more numerous
            // enqueue the font's stylesheet
            wp_enqueue_style('soulsites-customized-customizer-font-styles', SOULSITES_CC_URL_PATH . 'assets/css/soulsites-customized-customizer-font-styles.css');
        }

        /**
         * Obtains the color settings the user has inputted in the Customizer,
         * and outputs CSS variables based on the settings.
         **/
        public static function output_soulsites_cc_color_css_variables(){
            // create the list of available color presets
            $available_presets = array( 
            	'off_white_paper' => array(
            		'primary' => '#fdfbf1', 
            		'secondary' => '#2C170B'
            	), //primary == the background/body color, secondary == the text color
                                        
	            'black_and_white' => array(
		            'primary' => '#ffffff', 
		            'secondary' => '#000000'
	            ),
	            'sunday_paper'    => array(
		            'primary' => '#F7FAFC', 
		            'secondary' => '#1A202C'
	            ),
	            'dark_mode'   => array(
		            'primary' => '#212121', 
		            'secondary' => '#ffffff'
	            ),
	            'tailwind_gray_dark'   => array(
		            'primary' => '#4A5568', 
		            'secondary' => '#F7FAFC'
	            ),
	            'solarized_red_light'   => array(
		            'primary' => '#fdf6e3', 
		            'secondary' => '#dc322f'
	            ),
	            'solarized_red_dark'   => array(
		            'primary' => '#dc322f', 
		            'secondary' => '#fdf6e3'
	            ),
	            
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

        /**
         * Obtains the font settings that the SoulSites user has selected and
         * outputs CSS variables that define the site's font families
         **/
        public static function output_soulsites_cc_font_css_variables(){
            // create the list of available font presets
            $available_presets = array( 
	            'default' => array(
		            'primary' => 'IBM Plex Serif, serif', 
		            'secondary' => 'IBM Plex Sans, sans-serif', 
		            'extra' => 'IBM Plex Sans, sans-serif'
	            ),
	            'system_ui' => array(
		            'primary' => 'system-ui, sans-serif', 
		            'secondary' => 'system-ui, sans-serif', 
		            'extra' => 'system-ui, sans-serif'
	            ),
	            'alegreya' => array(
		            'primary' => 'Alegreya, serif', 
		            'secondary' => 'Alegreya Sans, sans-serif', 
		            'extra' => 'Alegreya Sans, sans-serif'
	            ),
	            'dm' => array(
		            'primary' => 'DM Sans, serif', 
		            'secondary' => 'DM Serif Display, serif', 
		            'extra' => 'DM Serif Display, serif'
	            ),
	            'libre_franklin_libre_baskerville' => array(
		            'primary' => 'Libre Franklin, serif', 
		            'secondary' => 'Libre Baskerville, sans-serif', 
		            'extra' => 'Libre Franklin, sans-serif'
	            ),
	            'merriweather_cabin' => array(
		            'primary' => 'Merriweather, serif', 
		            'secondary' => 'Cabin, sans-serif', 
		            'extra' => 'Cabin, sans-serif'
	            ),
	            'proza_libre_cormorant_garamond' => array(
		            'primary' => 'Proza Libre, sans-serif', 
		            'secondary' => 'Cormorant Garamond, serif', 
		            'extra' => 'Cormorant Garamond, serif'
	            ),
	            'roboto' => array(
		            'primary' => 'Roboto, sans-serif', 
		            'secondary' => 'Roboto Slab, sans-serif', 
		            'extra' => 'Roboto Slab, sans-serif'
	            ),

            );

            // get the user's selected font options
            $font_set = get_option('soulsites_available_font_presets', 'default');
            
            // if the user has selected a custom font scheme
/*            if($color_set === 'custom'){
                // load the custom colors
//                $primary    = get_option('soulsites_custom_site_primary_color', '#ffffff');
//                $secondary  = get_option('soulsites_custom_site_secondary_color', '#000000');
            }else*/if(isset($available_presets[$font_set])){
                // if the user has selected from our list of preset fonts, apply the preset
                $primary    = $available_presets[$font_set]['primary'];
                $secondary  = $available_presets[$font_set]['secondary'];
                $extra      = $available_presets[$font_set]['extra'];
            }else{
                // if the user hasn't selected a font preset, or if for some reason we can't get the selected fonts, default to this preset
                $primary    = '"Chronicle SSm A", "Chronicle SSm B", serif';
                $secondary  = '"Sentinel A", "Sentinel B", serif';
                $extra      = '"Sentinel A", "Sentinel B", serif';
                //todo remove later, just here for contrast
                $primary    = 'cursive';
                $secondary  = 'cursive';
                $extra      = 'cursive';
            }

            ?>
            <style type="text/css">
                :root {
                    --primary-typeface: <?php echo $primary; ?>  !important;    /* primary font */
                    --secondary-typeface: <?php echo $secondary; ?> !important; /* secondary font */
                    --extra-typeface: <?php echo $extra; ?> !important;         /* extra font*/
                }
            </style>
            <?php
        }

        /**
         * Attaches a listener for showing and hiding the SSCC custom color options in the WP_Customizer depending on if the user has selected to use custom colors.
         **/
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
