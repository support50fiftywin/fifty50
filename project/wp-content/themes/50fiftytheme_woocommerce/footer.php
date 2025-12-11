<footer id="footer" class="bg-brand-dark text-white py-16">
    <div class="container mx-auto px-4">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">

            <!-- Logo & Description -->
            <div>
                <div class="flex items-center gap-2 mb-4">

                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <div class="w-10 h-10 bg-white text-black rounded-full flex items-center justify-center font-bold text-lg">
                            50
                        </div>
                    <?php endif; ?>

                    <span class="text-2xl font-bold font-display">
                        <?php bloginfo('name'); ?>
                    </span>
                </div>

                <p class="text-gray-400 text-sm">
                    <?php bloginfo('description'); ?>
                </p>
            </div>

            <!-- QUICK LINKS MENU -->
            <div>
                <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <?php
                        wp_nav_menu([
                            'theme_location' => 'footer_quick_links',
                            'container' => false,
                            'items_wrap' => '%3$s',
                            'fallback_cb' => false,
                        ]);
                    ?>
                </ul>
            </div>

            <!-- PARTNERS MENU -->
            <div>
                <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">For Partners</h4>
                <ul class="space-y-2 text-sm">
                    <?php
                        wp_nav_menu([
                            'theme_location' => 'footer_partners',
                            'container' => false,
                            'items_wrap' => '%3$s',
                            'fallback_cb' => false,
                        ]);
                    ?>
                </ul>
            </div>

            <!-- SUPPORT MENU -->
            <div>
                <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">Support</h4>
                <ul class="space-y-2 text-sm">
                    <?php
                        wp_nav_menu([
                            'theme_location' => 'footer_support',
                            'container' => false,
                            'items_wrap' => '%3$s',
                            'fallback_cb' => false,
                        ]);
                    ?>
                </ul>
            </div>

        </div>

        <!-- COPYRIGHT + SOCIALS -->
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-sm">
                <?php echo esc_html(get_theme_mod('fifty_footer_copyright')); ?>
            </p>

            <div class="flex gap-4">
                <!-- Facebook -->
                <a href="<?php echo get_theme_mod('fifty_social_facebook'); ?>"
                   class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-accent transition-colors">
                    <i class="fab fa-facebook-f"></i>
                </a>

                <!-- Twitter -->
                <a href="<?php echo get_theme_mod('fifty_social_twitter'); ?>"
                   class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-accent transition-colors">
                    <i class="fab fa-twitter"></i>
                </a>

                <!-- Instagram -->
                <a href="<?php echo get_theme_mod('fifty_social_instagram'); ?>"
                   class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-accent transition-colors">
                    <i class="fab fa-instagram"></i>
                </a>

                <!-- YouTube -->
                <a href="<?php echo get_theme_mod('fifty_social_youtube'); ?>"
                   class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-accent transition-colors">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>
