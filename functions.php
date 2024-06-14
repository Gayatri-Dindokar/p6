<?php

function load_stylesheets() {
    // Enqueue custom styles
    // wp_register_style('custom', get_template_directory_uri() . '/css/common.min.css');
    // wp_enqueue_style('custom'); 

	wp_register_style('custom', get_template_directory_uri() . '/css/custom.css');
    wp_enqueue_style('custom'); 

    // Enqueue the main stylesheet
    wp_enqueue_style('pixel-style', get_stylesheet_uri());

    // Enqueue reset CSS
    wp_enqueue_style('reset-css', get_template_directory_uri() . '/css/reset.css');

    // Enqueue common CSS
    // wp_enqueue_style('common-css', get_template_directory_uri() . '/css/common.min.css');
}
add_action('wp_enqueue_scripts', 'load_stylesheets');

function pixel_scripts() {
    wp_enqueue_script('script_js_jquery', get_template_directory_uri() . '/js/jquery.3.7.1.js', array('jquery'), null, true);
    wp_enqueue_script('pixel_script_masonry', get_template_directory_uri() . '/js/masonry.pkgd.min.js', array('jquery'), null, true);
    // wp_enqueue_script('bx_slider', get_template_directory_uri() . '/js/jquery.bxslider.js', array('jquery'), null, true);
    wp_enqueue_script('images_loaded', get_template_directory_uri() . '/js/imagesloaded.js', array('jquery'), null, true);
    // wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/script-min.js', array('jquery'), null, true);
    wp_enqueue_script('script_js', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
    wp_enqueue_script('script_js_custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'pixel_scripts');

 add_theme_support( 'custom-logo' );
 add_theme_support( 'post-thumbnails' );
 add_theme_support('menus');
 register_nav_menus(
    array(
        'top-menu' => __('Top Menu','theme'),
        'sidebar-menu'=> __('side menu','theme'),
       

    )
    );

   


  
  

add_action('add_meta_boxes', 'add_project_metabox');
function add_project_metabox() {
    add_meta_box(
        'client_metabox',
        'Page Meta Data', 
        'render_client_metabox', 
        'page', 
        'normal', 
        'high'
    );
}
function render_client_metabox($post) {
   
    $page_title = get_post_meta($post->ID, 'page_title', true);
    $page_subtitle = get_post_meta($post->ID, 'page_subtitle', true);

    // Add nonce for security and authentication
    wp_nonce_field('save_client_metabox', 'client_metabox_nonce');
    ?>
	 <style>
        .meta-box-wrapper {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .meta-box-wrapper label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .meta-box-wrapper input[type="text"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .meta-box-wrapper input[type="text"]:focus {
            border-color: #007cba;
            box-shadow: 0 0 5px rgba(0, 124, 186, 0.5);
            outline: none;
        }
        .meta-box-wrapper p {
            margin-bottom: 20px;
        }
    </style>
     <div class="meta-box-wrapper">
        <p>
            <label for="page_title">Page Title:</label>
            <input type="text" id="page_title" name="page_title" value="<?php echo esc_attr($page_title); ?>">
        </p>
        <p>
            <label for="page_subtitle">Page Subtitle:</label>
            <input type="text" id="page_subtitle" name="page_subtitle" value="<?php echo esc_attr($page_subtitle); ?>">
        </p>
    </div>
    <?php
}

// Save the meta box data
add_action('save_post', 'save_project_client');
function save_project_client($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['client_metabox_nonce'])) {
        return $post_id;
    }

    $nonce = $_POST['client_metabox_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'save_client_metabox')) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Sanitize and save the data
    if (isset($_POST['page_title'])) {
        update_post_meta($post_id, 'page_title', sanitize_text_field($_POST['page_title']));
    }

    if (isset($_POST['page_subtitle'])) {
        update_post_meta($post_id, 'page_subtitle', sanitize_text_field($_POST['page_subtitle']));
    }
}


// custom post type for project
function create_project_cpt() {
    $labels = array(
        'name' => _x('projects', 'Post Type General Name', 'textdomain'),
        'singular_name' => _x('project', 'Post Type Singular Name', 'textdomain'),
        'menu_name' => __('projects', 'textdomain'),
        'name_admin_bar' => __('project', 'textdomain'),
        'archives' => __('project Archives', 'textdomain'),
        'attributes' => __('project Attributes', 'textdomain'),
        'parent_item_colon' => __('Parent project:', 'textdomain'),
        'all_items' => __('All projects', 'textdomain'),
        'add_new_item' => __('Add New project', 'textdomain'),
        'add_new' => __('Add New', 'textdomain'),
        'new_item' => __('New project', 'textdomain'),
        'edit_item' => __('Edit project', 'textdomain'),
        'update_item' => __('Update project', 'textdomain'),
        'view_item' => __('View project', 'textdomain'),
        'view_items' => __('View projects', 'textdomain'),
        'search_items' => __('Search project', 'textdomain'),
        'not_found' => __('Not found', 'textdomain'),
        'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
        'featured_image' => __('Featured Image', 'textdomain'),
        'set_featured_image' => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image' => __('Use as featured image', 'textdomain'),
        'insert_into_item' => __('Insert into project', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'textdomain'),
        'items_list' => __('projects list', 'textdomain'),
        'items_list_navigation' => __('projects list navigation', 'textdomain'),
        'filter_items_list' => __('Filter projects list', 'textdomain'),
    );
    $args = array(
        'label' => __('project', 'textdomain'),
        'description' => __('project Description', 'textdomain'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields',),
        'taxonomies' => array('category', 'post_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon'  => 'dashicons-project',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('project', $args);
}
add_action('init', 'create_project_cpt', 0);

function project_custom_fields() {
    add_meta_box(
        'project_meta_box', // $id
        'project Details', // $title
        'show_project_meta_box', // $callback
        'project', // $screen
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'project_custom_fields');

function show_project_meta_box() {
    global $post;  
    $btn_link_text = get_post_meta($post->ID, 'btn_link_text', true);
    $btn_link = get_post_meta($post->ID, 'btn_link', true);
    ?>
    <p>
        <label for="btn_link_text">Button Link Text:</label>
        <input type="text" name="btn_link_text" id="btn_link_text" value="<?php echo $btn_link_text; ?>" />
    </p>
    <p>
        <label for="btn_link">Button Link URL:</label>
        <input type="text" name="btn_link" id="btn_link" value="<?php echo $btn_link; ?>" />
    </p>
    <?php
}

function save_project_custom_fields($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['btn_link_text']) || !isset($_POST['btn_link'])) return;
    update_post_meta($post_id, 'btn_link_text', sanitize_text_field($_POST['btn_link_text']));
    update_post_meta($post_id, 'btn_link', esc_url_raw($_POST['btn_link']));
}
add_action('save_post', 'save_project_custom_fields');



// Register Custom Post Type for Services
function create_services_cpt() {

    $labels = array(
        'name'                  => _x( 'Services', 'Post Type General Name', 'textdomain' ),
        'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'textdomain' ),
        'menu_name'             => __( 'Services', 'textdomain' ),
        'name_admin_bar'        => __( 'Service', 'textdomain' ),
        'archives'              => __( 'Service Archives', 'textdomain' ),
        'attributes'            => __( 'Service Attributes', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Service:', 'textdomain' ),
        'all_items'             => __( 'All Services', 'textdomain' ),
        'add_new_item'          => __( 'Add New Service', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'new_item'              => __( 'New Service', 'textdomain' ),
        'edit_item'             => __( 'Edit Service', 'textdomain' ),
        'update_item'           => __( 'Update Service', 'textdomain' ),
        'view_item'             => __( 'View Service', 'textdomain' ),
        'view_items'            => __( 'View Services', 'textdomain' ),
        'search_items'          => __( 'Search Service', 'textdomain' ),
        'not_found'             => __( 'Not found', 'textdomain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'textdomain' ),
        'featured_image'        => __( 'Featured Image', 'textdomain' ),
        'set_featured_image'    => __( 'Set featured image', 'textdomain' ),
        'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
        'use_featured_image'    => __( 'Use as featured image', 'textdomain' ),
        'insert_into_item'      => __( 'Insert into service', 'textdomain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this service', 'textdomain' ),
        'items_list'            => __( 'Services list', 'textdomain' ),
        'items_list_navigation' => __( 'Services list navigation', 'textdomain' ),
        'filter_items_list'     => __( 'Filter services list', 'textdomain' ),
    );

    $args = array(
        'label'                 => __( 'Service', 'textdomain' ),
        'description'           => __( 'Custom Post Type for Services', 'textdomain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-hammer',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type( 'service', $args );

}
add_action( 'init', 'create_services_cpt', 0 );

// Register Meta Box for Service Subtitle
function service_add_meta_boxes() {
    add_meta_box(
        'service_subtitle_meta_box', // $id
        'Service Subtitle', // $title
        'service_subtitle_meta_box_callback', // $callback
        'service', // $screen
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'service_add_meta_boxes');

// Meta Box Callback Function
function service_subtitle_meta_box_callback( $post ) {
    wp_nonce_field( 'service_save_meta_box_data', 'service_meta_box_nonce' );

    $value = get_post_meta( $post->ID, 'service_subtitle', true );

    echo '<label for="service_subtitle">';
    _e( 'Service Subtitle', 'textdomain' );
    echo '</label> ';
    echo '<input type="text" id="service_subtitle" name="service_subtitle" value="' . esc_attr( $value ) . '" size="25" />';
}

// Save Meta Box Data
function save_services_custom_fields( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['service_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['service_meta_box_nonce'], 'service_save_meta_box_data' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Make sure that it is set.
    if ( ! isset( $_POST['service_subtitle'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['service_subtitle'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'service_subtitle', $my_data );
}
add_action( 'save_post', 'save_services_custom_fields' );




// Add meta box for show project with slider settings
function add_page_settings_meta_box() {
    add_meta_box(
        'page_settings_meta_box',
        'Page Settings',
        'render_page_settings_meta_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_page_settings_meta_box');

// Render meta box content
function render_page_settings_meta_box($post) {
    $show_project = get_post_meta($post->ID, 'show_project', true);
    ?>
    <label for="show_project">
        <input type="checkbox" id="show_project" name="show_project" <?php checked($show_project, 'on'); ?>>
        Show project slider Section
    </label>
    <?php
}

// Save meta box data
function save_project_settings_meta_box($post_id) {
    if (isset($_POST['show_project'])) {
        update_post_meta($post_id, 'show_project', 'on');
    } else {
        delete_post_meta($post_id, 'show_project');
    }
}
add_action('save_post', 'save_project_settings_meta_box');





// Add a meta box for Project Display Settings
function add_project_display_meta_box() {
    add_meta_box(
        'project_display_meta_box', // Unique ID for the meta box
        'Project Display Settings', // Meta box title
        'render_project_display_meta_box', // Callback function to render the meta box content
        'page', // Post type
        'side', // Context (where the box should appear)
        'high' // Priority (high, core, default, low)
    );
}
add_action('add_meta_boxes', 'add_project_display_meta_box');

// metabox without slider
// Render meta box content
function render_project_display_meta_box($post) {
    $display_project_section = get_post_meta($post->ID, 'display_project_section', true);
    ?>
    <label for="display_project_section">
        <input type="checkbox" id="display_project_section" name="display_project_section" <?php checked($display_project_section, 'on'); ?>>
        Show project section
    </label>
    <?php
}

// Save meta box data
function save_project_display_meta_box($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['project_display_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['project_display_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'save_project_display_meta_box')) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    // Sanitize the user input.
    $display_project_section = isset($_POST['display_project_section']) ? 'on' : '';

    // Update the meta field in the database.
    update_post_meta($post_id, 'display_project_section', $display_project_section);
}
add_action('save_post', 'save_project_display_meta_box');

// Register the nonce field
function add_project_display_nonce_field() {
    global $post;
    if ($post->post_type == 'page') {
        wp_nonce_field('save_project_display_meta_box', 'project_display_nonce');
    }
}
add_action('edit_form_after_title', 'add_project_display_nonce_field');








// show and hide banner of page title and subtitle

// Add meta box for page settings
function add_page_banner_meta_box() {
    add_meta_box(
        'page_banner_meta_box',
        'Page banner',
        'render_page_banner_meta_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_page_banner_meta_box');

// Render meta box content
function render_page_banner_meta_box($post) {
    $show_banner = get_post_meta($post->ID, 'show_banner', true);
    ?>
    <label for="show_banner">
        <input type="checkbox" id="show_banner" name="show_banner" <?php checked($show_banner, 'on'); ?>>
        Show banner Section
    </label>
    <?php
}

// Save meta box data
function save_page_banner_meta_box($post_id) {
    if (isset($_POST['show_banner'])) {
        update_post_meta($post_id, 'show_banner', 'on');
    } else {
        delete_post_meta($post_id, 'show_banner');
    }
}
add_action('save_post', 'save_page_banner_meta_box');




// services meta box
function add_page_service_meta_box() {
    add_meta_box(
        'page_service_meta_box',
        'Page service',
        'render_page_service_meta_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_page_service_meta_box');

// Render meta box content
function render_page_service_meta_box($post) {
    $show_service = get_post_meta($post->ID, 'show_service', true);
    ?>
    <label for="show_service">
        <input type="checkbox" id="show_service" name="show_service" <?php checked($show_service, 'on'); ?>>
        Show service Section
    </label>
    <?php
}

// Save meta box data
function save_page_service_meta_box($post_id) {
    if (isset($_POST['show_service'])) {
        update_post_meta($post_id, 'show_service', 'on');
    } else {
        delete_post_meta($post_id, 'show_service');
    }
}
add_action('save_post', 'save_page_service_meta_box');





// hide and show client section
// Add meta box for page settings
function add_page_client_meta_box() {
    add_meta_box(
        'page_client_meta_box',
        'Page client',
        'render_page_client_meta_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_page_client_meta_box');

// Render meta box content
function render_page_client_meta_box($post) {
    $show_client = get_post_meta($post->ID, 'show_client', true);
    ?>
    <label for="show_client">
        <input type="checkbox" id="show_client" name="show_client" <?php checked($show_client, 'on'); ?>>
        Show client Section
    </label>
    <?php
}

// Save meta box data
function save_page_client_meta_box($post_id) {
    if (isset($_POST['show_client'])) {
        update_post_meta($post_id, 'show_client', 'on');
    } else {
        delete_post_meta($post_id, 'show_client');
    }
}
add_action('save_post', 'save_page_client_meta_box');

function add_page_testimonials_meta_box() {
    add_meta_box(
        'page_testimonials_meta_box',    // Meta box ID
        'Page Testimonials',            // Meta box title
        'render_page_testimonials_meta_box', // Callback function to render the meta box content
        'page',                         // Post type where the meta box should appear (in this case, 'page')
        'side',                         // Context (e.g., 'normal', 'side', 'advanced')
        'high'                          // Priority (e.g., 'high', 'default', 'low')
    );
}
add_action('add_meta_boxes', 'add_page_testimonials_meta_box');
// Render meta box content
function render_page_testimonials_meta_box($post) {
    $show_testimonials = get_post_meta($post->ID, 'show_testimonials', true);
    ?>
    <label for="show_testimonials">
        <input type="checkbox" id="show_testimonials" name="show_testimonials" <?php checked($show_testimonials, 'on'); ?>>
        Show Testimonials Section
    </label>
    <?php
}
// Save meta box data
function save_page_testimonials_meta_box($post_id) {
    if (isset($_POST['show_testimonials'])) {
        update_post_meta($post_id, 'show_testimonials', 'on');
    } else {
        delete_post_meta($post_id, 'show_testimonials');
    }
}
add_action('save_post', 'save_page_testimonials_meta_box');












function ensure_jquery_loaded() {
    if (!wp_script_is('jquery')) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'ensure_jquery_loaded');



class Menu_With_Description extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        
        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        if (!empty($item->description)) {
            $item_output .= '<br>'; // Add line break
            $item_output .= '<a' . $attributes . ' class="sub" style="color: dimgray; display: block; text-transform: lowercase; font-family: \'Fontin-Italic\'; margin-top: 11px;width: 100px;">' . $item->description . '</a>'; // Description with same href and applied CSS
        }
        

        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}




// Step 1: Register Widget Areas
function pixel6_register_widget_areas() {
    // Register widget area for Pixel6 Jobs section
    register_sidebar( array(
        'name'          => __( 'Pixel6 Jobs Widget Area', 'pixel' ),
        'id'            => 'pixel6-jobs-widget-area',
        'description'   => __( 'Widgets for Pixel6 Jobs section in the footer.', 'pixel' ),
        'before_widget' => '<div class="">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="">',
        'after_title'   => '</h3>',
    ) );

    // Register widget area for Pixel6 Social section
    register_sidebar( array(
        'name'          => __( 'Pixel6 Social Widget Area', 'pixel' ),
        'id'            => 'pixel6-social-widget-area',
        'description'   => __( 'Widgets for Pixel6 Social section in the footer.', 'pixel' ),
        'before_widget' => '<div class="">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="">',
        'after_title'   => '</h3>',
    ) );
// Register widget area for Pixel6 gmail section
    register_sidebar( array(
        'name'          => __( 'Pixel6 gmail Widget Area', 'pixel' ),
        'id'            => 'pixel6-gmail-widget-area',
        'description'   => __( 'Widgets for Pixel6 gmail section in the footer.', 'pixel' ),
        'before_widget' => '<div class="">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="">',
        'after_title'   => '</h3>',
    ) );

  
}
add_action( 'widgets_init', 'pixel6_register_widget_areas' );







function my_custom_sidebars() {
    register_sidebar(array(
        'name' => __('Right Sidebar', 'your-theme'),
        'id' => 'right-sidebar',
        'description' => __('Widgets in this area will be shown on the right-hand side.', 'your-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'my_custom_sidebars');



// custom post type clients 
function create_client_post_type() {
    $labels = array(
        'name'                  => _x('Clients', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Client', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Clients', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Client', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Client', 'textdomain'),
        'new_item'              => __('New Client', 'textdomain'),
        'edit_item'             => __('Edit Client', 'textdomain'),
        'view_item'             => __('View Client', 'textdomain'),
        'all_items'             => __('All Clients', 'textdomain'),
        'search_items'          => __('Search Clients', 'textdomain'),
        'parent_item_colon'     => __('Parent Clients:', 'textdomain'),
        'not_found'             => __('No clients found.', 'textdomain'),
        'not_found_in_trash'    => __('No clients found in Trash.', 'textdomain'),
        'featured_image'        => _x('Client feature Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'archives'              => _x('Client archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
        'insert_into_item'      => _x('Insert into client', 'Overrides the “Insert into post” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
        'uploaded_to_this_item' => _x('Uploaded to this client', 'Overrides the “Uploaded to this post” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
        'filter_items_list'     => _x('Filter clients list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/“Filter pages list”. Added in 4.4', 'textdomain'),
        'items_list_navigation' => _x('Clients list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/“Pages list navigation”. Added in 4.4', 'textdomain'),
        'items_list'            => _x('Clients list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/“Pages list”. Added in 4.4', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'client'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-businessman', // Here is the menu icon
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
    );

    register_post_type('client', $args);
}

add_action('init', 'create_client_post_type');

add_theme_support('post-thumbnails');




// testimonials post type
function create_testimonial_post_type() {
    $labels = array(
        'name'                  => _x('Testimonials', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Testimonial', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Testimonials', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Testimonial', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Testimonial', 'textdomain'),
        'new_item'              => __('New Testimonial', 'textdomain'),
        'edit_item'             => __('Edit Testimonial', 'textdomain'),
        'view_item'             => __('View Testimonial', 'textdomain'),
        'all_items'             => __('All Testimonials', 'textdomain'),
        'search_items'          => __('Search Testimonials', 'textdomain'),
        'parent_item_colon'     => __('Parent Testimonials:', 'textdomain'),
        'not_found'             => __('No testimonials found.', 'textdomain'),
        'not_found_in_trash'    => __('No testimonials found in Trash.', 'textdomain'),
        'featured_image'        => _x('Testimonial Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'archives'              => _x('Testimonial archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
        'insert_into_item'      => _x('Insert into testimonial', 'Overrides the “Insert into post” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
        'uploaded_to_this_item' => _x('Uploaded to this testimonial', 'Overrides the “Uploaded to this post” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
        'filter_items_list'     => _x('Filter testimonials list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/“Filter pages list”. Added in 4.4', 'textdomain'),
        'items_list_navigation' => _x('Testimonials list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/“Pages list navigation”. Added in 4.4', 'textdomain'),
        'items_list'            => _x('Testimonials list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/“Pages list”. Added in 4.4', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonial'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-testimonial', // Dashicon for testimonials
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
    );

    register_post_type('testimonial', $args);
}

add_action('init', 'create_testimonial_post_type');





// i crete a custom feild for heading and paragrapgh which is shown in side bar
// Add meta box
// Add meta box
function add_page_heading_para_meta_box() {
    add_meta_box(
        'page_heading_para_meta_box',    // Meta box ID
        'Page Heading and Paragraph',   // Meta box title
        'render_page_heading_para_meta_box', // Callback function to render the meta box content
        'page',                         // Post type where the meta box should appear
        'normal',                       // Context: 'normal', 'side', 'advanced'
        'high'                          // Priority: 'high', 'default', 'low'
    );
}
add_action('add_meta_boxes', 'add_page_heading_para_meta_box');

// Render meta box content
function render_page_heading_para_meta_box($post) {
    // Retrieve existing meta values
    $heading = get_post_meta($post->ID, 'heading', true);
    $content = get_post_meta($post->ID, 'heading_para_content', true);

    // Set default values if meta values are empty
    $default_heading = 'Sidebar Section';
    $default_content = 'Default paragraph content. Edit me!';

    if (empty($heading)) {
        $heading = $default_heading;
    }

    if (empty($content)) {
        $content = $default_content;
    }

    // Nonce field to validate form request
    wp_nonce_field('save_page_heading_para_meta_box', 'page_heading_para_meta_box_nonce');

    // Output the fields
    ?>
    <label for="heading">Heading:</label><br>
    <input type="text" id="heading" name="heading" value="<?php echo esc_attr($heading); ?>" style="width: 100%;"><br>
    
    <label for="heading_para_content">Paragraph:</label><br>
    <?php
    $editor_id = 'heading_para_content'; // ID for the textarea
    $settings = array(
        'textarea_name' => 'heading_para_content',
        'media_buttons' => false,
        'teeny' => true,
        'textarea_rows' => 10, // Adjust height as needed
    );
    wp_editor($content, $editor_id, $settings);
}


// Save meta box data
function save_page_heading_para_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['page_heading_para_meta_box_nonce']) || !wp_verify_nonce($_POST['page_heading_para_meta_box_nonce'], 'save_page_heading_para_meta_box')) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save/update meta values
    if (isset($_POST['heading'])) {
        update_post_meta($post_id, 'heading', sanitize_text_field($_POST['heading']));
    } else {
        delete_post_meta($post_id, 'heading');
    }

    if (isset($_POST['heading_para_content'])) {
        update_post_meta($post_id, 'heading_para_content', wp_kses_post($_POST['heading_para_content']));
    } else {
        delete_post_meta($post_id, 'heading_para_content');
    }
}
add_action('save_post', 'save_page_heading_para_meta_box');




// In your theme's functions.php file
if (!function_exists('p6_theme_post_thumbnail')) {
    function p6_theme_post_thumbnail() {
        if (has_post_thumbnail()) {
            the_post_thumbnail();
        }
    }
}

// repeater feild for front page banner section title 
function my_custom_meta_boxes() {
    add_meta_box(
        'repeater_meta_box', // ID
        'Repeater Field', // Title
        'render_repeater_meta_box', // Callback
        'page', // Screen (you can change this to a specific post type)
        'normal', // Context
        'high' // Priority
    );
}
add_action('add_meta_boxes', 'my_custom_meta_boxes');
function render_repeater_meta_box($post) {
    wp_nonce_field('save_repeater_meta_box', 'repeater_meta_box_nonce');
    $repeater_fields = get_post_meta($post->ID, 'repeater_fields', true);
    ?>
    <div id="repeater-fields-container">
        <?php
        if (!empty($repeater_fields)) {
            foreach ($repeater_fields as $field) {
                ?>
                <div class="repeater-field">
                    <input type="text" name="repeater_fields[]" value="<?php echo esc_attr($field); ?>" />
                    <button type="button" class="remove-repeater-field button">Remove</button>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="repeater-field">
                <input type="text" name="repeater_fields[]" value="" />
                <button type="button" class="remove-repeater-field button">Remove</button>
            </div>
            <?php
        }
        ?>
    </div>
    <button type="button" id="add-repeater-field" class="button">Add Field</button>
    <script>
        jQuery(document).ready(function($) {
            $('#add-repeater-field').click(function() {
                var newField = '<div class="repeater-field"><input type="text" name="repeater_fields[]" value="" /><button type="button" class="remove-repeater-field button">Remove</button></div>';
                $('#repeater-fields-container').append(newField);
            });

            $('#repeater-fields-container').on('click', '.remove-repeater-field', function() {
                $(this).parent('.repeater-field').remove();
            });
        });
    </script>
    <?php
}
function save_repeater_meta_box($post_id) {
    if (!isset($_POST['repeater_meta_box_nonce']) || !wp_verify_nonce($_POST['repeater_meta_box_nonce'], 'save_repeater_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['repeater_fields'])) {
        $repeater_fields = array_map('sanitize_text_field', $_POST['repeater_fields']);
        update_post_meta($post_id, 'repeater_fields', $repeater_fields);
    } else {
        delete_post_meta($post_id, 'repeater_fields');
    }
}
add_action('save_post', 'save_repeater_meta_box');


