<?php
/**
 * 404 template.
 *
 * @package AquaIngressSolutions
 */

get_header();
?>
<main id="main-content">
  <section class="section">
    <div class="container">
      <div class="section-head">
        <h1>Page Not Found</h1>
        <p>The page you're looking for could not be found.</p>
      </div>
      <a class="btn btn-primary" href="<?php echo esc_url(ais_home_section_url('home')); ?>">Back Home</a>
    </div>
  </section>
</main>
<?php
get_footer();
