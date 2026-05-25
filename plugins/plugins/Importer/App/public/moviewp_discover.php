<?php $randomDate = random_int(2000, 2023); ?>
<div class="wrap">
  <h1 class="wp-heading-inline">Movie Importer <span>(Discover)</span></h1>
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

      #submitdiv>h2 {
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

      #tags>div>a {
        color: #212529 !important;
        transition: none !important;
        box-shadow: none !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      #tags>div>a:hover {
        color: #212529 !important;
        transition: none !important;
        outline: 0 !important;
        box-shadow: none !important;
      }

      #tags>div>a:focus {
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

        margin-top: -1px;
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

      #reset:focus {
        outline: 0 !important;
        box-shadow: none !important;
      }

      .number-input input[type="number"] {
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
        appearance: textfield;
      }

      .number-input input[type=number]::-webkit-inner-spin-button,
      .number-input input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
      }

      .number-input {
        display: flex;
        justify-content: space-around;
        align-items: center;
      }

      .number-input button {
        -webkit-appearance: none;
        background-color: transparent;
        border: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin: 0;
        position: relative;
      }

      .number-input button:before,
      .number-input button:after {
        display: inline-block;
        position: absolute;
        content: '';
        height: 2px;
        transform: translate(-50%, -50%);
      }

      .number-input button.plus:after {
        transform: translate(-50%, -50%) rotate(90deg);
      }

      .number-input input[type=number] {
        text-align: center;
      }

      .number-input.number-input {
        border: 1px solid #ced4da;
        width: 10rem;
        border-radius: .25rem;
      }

      .number-input.number-input button {
        width: 2.6rem;
        height: .7rem;
      }

      .number-input.number-input button.minus {
        padding-left: 10px;
      }

      .number-input.number-input button:before,
      .number-input.number-input button:after {
        width: .8rem;
        background-color: #495057;
      }

      .number-input.number-input input[type=number] {
        max-width: 4rem;
        padding: .5rem;
        border: 1px solid #ced4da;
        border-width: 0 1px;
        font-size: 1rem;
        height: 2rem;
        color: #495057;
      }

      .number-input.number-input button:focus {
        outline: 0 !important;
      }

      @media not all and (min-resolution:.001dpcm) {
        @supports (-webkit-appearance: none) and (stroke-color:transparent) {

          .number-input.def-number-input.safari_only button:before,
          .number-input.def-number-input.safari_only button:after {
            margin-top: -.3rem;
          }
        }
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
  color:#303233!important;
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
                  <a class="nav-link" href="admin.php?page=moviewp_movie">Search</a>
                </li>
                <li class="nav-item">
                  <a id="discover" class="nav-link active" href="admin.php?page=moviewp_discover">Discover</a>
                </li>
              </ul>
      <div id="postbox-container-1" class="postbox-container">
        <div id="side-sortables" class="meta-box-sortables ui-sortable ui-sortable-disabled">
          <div id="submitdiv" class="postbox">
            <h2><span class="type">Movie Discover</span></h2>
            <div class="inside">
              <form data-view="tmdb_result" data-callback="show_tmdb_search" data-method="post" id="tmdb_search" class="moviewp_form">
                <div class="submitbox" id="submitpost">
                  <div id="minor-publishing">
                    <div id="minor-publishing-actions" style="text-align: left">
                      <!--<label class="font-weight-normal mtt">Movie Title</label>-->
                      <input type="hidden" name="page" id="page" value="1">
                      <input type="hidden" value="Discover" name="action">
                      <!-- Language -->
                      <label style="display:none;" class="mtt">Language</label>
                      <select style="display:none;" name="language" id="language" style="width: 100%">
                        <option value="<?php echo apilanguage; ?>" selected>Default</option>
                      </select>
                      <!-- Sort by -->
                      <!--<fieldset>
                        <input type="number" id="primary_release_year" min="1900" max="2023" name="primary_release_year" value="2020" placeholder="primary_release_year" style="width: 100%">
                      </fieldset>
                      <p></p>-->
                      <div class="def-number-input number-input safari_only" style="width: 100%">
                        <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></button>
                        <input class="quantity" id="primary_release_year" min="1900" max="2023" name="primary_release_year" value="<?php echo $randomDate; ?>" type="number">
                        <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                      </div>
                      <p></p>
                      <p><select name="sort_by" id="sort_by" style="width: 100%">
                          <option value="popularity.desc" selected>Popularity desc</option>
                          <option value="popularity.asc">Popularity asc</option>
                          <option value="revenue.desc">Revenue desc</option>
                          <option value="revenue.asc">Revenue asc</option>
                          <option value="release_date.desc">Release date desc</option>
                          <option value="release_date.asc">Release date asc</option>
                          <option value="vote_average.desc">Vote average desc</option>
                          <option value="vote_average.asc">Vote average asc</option>
                          <option value="vote_count.desc">Vote count desc</option>
                          <option value="vote_count.asc">Vote count asc</option>

                      </p></select>
                      <p><select id="with_genres" name="with_genres" style="width: 100%">
                          <option value="">All genres</option>
                          <option value="28">Action</option>
                          <option value="12">Adventure</option>
                          <option value="16">Animation</option>
                          <option value="35">Comedy</option>
                          <option value="80">Crime</option>
                          <option value="99">Documentary</option>
                          <option value="18">Drama</option>
                          <option value="10751">Family</option>
                          <option value="14">Fantasy</option>
                          <option value="36">History</option>
                          <option value="27">Horror</option>
                          <option value="10402">Music</option>
                          <option value="9648">Mystery</option>
                          <option value="10749">Romance</option>
                          <option value="878">Science Fiction</option>
                          <option value="10770">TV Movie</option>
                          <option value="53">Thriller</option>
                          <option value="10752">War</option>
                          <option value="37">Western</option>
                      </p></select>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div id="major-publishing-actions">
                    <button style="float:left;border:none!important;" type="button" id="reset" name="reset" class="button button-reset button-large btn btn-danger"><i class="fas fa-undo"></i></button>
                    <div id="publishing-action">
                      <span class="spinner"></span>
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
<script>var randomYear="<?php echo $randomDate; ?>";</script>