<?php
   /**
    * The template for displaying all pages
    *
    * This is the template that displays all pages by default.
    * Please note that this is the WordPress construct of pages
    * and that other 'pages' on your WordPress site may use a
    * different template.
    *
    * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
    *
    * @package p6-Theme
    */
   
   get_header();
   
   ?>

<main id="primary" class="site-main">
   <?php   
      global $post;
      $pageName = $post->post_name;
      if (locate_template( array( 'template-parts/content-' . $pageName . '.php' ) ) != '') {
          // yep, load the page template
          get_template_part('template-parts/content', $pageName);
      } else {
          // nope, load the default
          get_template_part( 'template-parts/content', 'page' );
      }
      ?>
</main>
<!-- #main -->
</div>
