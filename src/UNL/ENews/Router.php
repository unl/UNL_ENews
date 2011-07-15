<?php
class UNL_ENews_Router
{
    protected static $routes = array();

    public static function route($requestURI, $options = array())
    {

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen(urldecode($_SERVER['QUERY_STRING'])) - 1);
        }

        // Trim the base part of the URL
        $requestURI = substr($requestURI, strlen(parse_url(UNL_ENews_Controller::getURL(), PHP_URL_PATH)));

        $routes = self::getRoutes();

        if (isset($options['view'], $routes[$options['view']])) {
            $options['model'] = $routes[$options['view']];
            return $options;
        }

        if (empty($requestURI)) {
            // Default view/homepage
            return $options;
        }

        foreach ($routes as $route_exp=>$model) {
            if ($route_exp[0] == '/'
                && preg_match($route_exp, $requestURI, $matches)) {
                $options += $matches;
                $options['model'] = $model;
                return $options;
            }
        }

        $options['model'] = false;
        return $options;

    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * Set the routes
     *
     * @param array(preg => ModelName) $routes Associative array of routes
     */
    public static function setRoutes($routes)
    {
        self::$routes = $routes;
    }
}