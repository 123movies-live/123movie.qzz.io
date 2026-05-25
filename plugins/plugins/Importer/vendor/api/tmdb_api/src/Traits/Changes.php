<?php

namespace TMDB\Traits;

/**
 * Description of Changes
 *
 * @author fr0zen
 */
trait Changes {
    
    /**
     * Get a list of all of the movie ids that have been changed in the past 24 hours.
     * 
     * You can query it for up to 14 days worth of changed IDs at a time with the start_date and end_date query parameters. 100 items are returned per page.
     * 
     * @param string $end_date
     * @param string $start_date
     * @param int $page
     * @return object|stdClass|json
     */
    public function GetMovieChangeList($end_date = '', $start_date = '', $page = 1){
        return $this->_get("movie/changes",[
            "end_date" => $end_date,
            "start_date" => $start_date,
            "page" => $page
        ]);
    }

    /**
     * Get a list of all of the TV show ids that have been changed in the past 24 hours.
     *
     * You can query it for up to 14 days worth of changed IDs at a time with the start_date and end_date query parameters. 100 items are returned per page.
     *
     * @param string $end_date
     * @param string $start_date
     * @param int $page
     * @return object|stdClass|json
     */
    public function GetTVChangeList($end_date = '', $start_date = '', $page = 1){
        return $this->_get("tv/changes",[
            "end_date" => $end_date,
            "start_date" => $start_date,
            "page" => $page
        ]);
    }

    /**
     * Get a list of all of the person ids that have been changed in the past 24 hours.
     *
     * You can query it for up to 14 days worth of changed IDs at a time with the start_date and end_date query parameters. 100 items are returned per page.
     *
     * @param string $end_date
     * @param string $start_date
     * @param int $page
     * @return object|stdClass|json
     */
    public function GetPersonChangeList($end_date = '', $start_date = '', $page = 1){
        return $this->_get("person/changes",[
            "end_date" => $end_date,
            "start_date" => $start_date,
            "page" => $page
        ]);
    }
    
}
