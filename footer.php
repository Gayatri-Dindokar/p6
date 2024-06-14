</div>
<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the #content div and all content after
*
* @package pixel
*/
?>
<style>
	
</style>
<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package pixel
 */
?>

<footer class="px_footer clearfix">
    <div class="px_footer_wrapper content_wrapper_width">
        <div class="px_view clearfix">
            <div class="px_3coloumn">
                <div class="px_view_section">
                    <?php if (is_active_sidebar('pixel6-jobs-widget-area')) : ?>
                        <?php dynamic_sidebar('pixel6-jobs-widget-area'); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="px_3coloumn">
                <div class="px_view_section">
                    <?php if (is_active_sidebar('pixel6-social-widget-area')) : ?>
                        <?php dynamic_sidebar('pixel6-social-widget-area'); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="px_3coloumn">
                <div class="px_view_section px_contact_section">
                    <?php if (is_active_sidebar('pixel6-gmail-widget-area')) : ?>
                        <?php dynamic_sidebar('pixel6-gmail-widget-area'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
