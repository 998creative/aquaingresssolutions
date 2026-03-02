<?php
/**
 * Single case study template.
 *
 * @package AquaIngressSolutions
 */

get_header();
?>
<main id="main-content">
  <?php while (have_posts()) : the_post(); ?>
    <section class="hero case-study-hero">
      <div class="hero-overlay"></div>
      <div class="container">
        <div class="hero-content">
          <p class="kicker">Case Study</p>
          <h1><?php the_title(); ?></h1>
          <p class="lead"><?php echo esc_html(ais_get_case_study_location(get_the_ID())); ?></p>
        </div>
      </div>
    </section>

    <section class="section case-study-content-wrap">
      <div class="container case-study-content">
        <?php if (has_post_thumbnail()) : ?>
          <figure class="case-study-featured">
            <?php the_post_thumbnail('large'); ?>
          </figure>
        <?php endif; ?>
        <article class="entry-content">
          <?php the_content(); ?>
        </article>
        <a class="btn btn-primary" href="<?php echo esc_url(ais_case_studies_url()); ?>">Back To Case Studies</a>
      </div>
    </section>
  <?php endwhile; ?>
</main>
<?php
get_footer();
