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
            'cormorant'   => 'https://fonts.googleapis.com/css?family=Cormorant+Garamond:300,300i,400,400i,500,500i,600,600i,700,700i|Cormorant+SC:300,400,500,600,700&display=swap',
            'dm'   => 'https://fonts.googleapis.com/css?family=DM+Sans:400,400i,500,500i,700,700i|DM+Serif+Display:400,400i&display=swap',
            'libre'   => 'https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700|Libre+Franklin:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap',
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
	            'tailwind_gray'   => array(
		            'primary' => '#4A5568', 
		            'secondary' => '#F7FAFC'
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

            // get if the user has reversed the color order
            $colors_reversed = get_option('soulsites_flip_color_order');

            // output the CSS variables based on the user's input
            ?>
            <style type="text/css">
                :root {
                  --primary-color: <?php echo ('1' !== $colors_reversed) ? $primary : $secondary; ?> !important; /* background */
                  --secondary-color: <?php echo ('1' !== $colors_reversed) ? $secondary : $primary; ?> !important; /* foreground */
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
	            'cormorant' => array(
		            'primary' => 'Cormorant Garamond, serif', 
		            'secondary' => 'Cormorant SC, serif', 
		            'extra' => 'Cormorant SC, serif'
	            ),
	            'dm' => array(
		            'primary' => 'DM Sans, serif', 
		            'secondary' => 'DM Serif Display, serif', 
		            'extra' => 'DM Serif Display, serif'
	            ),
	            'libre' => array(
		            'primary' => 'Libre Baskerville, serif', 
		            'secondary' => 'Libre Franklin, sans-serif', 
		            'extra' => 'Libre Franklin, sans-serif'
	            ),
	            'roboto' => array(
		            'primary' => 'Roboto, sans-serif', 
		            'secondary' => 'Roboto Slab, sans-serif', 
		            'extra' => 'Roboto Slab, sans-serif'
	            ),

            );

            // get the user's selected font options
            $font_set = get_option('soulsites_available_font_presets');

            // find out if the user has set one font family to be the only one used on the site
            $exclusive_font_set = get_option('soulsites_use_one_font_exclusively', '');

            if(isset($available_presets[$font_set])){
                // if the user has selected from our list of preset fonts, apply the preset
                $primary    = $available_presets[$font_set]['primary'];
                $secondary  = $available_presets[$font_set]['secondary'];
                $extra      = $available_presets[$font_set]['extra'];

                // if the user has selected the Primary font as the only one to display on the site
                if('primary' === $exclusive_font_set){
                    // set the other font variables to the same as the Primary
                    $secondary  = $primary;
                    $extra      = $primary;

                }elseif('secondary' === $exclusive_font_set){
                    // if the user has selected the Secondary font as the main font,  set the other font variables to the same as the Secondary
                    $primary    = $secondary;
                    $extra      = $secondary;
                }
            }else{
                // if the user hasn't selected a font preset, or if for some reason we can't get the selected fonts, default to this preset
                $primary    = '"Chronicle SSm A", "Chronicle SSm B", serif';
                $secondary  = '"Sentinel A", "Sentinel B", serif';
                $extra      = '"Sentinel A", "Sentinel B", serif';
                //todo remove later, just here for contrast
                $primary    = 'cursive';
                $secondary  = 'cursive';
                $extra      = 'cursive';
                
                // if the user has selected the Primary font as the only one to display on the site
                if('primary' === $exclusive_font_set){
                    // set the other font variables to the same as the Primary
                    $secondary  = $primary;
                    $extra      = $primary;

                }elseif('secondary' === $exclusive_font_set){
                    // if the user has selected the Secondary font as the main font,  set the other font variables to the same as the Secondary
                    $primary    = $secondary;
                    $extra      = $secondary;
                }
            }

            // get if the user has reversed the font order
            $fonts_reversed = get_option('soulsites_flip_font_order');

            ?>
            <style type="text/css">
                :root {
                    --primary-typeface: <?php echo ('1' !== $fonts_reversed) ? $primary : $secondary; ?>    !important;     /* primary font */
                    --secondary-typeface: <?php echo ('1' !== $fonts_reversed) ? $secondary : $primary; ?>  !important;     /* secondary font */
                    --extra-typeface: <?php echo $extra; ?> !important;                                                     /* extra font*/
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
                    var colorLoopCount = 0;
                    var colorChangeSelectListener = setTimeout(function(){
                        // if the color selector exists or we've been trying to find it for 5 seconds
                        var colorSelector = jQuery('#_customize-input-soulsites_available_color_presets')
                        if(colorSelector.length > 0 || colorLoopCount > 20){
                            // try applying the listener function and exit the loop
                            colorSelector.on('change', showHideCustomColorInputs);
                            showHideCustomColorInputs();
                            clearTimeout(colorChangeSelectListener);
                        }else{
                            colorLoopCount++;
                        }
                        
                    }, 250);

                    // setup a delay for the font selector so that the "Font Flipper" is only shown when there isn't an exclusive font set
                    var fontLoopCount = 0;
                    var fontExclusivitySelectListener = setTimeout(function(){
                        // if the font exclusivity select exists or we've been trying to find it for 5 seconds
                        var fontSelector = jQuery('#_customize-input-soulsites_use_one_font_exclusively')
                        if(fontSelector.length > 0 || fontLoopCount > 20){
                            // try applying the listener function and exit the loop
                            fontSelector.on('change', showHideReverseFontInput);
                            showHideReverseFontInput();
                            clearTimeout(fontExclusivitySelectListener);
                        }else{
                            fontLoopCount++;
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
                    
                    /**
                     * Displays or hides the reverse font input depending on if
                     * the user has opted to set an exclusive font for the site
                     **/
                    function showHideReverseFontInput(){
                        var selector    = jQuery('#_customize-input-soulsites_use_one_font_exclusively');
                        var fontReverse = jQuery('#customize-control-soulsites_flip_font_order');

                        if(selector.length > 0 && selector.val() === ''){
                            fontReverse.css('display', 'list-item');
                        }else{
                            fontReverse.css('display', 'none');
                        }
                    }

                    // call showHide once at page load to setup the input display
                    showHideReverseFontInput();
                });
            </script>
            <?php
        }
    }
    
    new SoulSites_CC_Scripts;
}
