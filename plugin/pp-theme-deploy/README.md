# PP Theme Deploy

Deploys the theme to live when GitHub Actions pushes a zip payload to:

- `wp-json/pp-theme-deploy/v1/deploy`

## Install on WordPress

1. Zip folder `plugin/pp-theme-deploy`.
2. Upload in `Plugins -> Add New -> Upload Plugin`.
3. Activate **PP Theme Deploy**.
4. Open `Settings -> PP Theme Deploy`.
5. Copy:
   - Deploy Endpoint
   - Deploy Token

## GitHub Secrets Needed

- `PP_DEPLOY_ENDPOINT` = endpoint from plugin settings
- `PP_DEPLOY_TOKEN` = token from plugin settings

The GitHub Action `.github/workflows/pp-theme-deploy.yml` uses these secrets and deploys on every push to `main`.
