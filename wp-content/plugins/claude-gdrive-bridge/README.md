# Claude GDrive Bridge — WordPress Plugin

Secure bridge to receive files from Claude VM and upload them to Google Drive.

## Architecture

```
┌─────────────────┐         ┌─────────────────────┐         ┌──────────────┐
│   Claude VM     │  curl   │  WordPress Server    │  API    │ Google Drive  │
│                 │ ──────→ │  (botphonic.ai)      │ ──────→ │              │
│ creates docx    │ multipart│                     │ direct  │ uploads file │
│                 │  upload │ - receives file      │ no MITM │              │
│                 │         │ - uploads to GDrive   │         │              │
└─────────────────┘         └─────────────────────┘         └──────────────┘
```

- Claude VM → WordPress: Fast curl (botphonic.ai is whitelisted)
- WordPress → Google Drive: Direct internet (no Anthropic proxy, no TLS issues)
- File never passes through the chat window

## Installation

1. Download `claude-gdrive-bridge.zip`
2. In WordPress: **Plugins → Add New → Upload Plugin**
3. Upload the zip and activate
4. Go to **Settings → Claude GDrive Bridge**

## Setup

### Step 1: Google Cloud OAuth Credentials

1. Go to [Google Cloud Console → Credentials](https://console.cloud.google.com/apis/credentials)
2. Use your **existing project** (same one used for the MCP server)
3. Click **Create Credentials → OAuth Client ID**
4. Application type: **Web application**
5. Name: `Claude GDrive Bridge`
6. Authorized redirect URI: Copy the URI shown on the plugin settings page
   (e.g., `https://botphonic.ai/wp-admin/options-general.php?page=claude-gdrive-bridge`)
7. Click Create and copy the **Client ID** and **Client Secret**

### Step 2: Configure Plugin

1. Go to **Settings → Claude GDrive Bridge**
2. Paste the **Client ID** and **Client Secret**
3. Click **Save Settings**
4. Click **Authorize Google Drive Access**
5. Sign in with your Google account and grant permission
6. The refresh token will be auto-saved

### Step 3: Note the API Key

The plugin auto-generates a secure API key on activation.
You'll find it on the settings page — this key is used by Claude VM for authentication.

## Usage from Claude VM

### Upload a New File

Upload a file to the same Google Drive folder as a reference document:

```bash
curl -s -X POST \
  "https://botphonic.ai/wp-json/claude-bridge/v1/upload" \
  -H "X-API-Key: YOUR_API_KEY" \
  -F "file=@/home/claude/output.docx" \
  -F "doc_id=GOOGLE_DOC_ID_OR_URL" \
  -F "filename=my-document.docx" \
  -F "action=new"
```

### Replace an Existing File

Replace the content of an existing Google Drive file:

```bash
curl -s -X POST \
  "https://botphonic.ai/wp-json/claude-bridge/v1/upload" \
  -H "X-API-Key: YOUR_API_KEY" \
  -F "file=@/home/claude/output.docx" \
  -F "doc_id=GOOGLE_DOC_ID_TO_REPLACE" \
  -F "filename=my-document.docx" \
  -F "action=replace"
```

### Check Status

```bash
curl -s "https://botphonic.ai/wp-json/claude-bridge/v1/status" \
  -H "X-API-Key: YOUR_API_KEY"
```

### Parameters

| Parameter  | Required | Description |
|-----------|----------|-------------|
| `file`     | Yes      | The file to upload (multipart) |
| `doc_id`   | No       | Google Doc ID or full URL. Used to find the parent folder (action=new) or the file to replace (action=replace) |
| `filename` | No       | Desired filename on Google Drive. Defaults to uploaded filename |
| `action`   | No       | `new` (default) = create new file, `replace` = replace existing file |

### Response

```json
{
  "success": true,
  "file_id": "1abc...",
  "file_name": "output.docx",
  "web_link": "https://docs.google.com/...",
  "action": "new"
}
```

## Security Features

- **API Key Authentication**: Every request requires `X-API-Key` header
- **Rate Limiting**: Max 30 uploads per hour per IP
- **File Validation**: Extension whitelist + MIME type detection
- **Max File Size**: 50MB
- **Temp File Cleanup**: Hourly scheduled cleanup of temp files older than 1 hour
- **Secure Temp Directory**: Protected with `.htaccess` deny rule
- **Timing-Safe Comparison**: API key verified using `hash_equals()`
- **Logging**: All requests logged to `wp-content/claude-gdrive-bridge.log`

## Allowed File Types

docx, doc, xlsx, xls, pptx, ppt, pdf, txt, csv, zip

## Reusing Existing Google OAuth Credentials

If you already have OAuth credentials for your Google Docs MCP server:
- You can use the **same Client ID and Client Secret**
- Just add the WordPress redirect URI to the existing OAuth client's authorized redirect URIs
- The refresh token will be different (per-authorization)

## Troubleshooting

### "Google OAuth not configured"
→ Save Client ID, Client Secret, then click "Authorize Google Drive Access"

### "Token refresh failed"
→ Refresh token may be expired. Re-authorize by clicking the button on settings page.
→ If that fails, revoke access at https://myaccount.google.com/permissions and re-authorize.

### "File has no parent folder"
→ The doc_id might point to a file in "My Drive" root (which has no explicit parent).
→ The file will be uploaded to your Drive root instead.

### 403 from Google Drive API
→ Make sure the Google Drive API is enabled in your Google Cloud project.
→ Go to: https://console.cloud.google.com/apis/library/drive.googleapis.com

## Files

| File | Purpose |
|------|---------|
| `claude-gdrive-bridge.php` | Main plugin file (single file, no dependencies) |
| `wp-content/claude-gdrive-temp/` | Temp directory for uploaded files (auto-created) |
| `wp-content/claude-gdrive-bridge.log` | Request log file |
