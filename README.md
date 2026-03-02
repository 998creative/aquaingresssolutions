# Aqua Ingress Solutions Theme

Custom WordPress theme (no Elementor dependency) for:

- Home (`front-page.php`)
- About (`page-about.php`)
- Contact (`page-contact.php`)
- Strata & Building Manager Support (`page-strata-building-manager-support.php`)
- Case studies as native blog posts (`home.php` + `single.php`)

## Install

1. Upload this theme folder to `wp-content/themes/aquaingresssolutions`.
2. In WordPress admin: `Appearance -> Themes -> Activate` **Aqua Ingress Solutions**.
3. In `Settings -> Reading`:
   - `Your homepage displays`: `A static page`
   - `Homepage`: choose your `Home` page
   - `Posts page`: choose/create `Case Studies` page
4. Ensure page slugs match:
   - `about`
   - `contact`
   - `strata-building-manager-support`
5. Go to `Settings -> Permalinks` and click **Save Changes**.

## Case Studies (Posts)

- Case studies are standard `Posts`.
- Featured image is used in sliders/cards.
- Optional location field meta key:
  - `ais_case_study_location`
- If location meta is empty, the post excerpt is used as fallback.

## Remove Elementor Completely

After this theme is active and pages are verified:

1. `Plugins -> Installed Plugins`
2. Deactivate:
   - `Elementor`
   - `Elementor Pro` (if installed)
3. Delete both plugins.
4. Optional cleanup:
   - Remove unused Elementor templates from `Templates` post type.
   - Clear caches/CDN.

## GitHub Push-To-Live Updater Plugin

A companion plugin is included here:

- `plugin/aqua-theme-github-updater/aqua-theme-github-updater.php`

Install it on the live site to let WordPress pull theme updates directly from this GitHub repo.

## Notes

- Header/footer/navigation are built directly in theme templates.
- No Elementor widgets, templates, or theme builder are required.
