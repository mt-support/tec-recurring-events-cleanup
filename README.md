# TEC Recurring Events Cleanup

A single-file WordPress plugin designed to clean up duplicate recurring event instances caused by issue ECP-1912 in Events Calendar Pro.

## Overview

This plugin addresses the aftermath of a bug (ECP-1912) in Events Calendar Pro that could result in users having multiple instances of the same recurring event in their calendar. While the root cause has been fixed in the main Events Pro codebase, this plugin provides a cleanup solution for existing affected sites.

## What It Does

The plugin performs three cleanup operations in batches of 1000 records each:

1. **Deletes Recurrence Queue Meta**: Removes `_TribeEventsPRO_RecurrenceQueue` meta entries that may be causing issues
2. **Deletes Recurring Event Instance Meta**: Removes all post meta associated with recurring event instances (posts with `post_parent > 0`)
3. **Deletes Recurring Event Instance Posts**: Removes the actual recurring event instance posts from the database

## Installation

### Method 1: Upload via WordPress Admin

1. Download the `tec-recurring-events-cleanup.php` file
2. Add it inside of a directory named `tec-recurring-events-cleanup`
3. Zip it
4. Upload the zipped directory by going to the `Plugins` section in the WordPress admin panel and clicking `Add New`
5. Click `Upload Plugin` and select the zipped directory
6. Click `Install Now`
7. Click `Activate`

### Method 2: Via FTP/SFTP

1. Download the `tec-recurring-events-cleanup.php` file
2. Add it inside of a directory named `tec-recurring-events-cleanup`
3. Upload the directory `/wp-content/plugins/`
4. Activate the plugin through the WordPress admin panel

## How It Works

1. **Automatic Execution**: The plugin runs automatically on WordPress shutdown
2. **Batch Processing**: Processes records in batches of 1000 to avoid memory issues
3. **Self-Deactivation**: Automatically deactivates itself when cleanup is complete
4. **Logging**: Logs cleanup results using the `tribe_log` action

## Important Notes

### ⚠️ **CRITICAL WARNINGS**

- **BACKUP YOUR DATABASE FIRST**: This plugin performs destructive database operations. Always create a complete backup before running this plugin.
- **TEST ON STAGING**: Test this plugin on a staging environment before using it on a production site.
- **ONE-TIME USE**: This plugin is designed to be used once and will automatically deactivate itself after completion.

### What Gets Deleted

The plugin specifically targets:

- Recurring event instances (posts with `post_parent > 0`)
- Associated post meta for these instances
- Recurrence queue meta data

**It does NOT delete:**

- Parent recurring events (the original event definitions)
- Non-recurring events
- Event meta for parent events

### When to Use

Use this plugin if:

- You have multiple duplicate instances of the same recurring event
- You've identified that your site was affected by ECP-1912
- You want to clean up the database after the main issue has been resolved

### When NOT to Use

Do NOT use this plugin if:

- You're unsure about your site's state
- You haven't backed up your database
- You're not experiencing duplicate recurring event issues

## Troubleshooting

### Plugin Doesn't Deactivate

If the plugin doesn't automatically deactivate, it means there are still more records to process. The plugin will continue running on subsequent page loads until all affected records are cleaned up.

### Check Cleanup Results

You can check the cleanup results by looking for log entries with the message "TEC Recurring Events Cleanup completed." The log will include details about how many records were processed in each batch.

### Manual Deactivation

If needed, you can manually deactivate the plugin through the WordPress admin panel after it has completed its cleanup.

## Support

This plugin is provided as-is by The Events Calendar team. For issues related to:

- The original ECP-1912 bug: Contact The Events Calendar support
- This cleanup plugin: Review the code and ensure you've followed all safety precautions
