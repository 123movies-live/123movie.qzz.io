<div class="wrap">
    <h1 class="wp-heading-inline">Movie Importer <span>(Search)</span></h1>
    <span class="api"><i class="fa fa-circle flashit"></i>&nbsp;&nbsp;API is online</span>
    <hr class="wp-header-end">
    <div id="poststuff">
<style>
body {
  background: #f1f1f1 !important;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif !important;
  color: #444;
  font-size: 14px !important;
  line-height: 1.4em !important;
}

#submitdiv > h2 {
  font-weight: 600 !important;
}

.img-thumbnail {
  padding: 4px;
  background: none !important;
  border: none !important;
  min-width: 132px;
  max-height: 195px !important;
  min-height: 195px;
  width: 132px;
  cursor: pointer;
  margin-right: 3px;
  margin-bottom: 5px;
  display: inline-block;
  opacity: 1;
}

.img-thumbnail.added {
  opacity: 0.1;
  background: none !important;
}
.img-thumbnail.added:hover {
  opacity: 0.2;
  background: #000 !important;
}

.img-thumbnail:hover {
  opacity: 0.9;
  background: #000 !important;
  filter: saturate(140%);
  transition-duration: 0.14s;
}

.placeholder {
  background: #f1f1f1 !important;
  border-radius: 0.25rem;
  border: none !important;
  min-width: 132px;
  max-height: 195px;
  width: 132px;
  margin-right: 3px;
  margin-bottom: 5px;
  display: inline-block;
  opacity: 1;
}

#major-publishing-actions {
  background: #fff;
}

.postbox #post-body {
  border: none;
}

.tmdb_result {
  margin-bottom: 40px;
}

.back {
  position: absolute;
  left: 5px;
  margin-top: 30px;
}

.next {
  position: absolute;
  right: 5px;
  margin-top: 30px;
}

.button {
  color: #fff !important;
}

.chip {
  display: inline-block;
  height: 32px;
  padding: 0 12px;
  margin-right: 1rem;
  margin-bottom: 1rem;
  font-size: 13px;
  font-weight: 500;
  line-height: 32px;
  cursor: pointer;
  background-color: #c6c9cc;
  border-radius: 16px;
  -webkit-transition: all 0.3s linear;
  transition: all 0.3s linear;
}

.chip img {
  float: left;
  width: 32px;
  height: 32px;
  margin: 0 8px 0 -12px;
  border-radius: 50%;
}

#tags > div > a {
  color: #212529 !important;
  transition: none !important;
  box-shadow: none !important;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

#tags > div > a:hover {
  color: #212529 !important;
  transition: none !important;
  outline: 0 !important;
  box-shadow: none !important;
}

#tags > div > a:focus {
  border: none !important;
  outline: none !important;
  transition: none !important;
  box-shadow: none !important;
}

.foot {
  position: fixed;
  bottom: 0;
  width: 100%;
}

#wpfooter {
  display: none !important;
}

.importer-logs {
  float: left;
  width: 100%;
  border: 1px solid #e5e5e5;
  border-top: 0;
  border-bottom: 0;
}

.importer-logs .box {
  float: left;
  width: 100%;
  max-height: 165px;
  overflow-y: scroll;
  background: #fff;
  padding: 10px 0;
  box-shadow: 0 10px 5px -7px rgba(0, 0, 0, 0.09);
}

.importer-logs .box ul {
  float: left;
  width: 100%;
  color: #676a6d;
  list-style: none;
  margin-top: 0;
}

.importer-logs .box ul li {
  font-size: 13px;
  border-bottom: dashed 1px #eaeaea;
  margin-bottom: 0;
  padding: 10px 20px;
}

.fadein {
  -webkit-animation: fadein 1s;
  -moz-animation: fadein 1s;
  -ms-animation: fadein 1s;
  -o-animation: fadein 1s;
  animation: fadein 1s;
}

.importer-logs .box ul li span.type {
  float: left;
  margin-right: 20px;
  text-transform: uppercase;
  font-size: 10px;
  font-weight: 700;
}

.importer-logs .box ul li span.edit {
  float: left;
  margin-right: 20px;
}
.importer-logs .box ul li span.edit a {
  border: solid 1px #ddd;
  padding: 2px 10px;
  border-radius: 3px;
  font-size: 11px;
  font-weight: 700;
}
.importer-logs .box ul li a {
  text-decoration: none;
  font-weight: 500;
}
.importer-logs .box ul li span.microtime {
  float: right;
  font-weight: 500;
  color: #bebebe;
}
.btn-secondary.disabled,
.btn-secondary:disabled {
  color: #fff;
  background-color: #c3a9d0 !important;
  border-color: #6c757d;
  min-height: 32px;
  line-height: 2.30769231;
  padding: 0 12px;
}
.btn-floating {
  float: left;
  right: -45px;
  display: inline-block;
  color: #fff;
  background-color: #c3a9d0 !important;
  border-color: #6c757d;
  min-height: 32px;
  line-height: 2.30769231;
  padding: 0 12px;
  margin: 0.375rem;
  color: inherit;
  text-transform: uppercase;
  word-wrap: break-word;
  white-space: normal;
  cursor: pointer;
  border: 0;
  border-radius: 0.125rem;
  -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16),
    0 2px 10px 0 rgba(0, 0, 0, 0.12);
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  -webkit-transition: color 0.15s ease-in-out,
    background-color 0.15s ease-in-out, border-color 0.15s ease-in-out,
    -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out,
    -webkit-box-shadow 0.15s ease-in-out;
  padding: 0.84rem 2.14rem;

  margin-top: -0.5px;
}
.btn-floating:hover,
.btn-floating:focus,
.btn-floating:active {
  outline: 0;
  -webkit-box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18),
    0 4px 15px 0 rgba(0, 0, 0, 0.15);
  box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 4px 15px 0 rgba(0, 0, 0, 0.15);
}
select option[disabled] {
  display: none !important;
}
.nav-tabs {
  border-bottom: 1px solid #ccd0d4 !important;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
}
.postbox.results {
  border-top: 0 !important;
}
.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active {
  border-color: #ccd0d4 #ccd0d4 #fff !important;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
  color: #303233 !important;
  font-weight: 600 !important;
}
a.nav-link:focus {
  border: 1px solid transparent !important;
  outline: none;
  box-shadow: 0;
}
.nav-tabs a:focus {
  border: 1px solid transparent !important;
  outline: none;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;
}
#post-body.columns-2 #side-sortables {
  min-height: initial;
  height: auto;
}
.total{
  float: right;
  color: #bbb;
  font-size: 12px;
  margin-right: 35px;
  margin-top:5px;
}
.flashit {
    -webkit-animation: flash linear .9s infinite;
    animation: flash linear .9s infinite;
    font-size: 20px;
    color: #8be71f;
}
@-webkit-keyframes flash {
    0% {
        opacity: 1
    }

    50% {
        opacity: .1
    }

    100% {
        opacity: 1
    }
}

@keyframes flash {
    0% {
        opacity: 1
    }

    50% {
        opacity: .1
    }

    100% {
        opacity: 1
    }
}
.api {
  color:#5d5d5d;
  font-size:13px;
  font-weight: 600 !important;
  line-height:1.4;
  float:right;
  position:relative;
  margin-right:20px;
  margin-top:15px;
}
.api > i {
  font-size:13px;
}
</style>
        <div id="post-body" class="metabox-holder columns-2">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="admin.php?page=moviewp_movie">Search</a>
                </li>
                <li class="nav-item">
                    <a id="discover" class="nav-link" href="admin.php?page=moviewp_discover">Discover</a>
                </li>
            </ul>
            <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable ui-sortable-disabled">
                    <div id="submitdiv" class="postbox">
                        <h2><span class="type">Movie Search</span></h2>
                        <div class="inside">
                            <form data-view="tmdb_result" data-callback="show_tmdb_search" data-method="post" id="tmdb_search" class="moviewp_form">
                                <div class="submitbox" id="submitpost">
                                    <div id="minor-publishing">
                                        <div id="minor-publishing-actions" style="text-align: left">
                                            <!--<label class="font-weight-normal mtt">Movie Title</label>-->
                                            <input type="hidden" name="page" id="page" value="1">
                                            <input type="hidden" id="switchvalue" value="SearchMovie" name="action">
                                            <input class="font-weight-normal" type="text" required autofocus name="movie_title" id="movie_title" style="min-width: 100%; text-align: left;" placeholder="Enter Movie title">
                                            <!-- Language -->
                                            <label style="display:none;" class="mtt">Language</label>
                                            <select style="display:none;" name="language" id="language" style="width: 100%">
                                                <option value="<?php echo apilanguage; ?>" selected>Default</option>
                                            </select>
                                            <!-- Sort by -->
                                            
                                            <p><select class="sortby" name="action" id="switchvalue" style="display: none;width: 100%">
                                                <option value="SearchMovie" selected>Search</option>
                                                <option value="Populars">Popular</option>
                                                <option value="Rated">Top Rated</option>
                                                <option value="Upcoming">Upcoming</option>
                                                <option value="Playing">Now Playing</option>
                                                <option value="Animation">Anime</option>
                                                <!--<option value="Trending">Trending</option>-->
                                            </p></select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div id="major-publishing-actions">
                                        <!-- switch -->
                                        <div style="float:left;" class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches">
                                            <label class="custom-control-label" for="customSwitches">Search</label>
                                        </div>
                                        <div id="publishing-action">
                                            <span class="spinner"></span>
                                            <!--<a class="btn-floating btn-sm btn-secondary"><b style="color:white;">Import</b></a>-->
                                            <button type="button" id="publish" class="button button-cancel button-large btn btn-primary" style="display: none;">New Search</button>
                                            <button type="submit" id="publish" class="button button-primary button-large btn btn-primary">Search</button>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </form>
                            <form data-callback="show_movie_add" data-method="post" id="tmdb_movie_add" class="moviewp_form" data-view="tmdb_api">
                                <input type="hidden" value="AddMovie" name="action" required>
                                <input type="hidden" value="" id="tmdb_movie_id" name="tmdb_movie_id" required>
                                <input type="hidden" name="tmdb_language" id="tmdb_language" value="en-US">
                                <div class="tmdb_api" style="align-t"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <p class="hide-if-no-js"><button type="button" class="button-link" id="bulk-import" aria-expanded="false" style="display: none;">Bulk import</button></p>
                <!--<button type="button" class="btn btn-secondary btn-sm" id="bulk-import" style="display: none;width:98%;">Bulk import</button>-->
                <div id="tags"></div>
            </div>
            <!--<div class="importer-logs">
                <div id="importer-logs-box" class="box">
                <ul>
                </ul>
                </div>
            </div>-->
            <div class="postbox-container" id="post-body">
                <div id="submitdiv" class="postbox results">
                    <h2 style="text-align: center"><span>Results: <span class="result_page"></span></span><p class="total"></p></h2>
                    <div class="inside">
                        <div class="submitbox" id="submitpost">
                            <div id="minor-publishing" class="tmdb_result" style="padding: 15px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>