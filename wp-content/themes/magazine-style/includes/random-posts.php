	<ul>
		<?php
		$rand_posts = get_posts('numberposts=5&orderby=rand');
		foreach( $rand_posts as $post ) :
		?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endforeach; ?>
	</ul>