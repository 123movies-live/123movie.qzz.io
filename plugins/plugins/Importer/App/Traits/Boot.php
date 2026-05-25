<?php


namespace App\Traits;

/**
 * Description of Boot
 *
 * @author fr0zen
 */
trait Boot {

    private $configuration = null;

    public function AddMovieLinks(){
        $movie_id = @$_POST['post_id'];
        $post = get_post($movie_id);
        if(gettype($post) == null || gettype($post) == "NULL"){
            $this->show_header_error("The id of the post does not exist in the system");
        }else{
            $web_name = $this->getClassName($_POST['link_boot']);
            if(in_array($web_name,get_class_methods($this))){

            }else{
                $this->show_header_error('Sorry, we do not have support for this web.');
            }
        }
        exit();
    }
	//movies
    public function SearchMovie(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->SearchMovies(@$_POST['movie_title'],@$_POST['language'],@$_POST['page'],@$_POST['content_adult'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){

					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
	
    public function Discover(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->Disco(@$_POST['language'],@$_POST['sort_by'],@$_POST['primary_release_year'],@$_POST['with_genres'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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

    public function Populars(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->MoviesPopular(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
    public function Rated(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->MoviesTopRated(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
    public function Upcoming(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->MoviesUpcoming(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
    public function Playing(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->MoviesNowPlaying(@$_POST['language'],@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
    public function Trending(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->MoviesTrending(@$_POST['language'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
    public function Animation(){
        $this->configuration = $this->GetAPIConfiguration()->response;
        echo header('Content-Type: application/json');
        $movies = @$this->MovieAnime(@$_POST['language'],$sort_by="popularity.desc",$with_genres="16",$with_keywords="210024",$with_original_language="ja",@$_POST['page'])->response;
        if(@$movies->total_results > 0){
            $result = array();
            foreach (@$movies->results as $index => $value){
                if($value->poster_path!=''){
					@$result[$index]->title = str_replace("'", " ", $value->title);
					@$result[$index]->title = str_replace('"', ' ', $value->title);
					@$result[$index]->post_slug = ''.get_site_url().'/'.sanitize_title( @$result[$index]->title);
                    @$result[$index]->id = $value->id;
                    @$result[$index]->poster = @$this->configuration->images->secure_base_url.$this->configuration->images->poster_sizes[1].$value->poster_path;
                    @$result[$index]->release_date = @explode("-",@$value->release_date)[0];
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
    public function AddMovie(){
        $movie_id = @$_POST['tmdb_movie_id'];
        $movie = $this->GetMovieDetails($movie_id,@$_POST['tmdb_language'])->response;
        if(@$movie->id == @$_POST['tmdb_movie_id']){
            $this->configuration = $this->GetAPIConfiguration()->response;
            $genres = array();
            foreach ($movie->genres as $index => $value){
                if(in_array($_POST['tmdb_language'],["en-US","fr-FR","es-ES","it-IT"])){
                    //$value->name = str_replace(array("Suspense"),array("Thriller"),$value->name);
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

            if($this->get_post_by_slug("name",sanitize_title($movie->title)) || $this->get_post_by_slug("title",$movie->title)){
                $this->show_header_error("The title '".@$movie->title."' already exists");
            }else{
                $new_post = [
                    "post_title"=>$movie->title,
                    "post_name"=>sanitize_title($movie->title),
                    "post_excerpt"=>$movie->overview,
                    "post_content" => $movie -> overview,
                    "post_category"=>$genres,
                    "post_status"=>"publish"
                ];

                $POST_ID = wp_insert_post($new_post);
                if(@$POST_ID){
                    $stars = $director = $cast = array();
                    /**
                     * We look for the cast of the movie and store it in a variable
                     */
                    $credits = $this->GetMovieCast($movie_id)->response;
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
                     * We look for the directors of the movie and store them in a variable
                     */
                    $credits = $this->GetMovieCrew($movie_id)->response;
                    if(sizeof($credits->crew)>0){
                        foreach ($credits->crew as $index => $crew){
                            if($crew->job == 'Director'){
                                @$director[$index]=$crew->name;
                            }
                        }
                        $director = implode(",",$director);
                    }
                    /**
                     * We retrieve the keywords
                     */
                    $keywords = $this->GetMovieKeywords($movie_id)->response;
                    if(sizeof($keywords->keywords)>0){
                        $arr=array();
                        foreach ($keywords->keywords as $index => $value){
                                @$arr[$index] = @$value->name;
							
                        }
                        
                        $keywords=$arr;
						wp_set_post_tags($POST_ID,$keywords);
                    } 
                    /**
                     * We retrieve the Movie MPAA Certification
                     */
                    $releases = $this->GetMovieCertification($movie_id)->response;
                    if(sizeof($releases->countries)>0){
                        foreach ($releases->countries as $index => $countries){
                            if($countries->iso_3166_1 == 'US'){
                                @$certification[$index]=$countries->certification;
								add_post_meta($POST_ID,"Rated",@$certification[$index]=$countries->certification);
                            }
                        }
                    }

                    /**
                     * We retrieve the trailers of the searched movie and store them in a variable
                     */
                    $trailers = $this->GetMovieVideos($movie_id,apilanguage)->response;
                    if(sizeof($trailers->results)>0){
                        $arr=array();
                        foreach ($trailers->results as $index => $value){
                                @$arr[$index] = @$value->key;
							/**
                            if(@$value->site == 'YouTube' && @$value->type == 'Trailer'){
                                @$arr[$index] = @$value->key;
                            }
							*/
                        }
                        $trailers="".@$arr[0]."";
						if($trailers == ''){
                        $trailers = "";
                        }
                        } else {
					    $trailers = "";
                        }
                    /**
                     * We retrieve the images of the searched movie and store it in a variable
                     */
                    $images = @$this->GetMovieBackdrops($movie_id)->response;
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
                    add_post_meta($POST_ID,"imdb_id",trim($movie->imdb_id));
                    add_post_meta($POST_ID,"id",trim($movie->id));
                    add_post_meta($POST_ID,"Runtime",trim($movie->runtime)." min");
                    add_post_meta($POST_ID,"Title",trim($movie->title));
                    add_post_meta($POST_ID,"imdbRating",$vote_average);
                    add_post_meta($POST_ID,"vote_average",$vote_average);
                    add_post_meta($POST_ID,"vote_count",trim($movie->vote_count));
                    add_post_meta($POST_ID,"popularity",trim($movie->popularity));
                    add_post_meta($POST_ID,"overview",trim($movie->overview));
                    add_post_meta($POST_ID,"poster_path",trim($movie->poster_path));
                    add_post_meta($POST_ID,"tagline",trim($movie->tagline));
                    add_post_meta($POST_ID,"backdrop_path",trim($movie->backdrop_path));
                    add_post_meta($POST_ID,"youtube_id",$trailers);
                    add_post_meta($POST_ID,"original_title",trim($movie->original_title));
                    add_post_meta($POST_ID,"original_language",trim($movie->original_language));
                    add_post_meta($POST_ID,"production_companies",trim($movie->production_companies[0]->name));
                    add_post_meta($POST_ID,"homepage",trim($movie->homepage));
                    add_post_meta($POST_ID,"status",trim($movie->status));
                    add_post_meta($POST_ID,"budget",trim($movie->budget));
                    add_post_meta($POST_ID,"revenue",trim($movie->revenue));
					$country = @$movie->production_countries[0]->name;
					$country = str_replace(array("United Kingdom"),array("UK"),$country);
					$country = str_replace(array("United States of America"),array("USA"),$country);
                    wp_set_post_terms($POST_ID,$stars,"actors");
                    wp_set_post_terms($POST_ID,$director,"director");
                    wp_set_post_terms($POST_ID,@explode("-",@$movie->release_date)[0], "years");
                    add_post_meta($POST_ID,"release_date",trim(@explode("-",@$movie->release_date)[0]));
					wp_set_post_terms($POST_ID,$country,"country");
                    wp_set_post_terms($POST_ID,$genres,"category");
                    $category_movies = get_cat_ID( 'Movies' );
                    wp_set_post_categories( $POST_ID, $category_movies, 'category');
                    //collection
                    $collection_name = $movie->belongs_to_collection->name;
                    $collection_id = $movie->belongs_to_collection->id;
                    $collection_tax = 'collection';
                    $collections = $this->GetCollectionDetails($collection_id,apilanguage)->response;
                    $collection_description = $collections->overview;
                    $collection_image = "https://image.tmdb.org/t/p/w220_and_h330_face" . $movie->belongs_to_collection->poster_path;
                    if ( ! $term = term_exists( $collection_name, $collection_tax ) ) {
                    $term = wp_insert_term( $collection_name, $collection_tax, array(
                    'description' => ''.$collection_description.'',
                    ) );
                    }
                    if ( $term && ! is_wp_error( $term ) ) {
                    wp_set_post_terms( $POST_ID, $collection_name, $collection_tax );
                    update_option('_category_image'.$term['term_id'],$collection_image );
                    }
                    $this->response_json([
                        "id"=>$POST_ID,
                        "title"=>$movie->title,
                        "permalink"=>get_post_permalink($POST_ID)
                    ],true);
                }else{
                    $this->show_header_error("The movie was not added");
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