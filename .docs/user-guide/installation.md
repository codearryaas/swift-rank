# Installation Guide

## System Requirements

Before installing Swift Rank, ensure your server meets the following requirements:

- **WordPress Version**: 5.0 or higher
- **PHP Version**: 7.0 or higher
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge)

## Installing Swift Rank (Free)

1. Log in to your WordPress admin dashboard.
2. Navigate to **Plugins → Add New**.
3. Search for "Swift Rank".
4. Click **Install Now** on the Swift Rank plugin.
5. Click **Activate**.

Alternatively, you can download the plugin from the WordPress repository and upload the ZIP file:

1. Download the plugin ZIP file.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Choose the downloaded ZIP file.
4. Click **Install Now** and then **Activate**.

## Installing Swift Rank Pro

Swift Rank Pro requires the free version of Swift Rank to be installed and active.

1. Purchase and download Swift Rank Pro from [ToolPress.net](https://toolpress.net/swift-rank/pricing).
2. Log in to your WordPress admin dashboard.
3. Go to **Plugins → Add New → Upload Plugin**.
4. Choose the `swift-rank-pro.zip` file you downloaded.
5. Click **Install Now**.
6. Click **Activate**.

## Setup Wizard

After activating Swift Rank for the first time, you'll be automatically redirected to the Setup Wizard. This guided setup helps you configure the essential settings quickly.

### What the Wizard Configures

The Setup Wizard walks you through:

1. **Welcome**: Introduction to Swift Rank
2. **Knowledge Graph**: 
   - Choose your entity type (Organization, Person, or LocalBusiness)
   - Enter basic information (name, logo, description)
   - Add contact details
3. **Social Profiles**: Add your social media links
4. **Completion**: Review and finish setup

### Skipping the Wizard

You can skip the wizard at any time by clicking **Skip Setup**. All settings can be configured manually later at **Swift Rank → Settings**.

### Re-running the Wizard

To run the setup wizard again:

1. Go to **Swift Rank → Settings**
2. Scroll to the bottom of any settings tab
3. Click **Run Setup Wizard** button
4. Follow the wizard steps again

The wizard is helpful when:
- Setting up Swift Rank on a new site
- Reconfiguring your Knowledge Graph
- Teaching team members how to configure settings

## Activating Your License

After activating Swift Rank Pro, you need to activate your license key to receive updates and support.

1. Go to **Swift Rank → Settings → License → Activate License**.
2. Enter your license key in the field provided.
3. Click **Activate License**.

## Troubleshooting Installation

### "Missing Parent Plugin" Error
If you see a message saying "Swift Rank Pro requires Swift Rank to be installed and active," please ensure you have installed and activated the free version of Swift Rank first.

### Upload Size Limit Exceeded
If you cannot upload the plugin ZIP file, your server's upload limit might be too low. You can:
- Increase the `upload_max_filesize` in your `php.ini`.
- Contact your hosting provider for assistance.
- Install via FTP by extracting the ZIP file to `wp-content/plugins/`.
