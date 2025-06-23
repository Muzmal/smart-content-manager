# Smart Content Manager

A lightweight, secure WordPress plugin that allows content restriction based on login status and user roles — via a simple UI and powerful shortcode.

> Developed by [Muzammal Rasool](https://github.com/Muzmal) – WordPress Plugin Developer

---

## 🚀 Features

- ✅ Restrict full post/page visibility using a meta box
- ✅ Show/hide parts of content via `[scm_restrict]` shortcode
- ✅ Control access by login state or specific user roles
- ✅ Customize restriction messages from the settings page
- ✅ Follows WordPress coding standards (OOP, sanitization, security)
- ✅ Clean uninstall – no leftover data

---

## 🧠 Use Cases

- Membership or gated content
- Client dashboard areas
- Educational content restriction
- Temporary access control

---

## 🔧 How It Works

### 1. Meta Box (Post/Page)

Select:
- Only Logged-in Users
- Allowed Roles (Admin, Editor, etc.)

### 2. Shortcode (Within Content)

```php
[scm_restrict]This is for logged-in users only.[/scm_restrict]

[scm_restrict roles="administrator,editor"]
Only Admins and Editors can see this.
[/scm_restrict]


== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/smart-content-manager` directory.
2. Activate through the 'Plugins' screen in WordPress.
3. Edit any post or page, and use the new meta box to configure access.


== Changelog ==
= 1.0.0 =
* Initial release

== Upgrade Notice ==
Initial release

== License ==
Smart Content Manager is released under the GPLv2 or later.

Uninstallation
When the plugin is deleted, all related settings and post meta are removed cleanly.

Security
Uses nonces and capability checks

Sanitizes input, escapes output

Follows best practices for post meta and options


License
Licensed under the GPLv2 or later.

About the Developer
Hi, I’m Muzammal Rasool, a professional WordPress plugin developer with a strong focus on performance, usability, and security.
