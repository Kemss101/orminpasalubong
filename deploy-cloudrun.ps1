<#
PowerShell helper script to build the container, push to Container Registry (GCR), create secrets, and deploy to Cloud Run.

Usage: edit the variables below or run interactively. This script does NOT store secrets in plain text — it shows how to create Secret Manager secrets and reference them in Cloud Run.

Prerequisites:
- Install gcloud CLI and authenticate: https://cloud.google.com/sdk/docs/install
- Logged in: gcloud auth login
- Enabled billing on your Google Cloud project
#>

param(
    [string]$PROJECT_ID = "orminpasalubong",
    [string]$REGION = "asia-southeast1",
    [string]$SERVICE_NAME = "orminpasalubong-app",
    [string]$IMAGE = "gcr.io/$env:PROJECT_ID/orminpasalubong-app",
    [switch]$CreateSecrets
)

Write-Host "=== Deploy helper for Cloud Run (interactive) ===" -ForegroundColor Cyan

if (-not (Get-Command gcloud -ErrorAction SilentlyContinue)) {
  Write-Error "gcloud CLI not found. Install it first: https://cloud.google.com/sdk/docs/install"
  exit 1
}

Write-Host "Using project: $PROJECT_ID, region: $REGION, service: $SERVICE_NAME" -ForegroundColor Yellow

# Set project
gcloud config set project $PROJECT_ID

# Enable required APIs
Write-Host "Enabling required Google Cloud APIs..." -ForegroundColor Green
gcloud services enable run.googleapis.com cloudbuild.googleapis.com secretmanager.googleapis.com artifactregistry.googleapis.com --quiet

# Optional: create Secret Manager secrets interactively
if ($CreateSecrets) {
  Write-Host "Creating secrets in Secret Manager (interactive). Leave empty to skip a secret." -ForegroundColor Green

  $dbPassword = Read-Host -AsSecureString "DB_PASSWORD (enter secret)"
  if ($dbPassword.Length -gt 0) {
    $temp = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPassword))
    $tempFile = [System.IO.Path]::GetTempFileName()
    Set-Content -Path $tempFile -Value $temp -Force
    gcloud secrets create DB_PASSWORD --data-file=$tempFile --replication-policy="automatic" --project $PROJECT_ID
    Remove-Item $tempFile -Force
    Write-Host "Created secret: DB_PASSWORD"
  }

  $appKey = Read-Host -AsSecureString "APP_KEY (laravel app key)"
  if ($appKey.Length -gt 0) {
    $temp = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($appKey))
    $tempFile = [System.IO.Path]::GetTempFileName()
    Set-Content -Path $tempFile -Value $temp -Force
    gcloud secrets create APP_KEY --data-file=$tempFile --replication-policy="automatic" --project $PROJECT_ID
    Remove-Item $tempFile -Force
    Write-Host "Created secret: APP_KEY"
  }

  Write-Host "You can add more secrets later with: gcloud secrets create NAME --data-file=FILE" -ForegroundColor Cyan
}

# Build and push the container image with Cloud Build
Write-Host "Submitting build to Cloud Build..." -ForegroundColor Green
# Use existing cloudbuild.yaml if present; if not, build locally and push.
if (Test-Path cloudbuild.yaml) {
  gcloud builds submit --config cloudbuild.yaml --project $PROJECT_ID .
} else {
  gcloud builds submit --tag gcr.io/$PROJECT_ID/$SERVICE_NAME --project $PROJECT_ID .
}

# Deploy to Cloud Run
Write-Host "Deploying to Cloud Run..." -ForegroundColor Green

# Construct --set-secrets mapping if secrets exist
$secretsMap = @()
$secretNames = @("DB_PASSWORD","APP_KEY")
foreach ($s in $secretNames) {
  # check if secret exists
  $exists = (gcloud secrets list --filter=name:$s --format="value(name)" --project $PROJECT_ID) -ne $null
  if ($exists) {
    $secretsMap += "$s=projects/$PROJECT_ID/secrets/$s:latest"
  }
}

$setSecretsArgs = ""
if ($secretsMap.Count -gt 0) {
  $setSecretsArgs = "--set-secrets " + ($secretsMap -join ",")
}

# Example env vars to set (non-secret values). Replace placeholders below before running.
$envVars = @(
  "APP_ENV=production",
  "APP_DEBUG=false",
  "APP_URL=https://orminpasalubong.web.app",
  "DB_CONNECTION=pgsql",
  "DB_HOST=<DB_HOST>",
  "DB_PORT=5432",
  "DB_DATABASE=<DB_NAME>",
  "DB_USERNAME=<DB_USER>",
  "SESSION_DRIVER=database"
)

$envArg = "--set-env-vars " + ($envVars -join ",")

# Deploy command (note: using Invoke-Expression to allow $setSecretsArgs and $envArg expansion)
$deployCmd = "gcloud run deploy $SERVICE_NAME --image gcr.io/$PROJECT_ID/$SERVICE_NAME --platform managed --region $REGION --allow-unauthenticated $envArg $setSecretsArgs --project $PROJECT_ID"

Write-Host "About to run:" -ForegroundColor Cyan
Write-Host $deployCmd -ForegroundColor White

$confirm = Read-Host "Proceed with deploy? (yes/no)"
if ($confirm -ne 'yes') { Write-Host "Aborted by user"; exit 0 }

Invoke-Expression $deployCmd

Write-Host "Cloud Run deploy finished. If successful, update firebase.json rewrites to point to this service and run 'firebase deploy --only hosting --config firebase.json'" -ForegroundColor Green
Write-Host "Done." -ForegroundColor Cyan
