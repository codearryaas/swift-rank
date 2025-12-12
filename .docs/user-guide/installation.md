# Installation Guide

## System Requirements

Before installing Schema Engine, ensure your server meets the following requirements:

- **WordPress Version**: 5.0 or higher
- **PHP Version**: 7.0 or higher
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge)

## Installing Schema Engine (Free)

1. Log in to your WordPress admin dashboard.
2. Navigate to **Plugins → Add New**.
3. Search for "Schema Engine".
4. Click **Install Now** on the Schema Engine plugin.
5. Click **Activate**.

Alternatively, you can download the plugin from the WordPress repository and upload the ZIP file:

1. Download the plugin ZIP file.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Choose the downloaded ZIP file.
4. Click **Install Now** and then **Activate**.

## Installing Schema Engine Pro

Schema Engine Pro requires the free version of Schema Engine to be installed and active.

1. Purchase and download Schema Engine Pro from [ToolPress.net](https://toolpress.net/schema-engine/pricing).
2. Log in to your WordPress admin dashboard.
3. Go to **Plugins → Add New → Upload Plugin**.
4. Choose the `schema-engine-pro.zip` file you downloaded.
5. Click **Install Now**.
6. Click **Activate**.

## Activating Your License

After activating Schema Engine Pro, you need to activate your license key to receive updates and support.

1. Go to **Schema Engine → Settings → License → Activate License**.
2. Enter your license key in the field provided.
3. Click **Activate License**.

## Troubleshooting Installation

### "Missing Parent Plugin" Error
If you see a message saying "Schema Engine Pro requires Schema Engine to be installed and active," please ensure you have installed and activated the free version of Schema Engine first.

### Upload Size Limit Exceeded
If you cannot upload the plugin ZIP file, your server's upload limit might be too low. You can:
- Increase the `upload_max_filesize` in your `php.ini`.
- Contact your hosting provider for assistance.
- Install via FTP by extracting the ZIP file to `wp-content/plugins/`.
