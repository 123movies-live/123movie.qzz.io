<?php


namespace TMDB\Traits;

/**
 * Description of Jobs
 *
 * @author fr0zen
 */
trait Jobs {

    /**
     * The the list of official jobs that are used on TMDb.
     *
     * @return object|\stdClass|json
     */
    public function GetJobs(){
        return $this->_get("job/list");
    }
}
