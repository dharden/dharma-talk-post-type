<?php
/**
 * The template for displaying posts in the Dharma Talk post format
 *
 * @package BZC
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

?>

<?php
// set up some custom fields and other useful strings.
$author         = get_the_author();
$author_id      = get_the_author_meta( 'ID' );
$date           = esc_attr( get_the_date( 'c' ) );
$pretty_date    = esc_html( get_the_date() );
$youtube_link   = esc_attr( get_post_meta( get_the_ID(), 'dt_youtube_link', true ) );
$podcast_player = get_the_powerpress_content();
$format_class   = array();
if ( $youtube_link ) {
	array_push( $format_class, 'format-video' );
};
if ( $podcast_player ) {
	array_push( $format_class, 'format-audio' );
};
$bio         = nl2br( get_the_author_meta( 'description' ) );
$the_content = get_the_content(
	sprintf(
					/* translators: %s: Post title. */
		__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ),
		the_title( '<span class="screen-reader-text">', '</span>', false )
	)
);

			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				)
			);
			$default_description = '<p>A talk given at Berkeley Zen Center on ' . $pretty_date . ' by ' . $author . '.</p>';
			$talk_description    = $the_content ? $the_content : $default_description;
			?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $format_class ); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<a class="cat-links" href="<?php echo esc_url( get_post_type_archive_link( 'dharma-talk' ) ); ?>">Dharma Talks</a>
		</div><!-- .entry-meta -->
			<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
					endif;
					?>

				<div class="entry-meta">
					<?php
					if ( is_sticky() && is_home() && ! is_paged() ) {
						echo '<span class="featured-post">' . esc_html( __( 'Sticky', 'twentyfourteen' ) ) . '</span>';
					}

					// Set up and print post meta information.
					?>
				<?php
				printf(
					'<span class="byline"><span class="author vcard"><a class="url fn n" href="%1$s" rel="author">%2$s</a></span></span><span class="entry-date"><a href="%3$s" rel="bookmark"><time class="entry-date" datetime="%4$s">%5$s</time></a></span>',
					esc_url( get_author_posts_url( $author_id ) ),
					esc_html( $author ),
					esc_url( get_permalink() ),
					esc_html( $date ),
					esc_html( $pretty_date )
				);
				?>
			<?php if ( $youtube_link ) { ?>
			<span class="post-format video">
				<i class="fa"></i><?php echo esc_html( get_post_format_string( 'video' ) ); ?>
			</span>
			<?php }; ?>
			<?php if ( $podcast_player ) { ?>
			<span class="post-format audio">
				<i class="fa"></i><?php echo esc_html( get_post_format_string( 'audio' ) ); ?>
			</span>
			<?php }; ?>
			<?php edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php if ( $youtube_link && ! is_archive() ) { ?>
			<?php echo do_shortcode( '[youtube ' . $youtube_link . ']' ); ?>
		<?php }; ?>
		<?php
		if ( $talk_description ) {
			echo wp_kses(
				$talk_description,
				array(
					'a'      => array(
						'href'  => array(),
						'title' => array(),
					),
					'p'      => array(),
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
				)
			);
		}
		?>
		<?php if ( $podcast_player && ! is_archive() ) { ?>
		<div class="podcast">
			<h2>Audio</h2>
			<p>Listen to an audio-only version of this talk here: </p>
			<?php $podcast_player; ?>
			<?php
			if ( $podcast_player ) {
				echo $podcast_player;
			}
			?>
			<p>Subscribe to The Berkeley Zen Center Podcast: <a href="https://berkeleyzencenter.org/feed/podcast/" title="Subscribe via RSS" rel="nofollow">RSS</a></p>
		</div>
		<?php } ?>
	</div><!-- .entry-content -->
	<?php if ( $bio && ! is_archive() ) { ?>
	<div class="about">
		<?php if ( has_wp_user_avatar( $author_id ) ) { ?>
		<img class="avatar circle" src="<?php echo esc_attr( get_wp_user_avatar_src( $author_id, 128 ) ); ?>" width="128" height="128" alt="<?php echo esc_attr( $author ); ?>" />
		<?php } ?>
		<div class="bio">
			<h3>About <?php echo esc_html( $author ); ?></h3>
			<p><?php echo esc_html( $bio ); ?></p>
			<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">More from <?php echo esc_html( $author ); ?></a>
		</div>
	</div><!-- .entry-meta -->
	<?php } ?>

</article><!-- #post-<?php the_ID(); ?> -->
