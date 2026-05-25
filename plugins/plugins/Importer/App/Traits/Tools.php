<?php


namespace App\Traits;


trait Tools {

    public function get_post_by_slug($fiel,$value){
        try{
            $post = get_posts(array(
                $fiel => $value,
                'posts_per_page' => 1,
                'post_type' => 'post',
                'post_status' => 'publish'
            ));
if(isset($post[0])){
    return $post[0];
}
else {
  
}
            
        }catch (\Exception $exception){
            return false;
        }
    }

    public function show_header_error($error=""){
        header("HTTP/1.0 500 ".$error);
        exit(0);
    }

    public function parse_url($url) {
        $parse = json_decode(json_encode(parse_url($url)));
        if (@$parse->path != '') {
            $path = explode("/", @$parse->path);
            unset($path[0]);
            @$parse->path_data = $path;
        }
        if (@$parse->query != '') {
            parse_str(@$parse->query, $arr);
            @$parse->query_data = json_decode(json_encode($arr));
        }
        @$parse->url = $url;
        @$parse->class_name=$this->getClassName($url);
        return $parse;
    }

    public function getClassName($url) {
        $host = parse_url($url, PHP_URL_HOST);
        $a = array("www.", "com", ".");
        $b = array("", "com", '_');
        $host = ucwords(str_replace($a, $b, $host));
        if ($host[0] > 0) {
            $host = "S_" . $host;
        }
        return $host;
    }

    public function cut_str($str, $left, $right) {
        $str = substr(stristr($str, $left), strlen($left));
        $leftLen = strlen(stristr($str, $right));
        $leftLen = $leftLen ? -($leftLen) : strlen($str);
        $str = substr($str, 0, $leftLen);
        return $str;
    }
}
