<?php

/*
 * Project's Name: TMDB_Api
 * Description: Library for managing the TMDB api
 * Programming Languages: PHP
 * Programmer: fr0zen
 * File Created: 14/01/2018, 09:54:25 PM
 * File Name: Find.php
 * Email: moviewordpress.com@gmail.com
 * 
 * Copyright @2020 fr0zen.
 */

namespace TMDB\Traits;

/**
 * Description of Find
 *
 * @author fr0zen
 */
trait Find {

    /**
     * The find method makes it easy to search for objects in our database by an external id. For instance, an IMDB ID.
     *
     * This method will search all objects (movies, TV shows and people) and return the results in a single response.
     *
     * The supported external sources for each object are as follows.
     *
     *                Movies    TV Shows	TV Seasons	TV Episodes	  People
     *
     * IMDB ID	        ✓	        ✓	        ✗   	    ✓	        ✓
     *
     * Freebase MID	    ✗	        ✓	        ✓	        ✓	        ✓
     *
     * Freebase ID	    ✗	        ✓	        ✓	        ✓       	✓
     *
     * TVDB ID	        ✗	        ✓	        ✓	        ✓	        ✗
     *
     * TVRage ID	    ✗	        ✓	        ✓	        ✓	        ✓
     *
     * @param string $external_id
     * External id
     * @param string $language
     * Pass a ISO 639-1 value to display translated data for the fields that support it.
     * minLength: 2
     * pattern: ([a-z]{2})-([A-Z]{2})
     * default: en-US
     * @param string $external_source
     * Allowed Values: imdb_id, freebase_mid, freebase_id, tvdb_id, tvrage_id
     *
     * @return object|\stdClass|json
     */
    public function FindbyID($external_id="",$language="en-US",$external_source="imdb_id"){
        return $this->_get("find/".$external_id,[
            "language"=>$language,
            "external_source"=>$external_source
        ]);
    }
}
