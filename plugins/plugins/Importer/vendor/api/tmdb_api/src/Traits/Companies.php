<?php

namespace TMDB\Traits;

/**
 * Description of Companies
 *
 * @author fr0zen
 */
trait Companies {

    /**
     * Get a companies details by id.
     *
     * @param int $id
     * Number id of company
     * @return object|\stdClass|json
     */
    public function GetCompanyDetails($id = 0) {
        return $this->_get("company/".$id);
    }

    /**
     * Get the movies of a company by id.
     *
     * @param int $id
     * Number id of company
     * @param string $language
     * Pass a ISO 639-1 value to display translated data for the fields that support it. default: en-US
     * @return object|\stdClass|json
     */
    public function GetCompanyMovies($id=0,$language="en-US"){
        return $this->_get("company/".$id."/movies",[
            "language"=>$language
        ]);
    }
}
