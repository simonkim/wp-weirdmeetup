<div id="sidebar">
<?php if (of_get_option('magazine_activate_ltposts' ) =='1' ) {load_template(get_template_directory() . '/includes/ltposts.php'); } ?>


		<?php if (!dynamic_sidebar('Sidebar Right') ) : ?>		
		
		<?php endif; ?>	</div>	<!-- end div #sidebar -->

		