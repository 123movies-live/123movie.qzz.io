<?php


namespace App;

use App\Traits\Boot;
use App\Traits\Tv;
use App\Traits\Menu;
use App\Traits\Tools;
use TMDB\Tmdb;


class Bootstrap extends Tmdb {

    use Menu;
    use Boot;
    use Tv;
    use Tools;
    
    public function __construct($api_key = "49101d62654e71a2931722642ac07e5e")
    {
        parent::__construct($api_key);
        $this->isMovieWP();
        //add_action('admin_menu',[$this,"CreateAdminMenu"]);
        //add_action('admin_menu',[$this,"CustomAdminItemMenu"]);
        add_action( 'admin_menu', array( $this, 'CreateAdminMenu' ) );
        //add_action( 'admin_menu', array( $this, 'CustomAdminItemMenu' ) );
        if(!empty($_POST['action']))
        if($_POST['action']!="")
        if(isset($_POST['action'])){
            $wp_ajax_action = 'wp_ajax_'.$_POST['action'];
            //add_action($wp_ajax_action,[$this,$_POST['action']]);
            add_action( $wp_ajax_action, array( $this, $_POST['action'] ) );
	     }
    }

    private function isMovieWP(){
        if(wp_get_theme()!='moviewp'){
            add_action('admin_menu', function(){
                if(is_plugin_active(Importer)){
                    deactivate_plugins(Importer);
                }
            });

        }
    }

    public function load_page(){
        wp_enqueue_script(
            "moviewp_jquery",
            "//code.jquery.com/jquery-3.5.1.min.js"
        );
        wp_enqueue_script(
            "moviewp_popper",
            "//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        );
        wp_enqueue_script(
            "moviewp_bootstrap",
            "//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        );
        wp_enqueue_script(
            "moviewp_mdb",
            "//cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"
        );
        wp_enqueue_style(
            "moviewp_font",
            "//use.fontawesome.com/releases/v5.8.2/css/all.css"
        );
        wp_enqueue_style(
            "moviewp_bootstrap",
            "//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        );
        wp_enqueue_style(
            "moviewp_mdb",
            "//cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css"
        );
        if($_GET['page'] == "moviewp_tv"){
        wp_enqueue_script(
            "moviewp_tv",
            plugin_dir_url(__DIR__)."App/public/js/moviewp_tv.js",
            ["jquery"],
            random_int(100000,1000000000)
        );
        wp_localize_script(
            "moviewp_tv",
            "moviewp_ajax",
            ["url"=>admin_url("admin-ajax.php")]
        );
        }
        if($_GET['page'] == "moviewp_tv_discover"){
        wp_enqueue_script(
            "moviewp_tv_discover",
            plugin_dir_url(__DIR__)."App/public/js/moviewp_tv_discover.js",
            ["jquery"],
            random_int(100000,1000000000)
        );
        wp_localize_script(
            "moviewp_tv_discover",
            "moviewp_ajax",
            ["url"=>admin_url("admin-ajax.php")]
        );
        }
        if($_GET['page'] == "moviewp_discover"){
        wp_enqueue_script(
            "moviewp_discover",
            plugin_dir_url(__DIR__)."App/public/js/moviewp_discover.js",
            ["jquery"],
            random_int(100000,1000000000)
        );
        wp_localize_script(
            "moviewp_discover",
            "moviewp_ajax",
            ["url"=>admin_url("admin-ajax.php")]
        );
        }
        if($_GET['page'] == "moviewp_movie"){
        wp_enqueue_script(
            "moviewp_movie",
            plugin_dir_url(__DIR__)."App/public/js/moviewp_movie.js",
            ["jquery"],
            random_int(100000,1000000000)
        );
        wp_localize_script(
            "moviewp_movie",
            "moviewp_ajax",
            ["url"=>admin_url("admin-ajax.php")]
        );
        }
        if($_GET['page'] == "moviewp_boot"){
            if(@$_GET['post']==''){
                $_GET['page'] = 404;
            }else{
                $movie= get_post(@$_GET['post']);
                if(gettype($movie) == "NULL"){
                    $_GET['page'] = 404;
                }
            }
        }
        if(file_exists(PLUGIN_DIR."/App/public/".$_GET['page'].".php")){
            include PLUGIN_DIR."/App/public/".$_GET['page'].".php";
        }else{
            include PLUGIN_DIR."/App/public/404.php";
        }
    }
    
}
