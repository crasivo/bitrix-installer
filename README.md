# 🚀 Bitrix Project Installer

[![License](https://img.shields.io/github/license/crasivo/bitrix-installer?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue?style=flat-square)](composer.json)
[![Composer Type](https://img.shields.io/badge/composer-project-red?style=flat-square)](composer.json)

A lightweight Composer skeleton/adapter designed to quickly prepare a clean environment for **1C-Bitrix** installation
with public directory isolation.

---

## 🎯 Goal & Core Idea

This project is not a pre-packaged CMS installation. It is a minimalist adapter that prepares the target workspace and
dynamically downloads the official vendor scripts (`bitrixsetup.php` & `restore.php`) during `composer create-project`.

### Key Design Decisions:

* **Directory Isolation**: Prepared for a secure structure where the web server root points to a `public/` directory,
  isolating code files like `vendor/` and configuration files from the public web space.
* **Zero Bloat**: Intentionally contains zero third-party Composer packages (like Symfony components, PSR libraries,
  ORMs). The project remains extremely lightweight and only checks for PHP version and required extensions.
* **Dynamic Loading**: No CMS source code is stored in this repository. Everything is fetched securely at install time
  via Composer's `post-create-project-cmd` hooks.

---

## ⚡ Quick Start

To initialize a new Bitrix environment, run the standard Composer command:

```bash
composer create-project crasivo/bitrix-installer my-bitrix-site
```

### What Happens Under the Hood:

1. Composer verifies that your environment meets the minimum PHP version and extension requirements.
2. The folder structure is prepared.
3. The official installer scripts are dynamically downloaded:
  * `bitrixsetup.php` -> downloaded as `index.php` for direct installation launcher.
  * `restore.php` -> downloaded for backup restoration.
  * `restore.php.debug` -> created for debugging.

---

## 📄 License

This project is open-source and licensed under the [MIT License](LICENSE).
