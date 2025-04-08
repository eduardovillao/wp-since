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
-   📖 Reads the declared Requires at least: version from your `readme.txt`
-   🗂️ Compares those symbols with a version map built from WordPress core using `@since` tags
-   🚨 Reports any used symbols that require a newer WP version than what’s declared

### Example Output

Let’s say your plugin uses `register_setting()` (introduced in WP `5.5`), but your `readme.txt` declares compatibility with WordPress `5.4`:

```bash
🔍 Scanning plugin files...
✅ Found readme.txt → Minimum version declared: 5.4

🚨 Compatibility issues found:

┌──────────────────────┬──────────────────┐
│ Symbol               │ Introduced in WP │
├──────────────────────┼──────────────────┤
│ register_setting     │ 5.5.0            │
└──────────────────────┴──────────────────┘

📌 Suggested version required: 5.5.0
```

Now imagine your code is fully aligned with your declared version:

```bash
🔍 Scanning plugin files...
✅ Found readme.txt → Minimum version declared: 5.5

🎉 No compatibility issues found!
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

## 🛠️ Coming Soon

-   GitHub Action integration
-   HTML/Markdown reports
-   Export for CI/CD pipelines

## 📜 License

MIT © [Eduardo Villão](https://github.com/eduardovillao)  
Use freely, contribute gladly.
