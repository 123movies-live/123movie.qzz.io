<?php



namespace TMDB\Traits;

/**
 * Description of Discover
 *
 * @author fr0zen
 */
trait Discover {

    /**
     * Discover TV shows by different types of data like average rating, number of votes, genres, the network they aired on and air dates.
     *
     * Discover also supports a nice list of sort options. See below for all of the available options.
     *
     * Also note that a number of filters support being comma (,) or pipe (|) separated. Comma's are treated like an AND and query while pipe's are an OR.
     *
     * @param string $language
     * Specify a language to query translatable fields with.
     * minLength: 2
     * pattern: ([a-z]{2})-([A-Z]{2})
     * default: en-US
     * @param string $sort_by
     * Choose from one of the many available sort options.
     * Allowed Values: , vote_average.desc, vote_average.asc, first_air_date.desc, first_air_date.asc, popularity.desc, popularity.asc
     * default: popularity.desc
     * @param string $air_date_gte
     * Filter and only include TV shows that have a air date (by looking at all episodes) that is greater or equal to the specified value.
     * format: date
     * @param string $air_date_lte
     * Filter and only include TV shows that have a air date (by looking at all episodes) that is less than or equal to the specified value.
     * format: date
     * @param string $first_air_date_gte
     * Filter and only include TV shows that have a original air date that is greater or equal to the specified value. Can be used in conjunction with the "include_null_first_air_dates" filter if you want to include items with no air date.
     * format: date
     * @param string $first_air_date_lte
     * Filter and only include TV shows that have a original air date that is less than or equal to the specified value. Can be used in conjunction with the "include_null_first_air_dates" filter if you want to include items with no air date.
     * format: date
     * @param int $first_air_date_year
     * Filter and only include TV shows that have a original air date year that equal to the specified value. Can be used in conjunction with the "include_null_first_air_dates" filter if you want to include items with no air date.
     * @param int $page
     * Specify the page of results to query.
     * default: 1
     * @param string $timezone
     * Used in conjunction with the air_date.gte/lte filter to calculate the proper UTC offset.
     * default: America/New_York
     * @param int $vote_average_gte
     * Filter and only include movies that have a rating that is greater or equal to the specified value.
     * minimum: 0
     * @param int $vote_count_gte
     * Filter and only include movies that have a rating that is less than or equal to the specified value.
     * minimum: 0
     * @param string $with_genres
     * Comma separated value of genre ids that you want to include in the results.
     * @param string $without_genres
     * Comma separated value of genre ids that you want to exclude from the results.
     * @param int $with_runtime_gte
     * Filter and only include movies that have a runtime that is greater or equal to a value.
     * @param int $with_runtime_lte
     * Filter and only include movies that have a runtime that is less than or equal to a value.
     * @param bool $include_null_first_air_dates
     * Use this filter to include TV shows that don't have an air date while using any of the "first_air_date" filters.
     * default: false
     * @param string $with_original_language
     * Specify an ISO 639-1 string to filter results by their original language value.
     * @param string $without_keywords
     * Exclude items with certain keywords. You can comma and pipe seperate these values to create an 'AND' or 'OR' logic.
     * @param bool $screened_theatrically
     * Filter results to include items that have been screened theatrically.
     * default: false
     *
     * @return object|\stdClass|json
     */
    public function TVDiscover($language="en-US",$sort_by="popularity.desc",$air_date_gte="",$air_date_lte="",$first_air_date_gte=""
        ,$first_air_date_lte="",$first_air_date_year="",$page=1,$timezone="America/New_York",$vote_average_gte=0,$vote_count_gte=0
        ,$with_genres="",$without_genres="",$with_runtime_gte="",$with_runtime_lte="",$include_null_first_air_dates=false
        ,$with_original_language="",$without_keywords="",$screened_theatrically=false){
        return $this->_get("discover/tv",[
            "language"=>$language,
            "sort_by"=>$sort_by,
            "air_date.gte"=>$air_date_gte,
            "air_date.lte"=>$air_date_lte,
            "first_air_date.gte"=>$first_air_date_gte,
            "first_air_date.lte"=>$first_air_date_lte,
            "first_air_date_year"=>$first_air_date_year,
            "page"=>$page,
            "timezone"=>$timezone,
            "vote_average.gte"=>$vote_average_gte,
            "vote_count.gte"=>$vote_count_gte,
            "with_genres"=>$with_genres,
            "with_networks"=>$with_networks,
            "without_genres"=>$without_genres,
            "with_runtime.gte"=>$with_runtime_gte,
            "with_runtime.lte"=>$with_runtime_lte,
            "include_null_first_air_dates"=>$include_null_first_air_dates,
            "with_original_language"=>$with_original_language,
            "without_keywords"=>$without_keywords,
            "screened_theatrically"=>$screened_theatrically
        ]);
    }

    /**
     * Discover movies by different types of data like average rating, number of votes, genres and certifications. You can get a valid list of certifications from the certifications list method.
     *
     * Discover also supports a nice list of sort options. See below for all of the available options.
     *
     * Please note, when using certification \ certification.lte you must also specify certification_country. These two parameters work together in order to filter the results. You can only filter results with the countries we have added to our certifications list.
     *
     * If you specify the region parameter, the regional release date will be used instead of the primary release date. The date returned will be the first date based on your query (ie. if a with_release_type is specified). It's important to note the order of the release types that are used. Specifying "2|3" would return the limited theatrical release date as opposed to "3|2" which would return the theatrical date.
     *
     * Also note that a number of filters support being comma (,) or pipe (|) separated. Comma's are treated like an AND and query while pipe's are an OR.
     *
     * @param string $language
     * Specify a language to query translatable fields with.
     * minLength: 2
     * pattern: ([a-z]{2})-([A-Z]{2})
     * default: en-US
     * optional
     * @param string $region
     * Specify a ISO 3166-1 code to filter release dates. Must be uppercase.
     * @param string $sort_by
     * Choose from one of the many available sort options.
     * Allowed Values: , popularity.asc, popularity.desc, release_date.asc, release_date.desc, revenue.asc, revenue.desc, primary_release_date.asc, primary_release_date.desc, original_title.asc, original_title.desc, vote_average.asc, vote_average.desc, vote_count.asc, vote_count.desc
     * default: popularity.desc
     * @param string $certification_country
     * Used in conjunction with the certification filter, use this to specify a country with a valid certification.
     * @param string $certification
     * Filter results with a valid certification from the 'certification_country' field.
     * @param string $certification_lte
     * Filter and only include movies that have a certification that is less than or equal to the specified value.
     * @param bool $include_adult
     * A filter and include or exclude adult movies.
     * @param bool $include_video
     * A filter to include or exclude videos.
     * @param int $page
     * Specify the page of results to query.
     * minimum: 1
     * maximum: 1000
     * default: 1
     * @param int $primary_release_year
     * A filter to limit the results to a specific primary release year.
     * @param string $primary_release_date_gte
     * Filter and only include movies that have a primary release date that is greater or equal to the specified value.
     * format: date
     * @param string $primary_release_date_lte
     * Filter and only include movies that have a primary release date that is less than or equal to the specified value.
     * format: date
     * @param string $release_date_gte
     * Filter and only include movies that have a release date (looking at all release dates) that is greater or equal to the specified value.
     * format: date
     * @param string $release_date_lte
     * Filter and only include movies that have a release date (looking at all release dates) that is less than or equal to the specified value.
     * format: date
     * @param int $vote_count_gte
     * Filter and only include movies that have a vote count that is greater or equal to the specified value.
     * minimum: 0
     * @param int $vote_count_lte
     * Filter and only include movies that have a vote count that is less than or equal to the specified value.
     * minimum: 1
     * @param int $vote_average_gte
     * Filter and only include movies that have a rating that is greater or equal to the specified value.
     * minimum: 0
     * @param int $vote_average_lte
     * Filter and only include movies that have a rating that is less than or equal to the specified value.
     * minimum: 0
     * @param string $with_cast
     * A comma separated list of person ID's. Only include movies that have one of the ID's added as an actor.
     * @param string $with_crew
     * A comma separated list of person ID's. Only include movies that have one of the ID's added as a crew member.
     * @param string $with_companies
     * A comma separated list of production company ID's. Only include movies that have one of the ID's added as a production company.
     * @param string $with_genres
     * Comma separated value of genre ids that you want to include in the results.
     * @param string $with_keywords
     * A comma separated list of keyword ID's. Only include movies that have one of the ID's added as a keyword.
     * @param string $with_people
     * A comma separated list of person ID's. Only include movies that have one of the ID's added as a either a actor or a crew member.
     * @param int $year
     * A filter to limit the results to a specific year (looking at all release dates).
     * @param string $without_genres
     * Comma separated value of genre ids that you want to exclude from the results.
     * @param int $with_runtime_gte
     * Filter and only include movies that have a runtime that is greater or equal to a value.
     * @param int $with_runtime_lte
     * Filter and only include movies that have a runtime that is less than or equal to a value.
     * @param int $with_release_type
     * Specify a comma (AND) or pipe (OR) separated value to filter release types by. These release types map to the same values found on the movie release date method.
     * minimum: 1
     * maximum: 6
     * @param string $with_original_language
     * Specify an ISO 639-1 string to filter results by their original language value.
     * @param string $without_keywords
     * Exclude items with certain keywords. You can comma and pipe seperate these values to create an 'AND' or 'OR' logic.
     * @return object|\stdClass|json
     */
    public function MovieDiscover($language="en-US",$region="",$sort_by="popularity.desc",
                                  $certification_country="",$certification="",$certification_lte="",$include_adult=false,
                                  $include_video=false,$page=1,$primary_release_year=0,$primary_release_date_gte="",
                                  $primary_release_date_lte="",$release_date_gte="",$release_date_lte="",$vote_count_gte=0,
                                  $vote_count_lte=1,$vote_average_gte=0,$vote_average_lte=0,$with_cast="",$with_crew="",
                                  $with_companies="",$with_genres="",$with_keywords="",$with_people="",$year="",$without_genres="",
                                  $with_runtime_gte="",$with_runtime_lte="",$with_release_type="",$with_original_language="",$without_keywords=""){
        return $this->_get("discover/movie",[
            "language"=>$language,
            "region"=>$region,
            "sort_by"=>$sort_by,
            "certification_country"=>$certification_country,
            "certification"=>$certification,
            "certification.lte"=>$certification_lte,
            "include_adult"=>$include_adult,
            "include_video"=>$include_video,
            "page"=>$page,
            "primary_release_year"=>$primary_release_year,
            "primary_release_date.gte"=>$primary_release_date_gte,
            "primary_release_date.lte"=>$primary_release_date_lte,
            "release_date.gte"=>$release_date_gte,
            "release_date.lte"=>$release_date_lte,
            "vote_count.gte"=>$vote_count_gte,
            "vote_count.lte"=>$vote_count_lte,
            "vote_average.gte"=>$vote_average_gte,
            "vote_average.lte"=>$vote_average_lte,
            "with_cast"=>$with_cast,
            "with_crew"=>$with_crew,
            "with_companies"=>$with_companies,
            "with_genres"=>$with_genres,
            "with_keywords"=>$with_keywords,
            "with_people"=>$with_people,
            "year"=>$year,
            "without_genres"=>$without_genres,
            "with_runtime.gte"=>$with_runtime_gte,
            "with_runtime.lte"=>$with_runtime_lte,
            "with_release_type"=>$with_release_type,
            "with_original_language"=>$with_original_language,
            "without_keywords"=>$without_keywords
        ]);
    }
}
