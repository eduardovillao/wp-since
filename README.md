# WP Since

![License](https://img.shields.io/badge/license-MIT-blue.svg)
[![Code Style: PSR-12](https://img.shields.io/badge/code%20style-PSR--12-blue)](https://www.php-fig.org/psr/psr-12/)
[![Tests](https://img.shields.io/badge/tests-passing-brightgreen)](./tests)

**Make sure your plugin works with the right WordPress version — automatically.**  
Scans your WordPress plugin to detect all used core symbols and validates them against their official @since versions for accurate compatibility checks.

## ✨ How It Works

Ever struggled to define the correct minimum WordPress version for your plugin?

Worried about accidentally using functions or APIs that don’t exist in declared minimum WP version?

`wp-since` helps you avoid those headaches by automatically analyzing your plugin’s code and checking compatibility against real WordPress versions.

### Here’s what it does:

-   🧠 Scans your plugin for used:
    -   Functions
    -   Classes
    -   Class methods (static and instance)
    -   Action and filter hooks
-   📖 Reads the declared `Requires at least:` version from your plugin's main file header — or `readme.txt` if the header doesn't set it
-   🗂️ Compares those symbols with a version map built from WordPress core using `@since` tags
-   🚨 Reports any used symbols that require a newer WP version than what’s declared

### Example Output

Let’s say your plugin uses `register_setting()` (introduced in WP `5.5`), but your `readme.txt` declares compatibility with WordPress `5.4`:

```bash
✅ Minimum version declared: 5.4 (from readme)

🚨 Compatibility issues found:

┌─────────────────────────────┬──────────────────┐
│ Symbol                      │ Introduced in WP │
├─────────────────────────────┼──────────────────┤
│ register_setting (function) │ 5.5.0            │
└─────────────────────────────┴──────────────────┘

📌 Suggested version required:  5.5.0
```

Now imagine your code is fully aligned with your declared version:

```bash
✅ Minimum version declared: 5.5 (from readme)

✅ All good! Your plugin is compatible with WP 5.5.
```

Simple. Powerful. Automatic.  
Because your plugin deserves reliable compatibility.

## 🚀 Usage

**Requirements**

-   PHP 7.4+
-   Composer

🛠️ Install via Composer (recommended)

```bash
composer require --dev eduardovillao/wp-since
```

▶️ Run the compatibility check

```bash
./vendor/bin/wp-since check ./path-to-your-plugin
```

By default the minimum WordPress version is detected automatically from your plugin header or `readme.txt`. To override it, pass `--min-wp-version`:

```bash
./vendor/bin/wp-since check ./path-to-your-plugin --min-wp-version=6.0
```

### 🧹 Ignore Files & Folders

By default, wp-since scans all `.php` files in your plugin directory.

But what about files that don’t make it into your final plugin zip — like tests or dev tools? No worries — wp-since respects your ignore rules.

**Supported ignore sources:**

-   `.distignore`
-   `.gitattributes` with `export-ignore`

If any of those files are present, wp-since will automatically ignore the listed files or folders during analysis — just like svn export or plugin deployment.

Example: .gitattributes

```txt
/tests/              export-ignore
/tools/debug.php     export-ignore
```

Example: .distignore

```txt
/tests
/tools/debug.php
```

> These paths will be excluded from compatibility checks. This helps avoid false positives caused by test or development files.

### 📝 Inline Ignore

You can ignore specific lines from the scan by adding a special inline comment.

This is useful when you conditionally use a newer function but know it’s safe, like:

```php
if (function_exists('wp_some_new_func')) {
    return wp_some_new_func(); // @wp-since ignore
}
```

> Only inline comments on the same line will be considered — comments above the line won’t trigger ignores.

## 🛠️ Coming Soon

-   GitHub Action integration
-   HTML/Markdown reports
-   Export for CI/CD pipelines

## 📜 License

MIT © [Eduardo Villão](https://github.com/eduardovillao)  
Use freely, contribute gladly.
