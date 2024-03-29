E-Newsletter Platform for Educational Institutions

UNL_ENews is a micro-news management platform for funneling news and building
electronic newsletters.

Users from the community submit news stories into a "Newsroom" where administrators
build an electronic newsletter, which can be distributed through email.

News Stories can include:

 * Quick summary (required)
 * Full text
 * Photos
 * Web link

Use scripts within the scripts directory to create new newsrooms

# Local Installation

1. Use php 7.4

2. Create a fork and clone the `unl_enews` GitHub repository ([unl/unl_enews](https://github.com/unl/unl_enews/)).

3. Create your `database schema`

4. Create `www/config.inc.php` and `www/.htaccess` files by copying data from `www/config.sample.php` and `www/sample.htaccess`. Update:
- `UNL_ENews_Controller::setAdmins`
- `UNL_ENews_Controller::setDbSettings`
- `UNL_ENews_Controller::$url` - vhost servername or host/domain
- `$theme` - 'UNL'
- `RewriteBase /workspace/UNL_ENews/www/` - RewriteBase / or however you have your workspace structured

5.  Run `upgrade.php` script.

6. Add or create a symlink to the `WDN templates` repository ([unl/wdntemplates](https://github.com/unl/wdntemplates)).

7. Run `composer install` in your project root directory.


### Last Updated: March 29, 2024
