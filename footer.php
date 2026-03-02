<?php
/**
 * Site footer.
 *
 * @package AquaIngressSolutions
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <img class="footer-logo" src="<?php echo esc_url(get_theme_file_uri('assets/images/Aqua-Logo-Dark-1024x265.webp')); ?>" alt="Aqua Ingress Solutions dark logo" width="220" height="57">
      <p>Experts in remedial waterproofing for Queensland's high-rise and strata sectors, delivering compliant, lasting solutions backed by certification and field experience.</p>
    </div>

    <details class="footer-section" data-footer-section open>
      <summary class="footer-summary">Navigate</summary>
      <div class="footer-section-content">
        <ul class="footer-nav">
          <li><a href="<?php echo esc_url(ais_home_section_url('home')); ?>">Home</a></li>
          <li><a href="<?php echo esc_url(ais_about_url()); ?>">About Us</a></li>
          <li><a href="<?php echo esc_url(ais_home_section_url('services')); ?>">Services</a></li>
          <li><a href="<?php echo esc_url(ais_case_studies_url()); ?>">Case Studies</a></li>
        </ul>
      </div>
    </details>

    <details class="footer-section" data-footer-section open>
      <summary class="footer-summary">Contact Us</summary>
      <div class="footer-section-content">
        <ul class="footer-contact">
          <li>
            <span class="contact-icon" aria-hidden="true">
              <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                <path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/>
              </svg>
            </span>
            <span class="contact-text"><a href="mailto:info@aquaingresssolutions.com">info@aquaingresssolutions.com</a></span>
          </li>
          <li>
            <span class="contact-icon" aria-hidden="true">
              <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                <path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"/>
              </svg>
            </span>
            <span class="contact-text"><a href="tel:+12345678910">+12345678910</a></span>
          </li>
          <li>
            <span class="contact-icon" aria-hidden="true">
              <svg viewBox="0 0 288 512" xmlns="http://www.w3.org/2000/svg">
                <path d="M112 316.94v156.69l22.02 33.02c4.75 7.12 15.22 7.12 19.97 0L176 473.63V316.94c-10.39 1.92-21.06 3.06-32 3.06s-21.61-1.14-32-3.06zM144 0C64.47 0 0 64.47 0 144s64.47 144 144 144 144-64.47 144-144S223.53 0 144 0zm0 76c-37.5 0-68 30.5-68 68 0 6.62-5.38 12-12 12s-12-5.38-12-12c0-50.73 41.28-92 92-92 6.62 0 12 5.38 12 12s-5.38 12-12 12z"/>
              </svg>
            </span>
            <span class="contact-text">Address Line 1<br>Address Line 2<br>Address Line 2</span>
          </li>
        </ul>
      </div>
    </details>

    <details class="footer-section footer-cta-section" data-footer-section open>
      <summary class="footer-summary">Need A Quote?</summary>
      <div class="footer-section-content">
        <p>Ready for a structured leak investigation and remediation plan?</p>
        <a class="btn btn-primary footer-primary-cta" href="<?php echo esc_url(ais_contact_url('contact-form')); ?>">Book A Consultation</a>
        <h3 class="social-heading">Follow Us</h3>
        <ul class="social-links">
          <li>
            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
              <svg viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06H297V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
              </svg>
            </a>
          </li>
          <li>
            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
              <svg viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.3 0-74.8-33.5-74.8-74.8S182.8 181 224.1 181s74.8 33.5 74.8 74.8-33.5 74.8-74.8 74.8zm146.4-194.3c0 14.9-12 26.9-26.9 26.9-14.9 0-26.9-12-26.9-26.9 0-14.9 12-26.9 26.9-26.9 14.9 0 26.9 12 26.9 26.9zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9S352.6 7 316.7 5.3C280.6 3.3 167.4 3.3 131.3 5.3 95.4 7 63.6 15.2 37.4 41.4S7 95.2 5.3 131.1c-2 36.1-2 149.3 0 185.4 1.7 35.9 9.9 67.7 36.2 93.9s58 34.5 93.9 36.2c36.1 2 149.3 2 185.4 0 35.9-1.7 67.7-9.9 93.9-36.2s34.5-58 36.2-93.9c2-36.1 2-149.2 0-185.3zM398.8 388c-7.8 19.6-22.9 34.7-42.5 42.5-29.4 11.7-99.2 9-132.2 9s-102.9 2.6-132.2-9c-19.6-7.8-34.7-22.9-42.5-42.5-11.7-29.4-9-99.2-9-132.2s-2.6-102.9 9-132.2c7.8-19.6 22.9-34.7 42.5-42.5 29.4-11.7 99.2-9 132.2-9s102.9-2.6 132.2 9c19.6 7.8 34.7 22.9 42.5 42.5 11.7 29.4 9 99.2 9 132.2s2.7 102.9-9 132.2z"/>
              </svg>
            </a>
          </li>
          <li>
            <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
              <svg viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 01107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/>
              </svg>
            </a>
          </li>
        </ul>
      </div>
    </details>
  </div>
  <div class="footer-bottom">
    <p>&copy; <span id="year"><?php echo esc_html((string) gmdate('Y')); ?></span> Aqua Ingress Solutions. All rights reserved.</p>
  </div>
</footer>

<a class="mobile-fab" href="<?php echo esc_url(ais_contact_url('contact-form')); ?>">Book Consultation</a>

<?php wp_footer(); ?>
</body>
</html>
