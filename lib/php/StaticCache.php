<?php
/**
 * Static caching library. So simple, you should KISS it.
 * 
 * PHP version 5
 * 
 * @category  Caching
 * @package   StaticCache
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2011 Brett Bieber
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link      https://github.com/saltybeagle/StaticCache
 */

/**
 * Simple static caching library which uses the requestURI and filesystem.
 * 
 * PHP version 5
 * 
 * @category  Caching
 * @package   StaticCache
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2011 Brett Bieber
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link      https://github.com/saltybeagle/StaticCache
 */
class StaticCache
{

    /**
     * Default options for the class
     *
     * @var array
     */
    protected $options = array(
        /**
         * The web root where files will be stored
         *
         * @var string
         */
        'root_dir'            => '',

        /**
         * If existing files should be overwritten, typically not needed
         *
         * @var bool
         */
        'update_files'        => false,

        /**
         * Umask to use for new directories
         *
         * If new directories are created, this controls the umask set for
         * new directories.
         *
         * @var integer
         */
        'new_directory_umask' => 0777,

        /**
         * Filename to use for directory indexes, this should match what Apache
         * has configured for DirectoryIndex
         *
         * @var string
         */
        'index_document'      => 'index.html',
    );

    /**
     * Construct a new StaticCache lib
     *
     * @param array $options Associative array of options
     *
     * @see StaticCache::setOptions()
     */
    public function __construct($options = array())
    {
        if (empty($options['root_dir'])) {
            // Use the server's document root by default
            $options['root_dir'] = getcwd();
        }
        $this->setOptions($options);
    }

    /**
     * Set configuration options for the cache library
     *
     * @param array $options Associative array of options
     */
    public function setOptions($options = array())
    {
        $this->options = $options + $this->options;
    }

    /**
     * Get configuration options
     * 
     * @return array
     */
    public function getOptions()
    {
    	return $this->options;
    }

    /**
     * Attempt to get cached output
     *
     * @param string $request_uri Request URI, usually $_SERVER['REQUEST_URI']
     *
     * @return boolean|string
     */
    public function get($request_uri)
    {
        $file = $this->getLocalFilename($request_uri);

        if (!is_readable($file)) {
            return false;
        }

        if ($data = file_get_contents($file)) {
            return $data;
        }

        return false;
    }

    /**
     * Save the data to the key specified
     *
     * @param string $data        Rendered output
     * @param string $request_uri Request URI, usually $_SERVER['REQUEST_URI']
     *
     * @return boolean
     */
    public function save($data, $request_uri)
    {
        $file = $this->getLocalFilename($request_uri);
        $this->createDirs($file);
        return $this->saveCacheFile($file, $data);
    }

    /**
     * Convert the request_uri to a local filename
     *
     * @param string $request_uri The request URI
     *
     * @throws Exception
     *
     * @return string
     */
    protected function getLocalFilename($request_uri)
    {
        if (false !== strpos($request_uri, '..')) {
            throw new Exception('upper directory reference .. cannot be used');
        }

        if (   empty($request_uri)
            || substr($request_uri, -1) === '/') {
            // User is requesting a directory index
            $request_uri .= $this->options['index_document'];
        }

        if ($request_uri[0] !== '/') {
            // add leading slash
            $request_uri = DIRECTORY_SEPARATOR . $request_uri;
        }

        return $this->options['root_dir'].$request_uri;
    }

    /**
     * Create parent directories for the file
     *
     * @param string $file Local filename
     *
     * @throws Exception
     *
     * @return boolean
     */
    protected function createDirs($file)
    {
        $dir = dirname($file);

        if (is_dir($dir)) {
            return true;
        }

        if (is_file($dir)) {
            $this->convertFileToDir($dir);
            return true;
        }

        if (false === mkdir($dir, $this->options['new_directory_umask'], true)) {
            throw new Exception('Could not create directory structure for '.$file);
        }
        return true;
    }

    /**
     * Convert a file to a directory
     * 
     * This method corrects request sequences such as:
     * GET /people
     * GET /people/123
     * 
     * The first request will create a people file and not a directory, this 
     * method converts the people file into a directory.
     *
     * @param string $file Existing file to convert to a directory
     *
     * @throws Exception
     * 
     * @return true
     */
    protected function convertFileToDir($file)
    {

        // Copy existing file to a temp file
        if (false === copy($file, $file . '__staticcache__')) {
            // Failed to convert existing file to directory
            throw New Exception('Sorry, that directory already exists as a file.');
        }

        if (false === unlink($file)) {
            // Failed to remove existing file
            throw new Exception('Could not remove '.$file.' while converting file to dir.');
        }

        // Now create the directory for the index document
        $this->createDirs($file . DIRECTORY_SEPARATOR . $this->options['index_document']);

        if (false === copy($file . '__staticcache__', $file . DIRECTORY_SEPARATOR . $this->options['index_document'])) {
            // Failed to copy dir to index document
            throw new Exception('Failed to copy existing file to index document.');
        }

        // Remove temprary file
        unlink($file . '__staticcache__');
        return true;
    }

    /**
     * Save contents to specified filename
     * 
     * This is a hardended version of file_put_contents
     *
     * @param string $file     Local filename to write
     * @param string $contents Contents of the file
     *
     * @throws Exception
     *
     * @return boolean
     */
    protected function saveCacheFile($file, $contents)
    {
        $len = strlen($contents);

        $cachefile_fp = @fopen($file, 'xb'); // x is the O_CREAT|O_EXCL mode
        if ($cachefile_fp !== false) {
            // create file
            if (fwrite($cachefile_fp, $contents, $len) < $len) {
                fclose($cachefile_fp);
                throw new Exception("Could not write $file.");
            }
        } else {
            // could not open for create

            if (file_exists($file)
                && true !== $this->options['update_files']) {
                throw new Exception("The file $file already exists. Set update_files=>true or empty the cache");
            }

            $cachefile_lstat = @lstat($file);
            $cachefile_fp = @fopen($file, 'wb');
            if (!$cachefile_fp) {
                throw new Exception("Could not open $file for writing. Likely a permissions error.");
            }
    
            $cachefile_fstat = fstat($cachefile_fp);
            if (
            $cachefile_lstat['mode'] == $cachefile_fstat['mode'] &&
            $cachefile_lstat['ino']  == $cachefile_fstat['ino'] &&
            $cachefile_lstat['dev']  == $cachefile_fstat['dev'] &&
            $cachefile_fstat['nlink'] === 1
            ) {
                if (fwrite($cachefile_fp, $contents, $len) < $len) {
                    fclose($cachefile_fp);
                    throw new Exception("Could not write $file.");
                }
            } else {
                fclose($cachefile_fp);
                $link = function_exists('readlink') ? readlink($file) : $file;
                throw new Exception('SECURITY ERROR: Will not write to ' . $file . ' as it is symlinked to ' . $link . ' - Possible symlink attack');
            }
        }

        fclose($cachefile_fp);
        return true;
    }

    /**
     * This is a simple method for handling the most basic of output caching.
     * 
     * This uses output buffering to capture any generated output, then saves
     * that output to a filename matching the request URI.
     * 
     * Certain conditions are checked to ensure only idempotent requests are 
     * saved:
     * 
     * * $_GET, $_POST, and $_FILES must all be empty
     * * No headers must be already sent
     * * If a Status header is to be sent, must be <300
     * * Do not cache Location: redirects
     * 
     * @param array $options Associative array of options
     * 
     * @see StaticCache::__construct()
     */
    public static function autoCache($options = array())
    {
        ob_start();
        $cache = new StaticCache($options);
        register_shutdown_function(function() use ($cache) {
            if ($cache->requestIsCacheable($_GET, $_POST, $_FILES, $_SERVER)) {
                $request_uri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])));
                $data = ob_get_contents();
                try {
                    $cache->save($data, $request_uri);
                } catch(Exception $e) {
                    // Fail silently
                }
            }
        });
    }

   /**
    * Simple checks to determine if a request should be cached or not.
    * 
    * @return bool
    */
    function requestIsCacheable($get = array(), $post = array(), $files = array(), $server = array())
    {
        if (!(
               empty($get)
            && empty($post)
            && empty($files)
            )) {
            return false;
        }

        if (isset($server['REQUEST_METHOD'])) {
            switch ($server['REQUEST_METHOD']) {
                case 'POST':
                case 'PUT':
                    return false;
                case 'GET':
                case 'HEAD':
                default:
                    continue;
            }
        }

        if (headers_sent()) {
            // We have no clue what the headers should be, so abort caching
            return false;
        }

        foreach ($this->getResponseHeaders() as $header) {
            if (preg_match('/^(HTTP\/[\d]+\.[\d]+|Status:)\s+([3-5][\d]+)\s*.*$/', $header)) {
                // Do not cache anything greater than 299
                return false;
            }
            if (0 === strpos($header, 'Location:')) {
                // Do not cache redirects
                return false;
            }
        }

        return true;
    }

    /**
     * Test helper
     * 
     * @return array Array of headers to be sent
     */
    protected function getResponseHeaders()
    {
    	return headers_list();
    }
}