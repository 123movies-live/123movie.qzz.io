<?php


namespace TMDB\Traits;


trait Certifications {
    
    /**
     * Get an up to date list of the officially supported movie certifications on TMDb.
     * 
     * @return object|stdClass|json
     */
    public function GetMovieCertifications(){
        return $this->_get("certification/movie/list");
    }
    
    /**
     * Get an up to date list of the officially supported TV show certifications on TMDb.
     * 
     * @return object|stdClass|json
     */
    public function GetTVCertifications(){
        return $this->_get("certification/tv/list");
    }
    
}
