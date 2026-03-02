# Aqua Theme GitHub Updater Plugin

This plugin makes WordPress update the `aquaingresssolutions` theme directly from:

- `https://github.com/998creative/aquaingresssolutions`
- branch: `main`

It also enables auto-updates for this theme.

## Install

1. Zip the folder:
   - `plugin/aqua-theme-github-updater`
2. In WordPress admin:
   - `Plugins -> Add New -> Upload Plugin`
   - Upload and activate the zip.

## How it works

- Checks latest commit SHA from GitHub API.
- Compares it against stored installed SHA.
- If changed, injects a theme update package into WP updates.
- Renames extracted GitHub folder to `aquaingresssolutions` during update.
- Saves new SHA after successful upgrade.

## Notes

- Repo is public, so no token is required.
- Theme update checks follow WordPress cron/update schedule.
- Elementor is not required.
