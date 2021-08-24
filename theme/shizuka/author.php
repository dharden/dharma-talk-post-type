<?php
/**
 * The template for displaying Author archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area author">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header">
				<h1 class="archive-title">
					<?php
						/*
						 * Queue the first post, that way we know what author
						 * we're dealing with (if that is the case).
						 *
						 * We reset this later so we can run the loop properly
						 * with a call to rewind_posts().
						 */
						the_post();

						/* translators: %s: Author display name. */
						printf( __( 'All posts by %s', 'twentyfourteen' ), get_the_author() );
					?>
				</h1>
				<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<div class="author-description"><?php the_author_meta( 'description' ); ?></div>
				<?php endif; ?>
			</header><!-- .archive-header -->

				<?php
					/*
					 * Since we called the_post() above, we need
					 * to rewind the loop back to the beginning.
					 * That way we can run the loop properly, in full.
					 */
					rewind_posts();

				// Start the Loop.
				while ( have_posts() ) :
					the_post();

					if ( get_post_type( get_the_ID() ) === 'dharma-talk' ) {
						get_template_part( 'content', 'dharma-talk' );
					} else {
						get_template_part( 'content', get_post_format() );
					}

					endwhile;
					// Previous/next page navigation.
					twentyfourteen_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
				?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
