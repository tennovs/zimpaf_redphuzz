<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package
 */

get_header(); ?>

<div id="joomsport-container">
	<section class="joomsport-club clearfix">
		<?php if ( have_posts() ) : ?>
			<div class="jsClubAbout col-xs-12 page-header">
				<?php
				the_archive_title( '<h1>', '</h1>' );
				the_archive_description( '<div>', '</div>' );
				?>
			</div>
			<div class="jsClubTeams col-xs-12">
				<div class="row">
					<?php while ( have_posts() ) : the_post(); ?>
						<div id="post-<?php the_ID(); ?>" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<a href="<?php the_permalink(); ?>">
								<div class="jsTeam jstable">
									<?php if (has_post_thumbnail() ){ ?>
										<div class="jsTeamLogo jstable-cell">
											<?php the_post_thumbnail('thumbnail'); ?>
										</div>
										<?php 
									}
									?>
									<div class="jsTeamName jstable-cell">
										<div>
											<h2><?php the_title(); ?></h2>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
			<?php the_posts_pagination(); ?>
		<?php endif; ?>
	</section>
</div>

<?php get_footer(); ?>