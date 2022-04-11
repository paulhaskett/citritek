<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * 
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php
		the_title(
			sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
			'</a></h2>'
		);
		?>

		<?php if ( 'case_study' === get_post_type() ) : ?>

			

		<?php endif; ?>

	</header><!-- .entry-header -->

    <a href="<?php echo get_post_permalink(); ?>">

        <?php if(is_archive()){echo get_the_post_thumbnail( $post->ID, 'small ' ); }?>
    </a>
    
	<div class="entry-content">

		<?php
		the_excerpt();
		understrap_link_pages();
		?>

	</div><!-- .entry-content -->
    
	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
