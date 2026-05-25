<?php


namespace TMDB\Traits;

/**
 * Description of Keywords
 *
 * @author fr0zen
 */
trait Keywords {

    /**
     * Get the basic information for a specific keyword id.
     *
     * @param int $keyword_id
     * Example: 1721
     * @return mixed
     */
    public function GetKeywordDetails($keyword_id=0){
        return $this->_get("keyword/".$keyword_id);
    }

    /**
     * Get the movies that belong to a keyword.
     * 
     * @param int $keyword_id
     * Example: 1721
     * @param string $language
     * Pass a ISO 639-1 value to display translated data for the fields that support it.
     * *minLength: 2
     * pattern: ([a-z]{2})-([A-Z]{2})
     * default: en-US
     * @param bool $include_adult
     * Choose whether to inlcude adult (pornography) content in the results.
     * default: false
     * @return mixed
     */
    public function GetKeywordMovies($keyword_id=0,$language="en-US",$include_adult=false){
        return $this->_get("keyword/".$keyword_id."/movies",[
            "language"=>$language,
            "include_adult"=>$include_adult
        ]);
    }
}
