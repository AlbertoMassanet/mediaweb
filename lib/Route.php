<?php

namespace lib;

class Route
{
    private static $routes = [];


    public static function get($uri, $callback)
    {
      
      $uri = trim($uri, "/");
      self::$routes['GET'][$uri] = $callback;
    }

    public static function post($uri, $callback)
    {
      $uri = trim($uri, "/");
      self::$routes['POST'][$uri] = $callback;
    }

    public static function dispatch()
    {
      $uri = $_SERVER['REQUEST_URI'];
      $uri = trim($uri, "/");

      // $uris = explode('/',$uri);
      // if (count($uris) > 1) {
      //   $uri = $uris[0];
      //   $params = array_slice($uris,1);
      // }


      $method = $_SERVER['REQUEST_METHOD'];

      foreach (self::$routes[$method] as $route => $callback)
      {
        if (strpos($route, ":") !== false)
        {
          $route = preg_replace('#:[a-zA-Z0-9]+#', '([a-zA-Z0-9]+)', $route);
        }

        if (preg_match("#^$route$#", $uri, $matches))
        {
          $params = array_slice($matches,1);

          // Para evitar que genere una llamada estática al método, evitamos que sea un array
          if (is_callable($callback) && !is_array($callback)) 
          { 
            $response = $callback(...$params);
            
          }

          if (is_array($callback))
          {
            $controller = new $callback[0];
            $response = $controller->{$callback[1]}(...$params);
            
          }

          echo $response;

          return;
        }
      }

      //echo "<pre>" . print_r(self::$routes, true) . "<pre>";
      http_response_code(404);
      echo "404 not found";
    }
}