<?php


namespace TMDB;


use Curl\Curl;
use TMDB\Traits\Certifications;
use TMDB\Traits\Changes;
use TMDB\Traits\Collections;
use TMDB\Traits\Companies;
use TMDB\Traits\Configuration;
use TMDB\Traits\Credits;
use TMDB\Traits\Discover;
use TMDB\Traits\Find;
use TMDB\Traits\Genres;
use TMDB\Traits\Jobs;
use TMDB\Traits\Keywords;
use TMDB\Traits\Movies;
use TMDB\Traits\Series;
use TMDB\Traits\Search;

class Tmdb extends Curl {

    private $base_url = 'https://api.themoviedb.org/';
    private $version = 3;
    private $api_key = "";

    use Certifications;
    use Changes;
    use Collections;
    use Companies;
    use Configuration;
    use Credits;
    use Discover;
    use Find;
    use Genres;
    use Jobs;
    use Keywords;
    use Movies;
    use Series;
    use Search;

    public function __construct($api_key = "")
    {
        parent::__construct(NULL);
        $this->setUserAgent($_SERVER["HTTP_USER_AGENT"]);
        if(strlen($api_key) > 32 || strlen($api_key) < 32){
            echo  $this->response_json(json_decode(json_encode([
                "status_code" => 202,
                "status_message" => "The size of the api key must be 32 characters"
            ])),true);
            exit();
        }else{
            $this->api_key = $api_key;
        }
    }

    private function _get($path_url,array $parameters = []){
        $url = $this->base_url.$this->version."/".$path_url."?api_key=".$this->api_key;
        if(sizeof($parameters)>0){
            $url = $url."&".http_build_query($parameters);
        }
        $this->setReferer("https://developers.themoviedb.org");
        $this->get($url);
        return $this->response_json($this->response);
    }

    private function _post($path_url, array $parameters = []){
        $url = $this->base_url.$this->version."/".$path_url."?api_key=".$this->api_key;
        $http = new Curl();
        $http->setReferer("https://developers.themoviedb.org");
        $http->setUserAgent($_SERVER["HTTP_USER_AGENT"]);
        if(sizeof($parameters)>0){
            $http->setHeader("Content-Type","application/x-www-form-urlencoded");
        }
        $http->post($url,$parameters);
        return $this->response_json($http->response);
    }

    protected function response_json($response,$print_json = false){
        $json = new \stdClass();
        if(@$response->status_code > 0 ){
            @$json->status = "Error";
            foreach ($response as $key => $value){
                @$json->$key = $value;
            }
        }else{
            @$json->status = "Success";
            @$json->response = $response;
        }
        //@$json->api_version = $this->version;
        if(!$print_json){
            return $json;
        }else{
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    }

}