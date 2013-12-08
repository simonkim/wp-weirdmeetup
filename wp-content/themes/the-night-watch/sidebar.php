<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package nightwatch
 * @since nightwatch 1.0.2
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			
			<div id="primary-sidebar">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="search" class="widget widget_search">
					<?php get_search_form(); ?>
				</aside>

				<aside id="archives" class="widget">
					<h1 class="widget-title"><?php _e( 'Archives', 'nightwatch' ); ?></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside  class="widget">
					<h1 class="widget-title"><?php _e( 'Meta', 'nightwatch' ); ?></h1>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
			</div><!-- #primary-sidebar -->

		</div><!-- #secondary .widget-area -->
