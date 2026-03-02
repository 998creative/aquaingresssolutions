<?php
/**
 * Default page template.
 *
 * @package AquaIngressSolutions
 */

get_header();
?>
<main id="main-content">
  <section class="section">
    <div class="container">
      <?php while (have_posts()) : the_post(); ?>
        <article class="entry-content">
          <h1><?php the_title(); ?></h1>
          <?php the_content(); ?>
        </article>
      <?php endwhile; ?>
    </div>
  </section>
</main>
<?php
get_footer();
