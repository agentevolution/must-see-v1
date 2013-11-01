<?php

# add custom css editor 
add_action( 'admin_enqueue_scripts', 'custom_css_scripts' );
add_action( 'admin_menu', 'custom_css_admin_page' );
add_action( 'admin_init', 'register_custom_css_setting' );
add_action( 'wp_head', 'display_custom_css' );

/**
 * Loads ACE editor scripts for custom CSS edits
 */
function custom_css_scripts( $hook ) {
    if ( 'appearance_page_custom_css_admin_page_content' == $hook ) {
        wp_enqueue_script( 'ace_code_highlighter_js', get_stylesheet_directory_uri() . '/ace/ace.js', '', '1.0.0', true );
        wp_enqueue_script( 'ace_mode_js', get_stylesheet_directory_uri() . '/ace/mode-css.js', array( 'ace_code_highlighter_js' ), '1.0.0', true );
        wp_enqueue_script( 'custom_css_js', get_stylesheet_directory_uri() . '/lib/js/custom-css.js', array( 'jquery', 'ace_code_highlighter_js' ), '1.0.0', true );
    }
}

/**
 * Adds ACE editor page to admin
 */
function custom_css_admin_page() {
    add_theme_page( 'Custom CSS',  __( 'Custom CSS' ), 'edit_theme_options', 'custom_css_admin_page_content', 'custom_css_admin_page_content' );
}

/**
 * Registers custom_css setting
 */
function register_custom_css_setting() {
    register_setting( 'custom_css', 'custom_css',  'custom_css_validation');
}

/**
 * Custom CSS admin page content
 */
function custom_css_admin_page_content() {
    // The default message that will appear
    $custom_css_default = __( '/*
Welcome to the Custom CSS editor!
 
Please add all your custom CSS here and avoid modifying the core theme files, since that\'ll make upgrading the theme problematic. Your custom CSS will be loaded after the theme\'s stylesheets, which means that your rules will take precedence. Just add your CSS here for what you want to change, you don\'t need to copy all the theme\'s style.css content.
*/' );
    $custom_css = get_option( 'custom_css', $custom_css_default );
    ?>
    <div class="wrap">
        <div id="icon-themes" class="icon32"></div>
        <h2><?php _e( 'Custom CSS' ); ?></h2>
        <?php if ( ! empty( $_GET['settings-updated'] ) ) echo '<div id="message" class="updated"><p><strong>' . __( 'Custom CSS updated.' ) . '</strong></p></div>'; ?>
 
        <form id="custom_css_form" method="post" action="options.php" style="margin-top: 15px;">
 
            <?php settings_fields( 'custom_css' ); ?>
 
            <div id="custom_css_container">
                <div name="custom_css" id="custom_css" style="border: 1px solid #DFDFDF; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; width: 100%; height: 400px; position: relative;"></div>
            </div>
 
            <textarea id="custom_css_textarea" name="custom_css" style="display: none;"><?php echo $custom_css; ?></textarea>
            <p><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" /></p>
        </form>
    </div>
<?php
}

/**
 * Validate code
 */
function custom_css_validation( $input ) {
    if ( ! empty( $input['custom_css'] ) )
        $input['custom_css'] = trim( $input['custom_css'] );
    return $input;
}

/**
 * Loads custom CSS to head
 */
function display_custom_css() {
    $custom_css = get_option( 'custom_css' );
    echo '<style type="text/css">/* Custom CSS */';
    echo $custom_css;
    echo '</style>';
}