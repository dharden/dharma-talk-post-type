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
$author          = get_the_author();
$date            = esc_attr( get_the_date( 'c' ) );
$pretty_date     = esc_html( get_the_date() );
$youtube_link    = esc_attr( get_post_meta( get_the_ID(), 'dt_youtube_link', true ) );
$episode_content = get_the_powerpress_content();
$format_class    = array();
if ( $youtube_link ) {
	array_push( $format_class, 'format-video' );
};
if ( $episode_content ) {
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
						echo '<span class="featured-post">' . __( 'Sticky', 'twentyfourteen' ) . '</span>';
					}

					// Set up and print post meta information.
					?>
				<?php
				printf(
					'<span class="byline"><span class="author vcard"><a class="url fn n" href="%1$s" rel="author">%2$s</a></span></span><span class="entry-date"><a href="%3$s" rel="bookmark"><time class="entry-date" datetime="%4$s">%5$s</time></a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					$author,
					esc_url( get_permalink() ),
					$date,
					$pretty_date
				);
				?>
			<?php if ( $youtube_link ) { ?>
			<span class="post-format video">
				<i class="fa"></i><?php echo get_post_format_string( 'video' ); ?>
			</span>
			<?php }; ?>
			<?php if ( $episode_content ) { ?>
			<span class="post-format audio">
				<i class="fa"></i><?php echo get_post_format_string( 'audio' ); ?>
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
			echo $talk_description;}
		?>
		<?php if ( $episode_content && ! is_archive() ) { ?>
		<div class="podcast">
			<h2>Audio</h2>
			<p>Listen to an audio-only version of this talk here: </p>
			<?php echo $episode_content; ?>
			<p>Subscribe to The Berkeley Zen Center Podcast: <a href="https://berkeleyzencenter.org/feed/podcast/" title="Subscribe via RSS" rel="nofollow">RSS</a></p>
		</div>
		<?php } ?>
	</div><!-- .entry-content -->
	<?php if ( $bio && ! is_archive() ) { ?>
	<div class="about">
		<?php if ( has_wp_user_avatar( get_the_author_meta( 'ID' ) ) ) { ?>
		<img class="avatar circle" src="<?php echo get_wp_user_avatar_src( get_the_author_meta( 'ID' ), 128 ); ?>" width="128" height="128" alt="<?php echo $author; ?>" />
		<?php } ?>
		<div class="bio">
			<h3>About <?php echo $author; ?></h3>
			<p><?php echo $bio; ?></p>
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">More from <?php echo $author; ?></a>
		</div>
	</div><!-- .entry-meta -->
	<?php } ?>

</article><!-- #post-<?php the_ID(); ?> -->