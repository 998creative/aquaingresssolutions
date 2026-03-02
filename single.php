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
    <?php
    $hero_image = has_post_thumbnail()
        ? wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')
        : get_theme_file_uri('assets/images/waterproofing-7508362-scaled.jpg');
    ?>
    <section class="hero case-study-hero" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
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
