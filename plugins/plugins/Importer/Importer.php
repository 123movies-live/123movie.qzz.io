<?php
/*
Plugin Name: Importer
Plugin URI: https://fr0zen.mysellix.io/product/617ad2ba10ba2
Description: Importer Premium WordPress plugin for automatic import of movies, anime & tv series informations from TMDB exclusively for the Moviewp Theme.
Version: 5.0
Author: fr0zen
Author URI: https://fr0zen.mysellix.io/
License: Regular (FREE)
*/

define("Importer",plugin_basename(__FILE__));
define("PLUGIN_DIR",__DIR__);

require_once PLUGIN_DIR."/vendor/autoload.php";


if(is_admin()){
    $moviewp = new \App\Bootstrap();
}