<?php


namespace TMDB\Traits;

/**
 * Description of Search
 *
 * @author fr0zen
 */
trait Search {

    /**
     * @param string $query
     * Pass a text query to search. This value should be URI encoded.
     * minLength: 1
     * @param string $language
     * Pass a ISO 639-1 value to display translated data for the fields that support it.
     * minLength: 2
     * pattern: ([a-z]{2})-([A-Z]{2})
     * default: en-US
     * @param int $page
     *Specify which page to query.
     * minimum: 1
     * maximum: 1000
     * default: 1
     * @param bool $include_adult
     * Choose whether to inlcude adult (pornography) content in the results.
     * default: false
     * @param string $region
     * Specify a ISO 3166-1 code to filter release dates. Must be uppercase.
     * pattern: ^[A-Z]{2}$
     * @param int $year
     * @param int $primary_release_year
     *
     * @return object|\stdClass|json
     */
	 
	// movies
    public function SearchMovies($query="",$language="en-US",$page=1,$include_adult=false){
        return $this->_get("search/movie",[
            "query"=>$query,
            "language"=>$language,
            "page"=>$page,
            "include_adult"=>$include_adult
        ]);
    }
    public function MoviesNowPlaying($language="en-US",$page=1){
        return $this->_get("movie/now_playing",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function MoviesPopular($language="en-US",$page=1){
        return $this->_get("movie/popular",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function MoviesTopRated($language="en-US",$page=1){
        return $this->_get("movie/top_rated",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function MoviesUpcoming($language="en-US",$page=1){
        return $this->_get("movie/upcoming",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function MoviesTrending($language="en-US",$page=1){
        return $this->_get("trending/movie/day",[
            "language"=>$language
        ]);
    }
    public function Disco($language="en-US",$sort_by="popularity.desc",$primary_release_year="",$with_genres="",$page=1){
        return $this->_get("discover/movie",[
            "language"=>$language,
            "sort_by"=>$sort_by,
            "primary_release_year"=>$primary_release_year,
            "with_genres"=>$with_genres,
            "page"=>$page
        ]);
    }
    public function MovieAnime($language="en-US",$sort_by="popularity.desc",$with_genres="16",$with_keywords="210024",$with_original_language="ja",$page=1){
        return $this->_get("discover/movie",[
            "language"=>$language,
            "sort_by"=>$sort_by,
            "with_genres"=>$with_genres,
            "with_keywords"=>$with_keywords,
            "with_original_language"=>$with_original_language,
            "page"=>$page
        ]);
    }
	// tv shows
    public function SearchSeries($query="",$language="en-US",$page=1,$include_adult=false,$region="",$year="",$primary_release_year=""){
        return $this->_get("search/tv",[
            "query"=>$query,
            "language"=>$language,
            "page"=>$page,
            "include_adult"=>$include_adult,
            "region"=>$region,
            "year"=>$year,
            "primary_release_year"=>$primary_release_year
        ]);
    }
    public function GetPopular($language="en-US",$page=1){
        return $this->_get("tv/popular",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function GetTopRated($language="en-US",$page=1){
        return $this->_get("tv/top_rated",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function OnTheAir($language="en-US",$page=1){
        return $this->_get("tv/on_the_air",[
            "language"=>$language,
            "page"=>$page
        ]);
    }
    public function GetAnime($language="en-US",$sort_by="popularity.desc",$with_genres="16",$with_keywords="210024",$with_original_language="ja",$page=1){
        return $this->_get("discover/tv",[
            "language"=>$language,
            "sort_by"=>$sort_by,
            "with_genres"=>$with_genres,
            "with_keywords"=>$with_keywords,
            "with_original_language"=>$with_original_language,
            "page"=>$page
        ]);
    }
    public function TVDisco($language="en-US",$sort_by="popularity.desc",$first_air_date_year="",$with_genres="",$page=1){
        return $this->_get("discover/tv",[
            "language"=>$language,
            "sort_by"=>$sort_by,
            "first_air_date_year"=>$first_air_date_year,
            "with_genres"=>$with_genres,
            "page"=>$page
        ]);
    }
}