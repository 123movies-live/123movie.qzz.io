<?php

namespace TMDB\Traits;

/**
 * Description of Collections
 *
 * @author fr0zen
 */
trait Collections {

    /**
     * Get collection details by id, if there are no values ​​for the request made, return the error "The resource you requested could not be found".
     *
     * @param int $id
     * @param string $language
     * @return object|stdClass|json
     */
    public function GetCollectionDetails($id = 0,$language = "en-US"){
        return $this->_get("collection/".$id,[
            "language" => $language
        ]);
    }

    /**
     * Get the images for a collection by id, if there are no values ​​for the request made, return the error "The resource you requested could not be found".
     *
     * @param int $id
     * @param string $language
     * @return object|stdClass|json
     */
    public function GetCollectionImages($id = 0,$language = "en-US"){
        return $this->_get("collection/".$id."}/images",[
            "language" => $language
        ]);
    }
    
}
