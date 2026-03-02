<?php
/**
 * Blog archive template for case studies (posts).
 *
 * @package AquaIngressSolutions
 */

get_header();
?>
<main id="main-content">
  <section class="section studies studies-archive">
    <div class="container">
      <div class="section-head">
        <h1>Case Studies</h1>
        <p>Proven leak remediation outcomes across Queensland's toughest high-rise and remedial projects.</p>
      </div>

      <?php if (have_posts()) : ?>
        <div class="study-list-grid">
          <?php while (have_posts()) : the_post(); ?>
            <article class="study-card study-card-archive">
              <div class="study-media">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                <?php else : ?>
                  <img src="<?php echo esc_url(get_theme_file_uri('assets/images/PHOTO-2025-12-05-05-43-13.jpg')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                <?php endif; ?>
              </div>
              <div class="study-panel">
                <h2><?php the_title(); ?></h2>
                <p><?php echo esc_html(ais_get_case_study_location(get_the_ID())); ?></p>
                <a class="study-link" href="<?php the_permalink(); ?>">View Case Study</a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="archive-pagination">
          <?php the_posts_pagination(); ?>
        </div>
      <?php else : ?>
        <p>No case studies published yet.</p>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
get_footer();
