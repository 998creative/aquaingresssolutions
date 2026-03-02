<?php
/**
 * Template Name: Contact Page
 *
 * @package AquaIngressSolutions
 */

get_header();
?>
  <main id="main-content">
    <section class="hero contact-hero" id="contact-home">
      <div class="hero-overlay"></div>
      <div class="container">
        <div class="hero-content">
          <h1>Get in touch</h1>
          <p class="lead">Reach out to book a leak investigation, get a quote, or discuss your waterproofing requirements.</p>
        </div>
      </div>
    </section>

    <section class="section contact-main" id="contact-form">
      <div class="container contact-layout">
        <div class="contact-faq faq">
          <div class="section-head">
            <h2>Frequently Asked Questions</h2>
            <p>We're proud to be the trusted choice for strata managers, engineers, and builders who rely on us for dependable leak repair and waterproofing results.</p>
          </div>

          <div class="faq-list">
            <details open>
              <summary>What's the difference between positive and negative waterproofing?</summary>
              <p><strong>Positive waterproofing</strong> is applied to the water-exposed surface, stopping moisture before it enters the structure.</p>
              <p><strong>Negative waterproofing</strong> is applied internally where external access is limited, often in basements, lift pits, and plant rooms.</p>
              <p>We assess each building and can specify a combined strategy where needed.</p>
            </details>

            <details>
              <summary>How long does a leak investigation take?</summary>
              <p>Most investigations are completed within <strong>1-2 days</strong>, depending on building size and issue complexity.</p>
              <p>We use targeted methods like flood testing, hose testing, and moisture mapping to find root causes quickly.</p>
            </details>

            <details>
              <summary>Will residents need to vacate the building?</summary>
              <p>In most cases, <strong>no</strong>.</p>
              <p>Works are planned to minimise disruption and can usually be completed while residents remain in place.</p>
            </details>

            <details>
              <summary>Do you provide documentation for warranty and compliance?</summary>
              <p>Yes. We provide complete closeout documentation including compliance notes, photos, product sheets, and warranty records.</p>
              <ul>
                <li>Australian Standards compliance</li>
                <li>Photographic records of works</li>
                <li>Product data sheets and warranties</li>
                <li>Certification where required</li>
              </ul>
            </details>

            <details>
              <summary>What's included in your maintenance plans?</summary>
              <p>Each plan is tailored to the building and typically includes:</p>
              <ul>
                <li>Scheduled waterproofing inspections</li>
                <li>Early defect and membrane wear identification</li>
                <li>Minor preventative repairs</li>
                <li>Detailed condition reports and recommendations</li>
              </ul>
            </details>
          </div>
        </div>

        <aside class="contact-panel" aria-label="Contact details and enquiry form">
          <h2>Contact Us</h2>

          <ul class="contact-methods">
            <li>
              <span class="contact-icon" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                  <path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/>
                </svg>
              </span>
              <a href="mailto:info@aquaingresssolutions.com">info@aquaingresssolutions.com</a>
            </li>
            <li>
              <span class="contact-icon" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                  <path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"/>
                </svg>
              </span>
              <a href="tel:+12345678910">+12345678910</a>
            </li>
          </ul>

          <p>We're proud to be the trusted choice for strata managers, engineers, and builders who rely on us for dependable leak repair and waterproofing results.</p>

          <form class="contact-form" action="#" method="post">
            <div class="form-field">
              <label for="name">Name <span aria-hidden="true">*</span></label>
              <input id="name" name="name" type="text" placeholder="Your full name" autocomplete="name" required>
            </div>

            <div class="form-grid-two">
              <div class="form-field">
                <label for="email">Email <span aria-hidden="true">*</span></label>
                <input id="email" name="email" type="email" placeholder="you@email.com" autocomplete="email" required>
              </div>

              <div class="form-field">
                <label for="phone">Phone <span aria-hidden="true">*</span></label>
                <input id="phone" name="phone" type="tel" placeholder="+61..." autocomplete="tel" inputmode="tel" required>
              </div>
            </div>

            <div class="form-field">
              <label for="enquiry-type">Type of Enquiry <span aria-hidden="true">*</span></label>
              <select id="enquiry-type" name="enquiry_type" required>
                <option value="" selected disabled>Select enquiry type</option>
                <option value="Leak Investigation">Leak Investigation</option>
                <option value="Positive Waterproofing">Positive Waterproofing</option>
                <option value="Negative Waterproofing">Negative Waterproofing</option>
                <option value="Torch-on Membrane">Torch-on Membrane</option>
                <option value="Injection Waterproofing">Injection Waterproofing</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="form-field">
              <label for="message">Message</label>
              <textarea id="message" name="message" rows="5" placeholder="Tell us about the leak location, access constraints, and urgency."></textarea>
            </div>

            <p class="form-hint">We typically respond within 1 business day.</p>
            <button class="btn btn-primary" type="submit">Send Enquiry</button>
          </form>
        </aside>
      </div>
    </section>

    <section class="cta-banner contact-page-cta">
      <div class="cta-overlay"></div>
      <div class="container cta-content">
        <h2>Need a fast site attendance for active leaks?</h2>
        <p>Let our specialist team trace the source and provide a long-term solution.</p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="#contact-form">Book A Site Inspection</a>
          <a class="btn btn-light" href="#contact-form">Contact Us</a>
        </div>
      </div>
    </section>
  </main>
<?php
get_footer();
?>
