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

1. Ensure you have `PHP 7.4` installed. If not, install it using Homebrew: `brew install shivammathur/php/php@7.4`
If you're already using another PHP version, switch to 7.4 using tools like `sphp` (run `sphp 7.4` to switch).

2. Create a fork and clone the `unl_enews` GitHub repository ([unl/unl_enews](https://github.com/unl/unl_enews/)).

3. Create your `database schema`.

4. Set up `virtual hosts` (Alternatively, you can use your localhost) and configure certificates for HTTPS. Make sure the path points to the folder containing `index.php`.

5. Create `config.inc.php` and `.htaccess` files by copying data from `config.sample.php` and `sample.htaccess`. Update:
- `UNL_ENews_Controller::setAdmins`
- `UNL_ENews_Controller::setDbSettings`
- `UNL_ENews_Controller::$url` - vhost servername or host/domain
- `$theme` - 'UNL'
- `RewriteBase /workspace/UNL_ENews/www/` - RewriteBase / or however you have your workspace structured

6. Add or create a symlink to the `WDN templates` repository ([unl/wdntemplates](https://github.com/unl/wdntemplates)).

7. Run `composer install` in your project root directory.

8. Execute the top `two SQL files` on your database under the `data` folder for sample data and necessary table creations. You may also want to run the `upgrade.php` script.

9. Access the local site through a web browser.