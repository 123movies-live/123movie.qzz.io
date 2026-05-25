<?php



namespace TMDB\Traits;

/**
 * Description of Credits
 *
 * @author fr0zen
 */
trait Credits {

    /**
     * Get a movie or TV credit details by id.
     *
     * Get the detailed information about a particular credit record. This is currently only supported with the new credit model found in TV. These ids can be found from any TV credit response as well as the tv_credits and combined_credits methods for people.The episodes object returns a list of episodes and are generally going to be guest stars. The season array will return a list of season numbers. Season credits are credits that were marked with the "add to every season" option in the editing interface and are assumed to be "season regulars".
     *
     * @param string $id
     * Movie id or Show TV id credit, Example: 5256c8b219c2956ff6047cd8
     * @return object|\stdClass|json
     */
    public function GetCreditDetails($id=""){
        return $this->_get("credit/".$id);
    }
}
