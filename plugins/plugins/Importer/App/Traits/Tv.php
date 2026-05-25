<?php


namespace App\Traits;

/**
 * Description of Tv
 *
 * @author fr0zen
 */
trait Tv {

    private $configuration = null;
	//tv series
    public function SearchSerie(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->SearchSeries(@$_POST['movie_title'],@$_POST['language'],@$_POST['page'],@$_POST['content_adult'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->name);
					@$result[$index]->title = str_replace('"', ' ', $value->name);
                    @$result[$index]->original_title = $value->original_name;
                    //@$result[$index]->poster = "https://image.tmdb.org/t/p/w370_and_h556_bestv2".$value->poster_path;
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->first_air_date)[0];
	                if($this->get_post_by_slug("name",sanitize_title( @$result[$index]->title)) || $this->get_post_by_slug("title", @$result[$index]->title)){
					@$result[$index]->clas = "added animated bounceOutUp";
                    } else { 
					@$result[$index]->clas = "none";
					}
                }
            }
        }
        @$movies->results = $result;
        echo wp_json_encode($movies);
        exit();
    }
    public function Discovering(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->TVDisco(@$_POST['language'],@$_POST['sort_by'],@$_POST['first_air_date_year'],@$_POST['with_genres'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
                    //@$result[$index]->title = $value->title;
					@$result[$index]->title = str_replace("'", " ", $value->name);
					@$result[$index]->title = str_replace('"', ' ', $value->name);
                    @$result[$index]->original_title = $value->original_name;
                    //@$result[$index]->original_title = $value->original_title;
                    //@$result[$index]->poster = "https://image.tmdb.org/t/p/w370_and_h556_bestv2".$value->poster_path;
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->first_air_date)[0];
	                if($this->get_post_by_slug("name",sanitize_title( @$result[$index]->title)) || $this->get_post_by_slug("title", @$result[$index]->title)){
					@$result[$index]->clas = "added animated bounceOutUp";
                    } else { 
					@$result[$index]->clas = "none";
					}
                }
            }
        }
        @$movies->results = $result;
        echo wp_json_encode($movies);
        exit();
    }
	//popular
    public function Popular(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->GetPopular(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->name);
					@$result[$index]->title = str_replace('"', ' ', $value->name);
                    @$result[$index]->original_title = $value->original_name;
                    //@$result[$index]->poster = "https://image.tmdb.org/t/p/w370_and_h556_bestv2".$value->poster_path;
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->first_air_date)[0];
	                if($this->get_post_by_slug("name",sanitize_title( @$result[$index]->title)) || $this->get_post_by_slug("title", @$result[$index]->title)){
					@$result[$index]->clas = "added animated bounceOutUp";
                    } else { 
					@$result[$index]->clas = "none";
					}
                }
            }
        }
        @$movies->results = $result;
        echo wp_json_encode($movies);
        exit();
    }

	//latest
    public function Top(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->GetTopRated(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->name);
					@$result[$index]->title = str_replace('"', ' ', $value->name);
                    @$result[$index]->original_title = $value->original_name;
                    //@$result[$index]->poster = "https://image.tmdb.org/t/p/w370_and_h556_bestv2".$value->poster_path;
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->first_air_date)[0];
	                if($this->get_post_by_slug("name",sanitize_title( @$result[$index]->title)) || $this->get_post_by_slug("title", @$result[$index]->title)){
					@$result[$index]->clas = "added animated bounceOutUp";
                    } else { 
					@$result[$index]->clas = "none";
					}
                }
            }
        }
        @$movies->results = $result;
        echo wp_json_encode($movies);
        exit();
    }

	//OnTheAir
    public function Air(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->OnTheAir(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->name);
					@$result[$index]->title = str_replace('"', ' ', $value->name);
                    @$result[$index]->original_title = $value->original_name;
                    //@$result[$index]->poster = "https://image.tmdb.org/t/p/w370_and_h556_bestv2".$value->poster_path;
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->first_air_date)[0];
	                if($this->get_post_by_slug("name",sanitize_title( @$result[$index]->title)) || $this->get_post_by_slug("title", @$result[$index]->title)){
					@$result[$index]->clas = "added animated bounceOutUp";
                    } else { 
					@$result[$index]->clas = "none";
					}
                }
            }
        }
        @$movies->results = $result;
        echo wp_json_encode($movies);
        exit();
    }

	//Anime
    public function Anime(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->GetAnime(@$_POST['language'],$sort_by="popularity.desc",$with_genres="16",$with_keywords="210024",$with_original_language="ja",@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->name);
					@$result[$index]->title = str_replace('"', ' ', $value->name);
                    @$result[$index]->original_title = $value->original_name;
                    //@$result[$index]->poster = "https://image.tmdb.org/t/p/w370_and_h556_bestv2".$value->poster_path;
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->first_air_date)[0];
	                if($this->get_post_by_slug("name",sanitize_title( @$result[$index]->title)) || $this->get_post_by_slug("title", @$result[$index]->title)){
					@$result[$index]->clas = "added animated bounceOutUp";
                    } else { 
					@$result[$index]->clas = "none";
					}
                }
            }
        }
        @$movies->results = $result;
        echo wp_json_encode($movies);
        exit();
    }

    public function AddSerie(){
        $movie_id = @$_POST['tmdb_movie_id'];
        $movie = $this->GetSerieDetails($movie_id,@$_POST['tmdb_language'])->response;
        if(@$movie->id == @$_POST['tmdb_movie_id']){
            $this->configuration = $this->GetAPIConfiguration()->response;
            $genres = array();
            foreach ($movie->genres as $index => $value){
                if(in_array($_POST['tmdb_language'],["en-US","fr-FR","es-ES","it-IT"])){
                    $value->name = str_replace(array("Action & Adventure"),array("Action"),$value->name);
					$value->name = str_replace(array("Sci-Fi & Fantasy"),array("Science Fiction"),$value->name);
                }
                if(get_the_category_by_ID(1)=='Uncategorized'){
                    wp_update_term(1,"category",[
                        "name"=>@$value->name,
                        "slug"=>sanitize_title(@$value->name)
                    ]);
                    array_push($genres,1);
                }else{
                    $isCat = get_category_by_slug(sanitize_title(@$value->name));
                    if(@$isCat->term_id != ''){
                        array_push($genres,@$isCat->term_id);
                    }else{
                        $idcat= wp_insert_category([
                            "cat_name"=>@$value->name,
                            "category_nicename"=>sanitize_title(@$value->name),
                            "category_parent"=>"",
                            "category_description"=>@$value->name
                        ]);
                        array_push($genres,$idcat);
                    }
                }
            }

            if($this->get_post_by_slug("name",sanitize_title($movie->name)) || $this->get_post_by_slug("title",$movie->name)){
                $this->show_header_error("The title '".@$movie->name."' already exists");
            }else{
                $new_post = [
                    "post_title"=>$movie->name,
                    "post_name"=>sanitize_title($movie->name),
                    "post_excerpt"=>$movie->overview,
                    "post_content" => $movie -> overview,
                    "post_category"=>$genres,
                    "post_status"=>"publish"
                ];

                $POST_ID = wp_insert_post($new_post);
                if(@$POST_ID){
                    $stars = $director = $cast = array();
                    /**
                     * We look for the cast of the tv show and store it in a variable
                     */
                    $credits = $this->GetSerieCast($movie_id)->response;
                    if(sizeof($credits->cast)>0){
                        foreach ($credits->cast as $index => $actor){
                            @$cast[$index] = $actor->name;
                            if($index<3){
                                @$stars[$index] = $actor->name;
                            }
                        }
                        $cast = implode(",",$cast);
                        $stars = implode(",",$stars);
                    }
                    /**
                     * We look for the directors of the tv show and store them in a variable
                     */
                    $credits = $this->GetSerieCrew($movie_id)->response;
                    if(sizeof($credits->crew)>0){
                        foreach ($credits->crew as $index => $crew){
                            if($crew->known_for_department == 'Writing'){
                                @$director[$index]=$crew->name;
                            }
                        }
                        $director = implode(",",$director);
                    }
                    /**
                     * We retrieve the keywords
                     */
                    $keywords = $this->GetSerieKeywords($movie_id)->response;
                    if(sizeof($keywords->results)>0){
                        $arr=array();
                        foreach ($keywords->results as $index => $value){
                                @$arr[$index] = @$value->name;
							
                        }
                        
                        $keywords=$arr;
						wp_set_post_tags($POST_ID,$keywords);
                    } 
                    /**
                     * We retrieve the tv show TV parental guidelines
                     */
                    $releases = $this->GetSerieCertification($movie_id)->response;
                    if(sizeof($releases->results)>0){
                        foreach ($releases->results as $index => $results){
                            if($results->iso_3166_1 == 'US'){
                                @$certification[$index]=$results->certification;
								add_post_meta($POST_ID,"Rated",@$certification[$index]=$results->rating);
                            }
                        }
                    }

                    /**
                     * We retrieve the IMDB id of the searched tv show and store them in a variable
                     */
                    $external = $this->GetSerieExternalIds($movie_id)->response;
					add_post_meta($POST_ID,"imdb_id",trim($external->imdb_id));


                    /**
                     * We retrieve the trailers of the searched tv show and store them in a variable
                     */
                    $trailers = $this->GetSerieVideos($movie_id,"en-US")->response;
                    if(sizeof($trailers->results)>0){
                        $arr=array();
                        foreach ($trailers->results as $index => $value){
                            @$arr[$index] = @$value->key;

                        }
                        $trailers="".@$arr[0]."";
                        if($trailers == ''){
                        $trailers = "";
                            }
                        } else {
					    $trailers = "";
                        }
                    /**
                     * We retrieve the images of the searched tv show and store it in a variable
                     */
                    $images = @$this->GetSerieBackdrops($movie_id)->response;
                    if(sizeof($images->backdrops)>0){
                        if(sizeof($images->backdrops) > 10){
                            $limit = 10;
                        }else{
                            $limit = sizeof($images->backdrops);
                        }
                        $backdrops = array();
                        for($i=0;$i<$limit;$i++){
                            @$backdrops[$i] = @$this->configuration->images->secure_base_url.$this->configuration->images->backdrop_sizes[1].$images->backdrops[$i]->file_path;
                        }
                        $backdrops = implode(",",$backdrops);
                    }
                    $vote_average = substr($movie->vote_average, 0, 3);
                    add_post_meta($POST_ID,"Runtime",trim($movie->episode_run_time[0])." min");
                    add_post_meta($POST_ID,"Title",trim($movie->name));
                    add_post_meta($POST_ID,"imdbRating",$vote_average);
                    add_post_meta($POST_ID,"vote_average",$vote_average);
                    add_post_meta($POST_ID,"vote_count",trim($movie->vote_count));
					add_post_meta($POST_ID,"custom_post_template","tv.php");
                    add_post_meta($POST_ID,"id",trim($movie->id));
                    add_post_meta($POST_ID,"overview",trim($movie->overview));
                    add_post_meta($POST_ID,"poster_path",trim($movie->poster_path));
                    add_post_meta($POST_ID,"tagline",trim($movie->tagline));
                    add_post_meta($POST_ID,"last_air_date",trim($movie->last_air_date));
                    add_post_meta($POST_ID,"number_of_seasons",trim($movie->number_of_seasons));
                    add_post_meta($POST_ID,"number_of_episodes",trim($movie->number_of_episodes));
                    add_post_meta($POST_ID,"original_name",trim($movie->original_name));
                    add_post_meta($POST_ID,"original_language",trim($movie->original_language));
                    add_post_meta($POST_ID,"production_companies",trim($movie->production_companies[0]->name));
                    add_post_meta($POST_ID,"homepage",trim($movie->homepage));
                    add_post_meta($POST_ID,"status",trim($movie->status));
                    add_post_meta($POST_ID,"popularity",trim($movie->popularity));
                    add_post_meta($POST_ID,"backdrop_path",trim($movie->backdrop_path));
                    add_post_meta($POST_ID,"youtube_id",$trailers);
					$country = @$movie->production_countries[0]->name;
					$country = str_replace(array("United Kingdom"),array("UK"),$country);
					$country = str_replace(array("United States of America"),array("USA"),$country);
					$crea = @$movie->created_by[0]->name;
                    wp_set_post_terms($POST_ID,$stars,"actors");
                    wp_set_post_terms($POST_ID,$crea,"creator");
                    wp_set_post_terms($POST_ID,@explode("-",@$movie->first_air_date)[0], "years");
                    add_post_meta($POST_ID,"release_date",trim(@explode("-",@$movie->first_air_date)[0]));
					wp_set_post_terms($POST_ID,$country,"country");
                    wp_set_post_terms($POST_ID,$genres,"category");
                    $category_tv = get_cat_ID( 'TV Series' );
                    wp_set_post_categories( $POST_ID, $category_tv, 'category');
                    //network
                    $network_name = @$movie->networks[0]->name;
                    $network_id = @$movie->networks[0]->id;
                    $network_tax = 'networks';
                    $networks = $this->GetNetworkDetails($network_id)->response;
                    $network_description = $networks->headquarters.' '.$networks->homepage;
                    $network_image = "https://image.tmdb.org/t/p/w500" . @$movie->networks[0]->logo_path;
                    if ( ! $term = term_exists( $network_name, $network_tax ) ) {
                        $term = wp_insert_term( $network_name, $network_tax, array(
                    'description' => $network_description,
                    ) );
                    }
                    if ( $term && ! is_wp_error( $term ) ) {
                    wp_set_post_terms( $POST_ID, $network_name, $network_tax );
                    update_option('_category_image'.$term['term_id'],$network_image );
                    }
                    
                    
                    $this->response_json([
                        "id"=>$POST_ID,
                        "title"=>$movie->name,
                        "permalink"=>get_post_permalink($POST_ID)
                    ],true);
                }else{
                    $this->show_header_error("The tv show was not added");
                }
            }
        }else{
            header("HTTP/1.0 500 Incorrect Request: The id is not appropriate");
            exit();
        }
        echo $this->response_json($movie,true);
        exit();
    }

}