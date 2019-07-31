            </div>

            <div class="footer-menu-wrapper bg-default py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 mb-4 mb-md-0">
                            <?php
                            echo wp_nav_menu(array(
                                'theme_location' => 'footer-menu',
                                'container'      => 'false',
                                'menu_class'     => 'menu list-unstyled list-inline mt-1 mb-0',
                                'menu_id'        => 'footer-menu'
                            ));
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
            </div>

        </main>
		<?php echo ucfwp_get_footer_markup(); ?>
		<?php wp_footer(); ?>
	</body>
</html>