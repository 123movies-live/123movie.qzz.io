var MOVIEWP = {
    response: null,
    __constructor: function() {
        if ($(".moviewp_form").length > 0) {
            $(".moviewp_form").submit(function() {
                MOVIEWP.CallAjax(this);
                return false;
            });
        }
    },
    CallAjax: function(form) {
        var m = $(form).data("method");
        if (m == "get" || m == "post") {
            var v = $(form).data("view");
            if ($("." + v).length > 0) {
                $("." + v).html(
                    '<center><h5><img src="data:image/gif;base64,R0lGODlhEAAQAIABAAAAAP///yH/C05FVFNDQVBFMi4wAwEAAAAh+QQJCgABACwAAAAAEAAQAAACHYyPqcvtD6M0oAJo78vYzsOFXiBW5Fhe3GmmX1AAACH5BAkKAAEALAAAAAAQABAAAAIcjI+py+0PowIUwGofvlXKDXZBSI0iaW1miXpGAQAh+QQJCgABACwAAAAAEAAQAAACH4yPacCg2txDcdJms62aZ79h2ngxAXhU6IKtZyuSTwEAIfkECQoAAQAsAAAAABAAEAAAAiCMj2nAEO0UfE1RdmOa03rbfZm4VGRIpiV2miLqwepXAAAh+QQJCgABACwAAAAAEAAQAAACHoyPacAQ7eBqj8rKcKS6XwUeX9iRU2aW6cq27guDBQAh+QQJCgABACwAAAAAEAAQAAACHoyPacAQ7eBqj8rKcKS6XwWGySeSoQmi4sq27guDBQAh+QQJCgABACwAAAAAEAAQAAACHoyPqQEN7JZ7U8aqKl68m92BnGg13lk+J5lFq4seBQAh+QQJCgABACwAAAAAEAAQAAACHoyPqQoNm9yDR9Lqrl5W9/tR4cZlo2GdQZoxZksaBQA7" /></h5></center>'
                );
                $.ajax({
                    type: m,
                    url: moviewp_ajax.url,
                    data: $(form).serializeArray(),
                    success: function(data) {
                        try {
                            $("." + v).html("");
                            var c = $(form).data("callback");
                            MOVIEWP.response = data;
                            try {
                                eval("MOVIEWP." + c + "('" + v + "')");
                            } catch (eval_error) {}
                        } catch (e) {}
                    },
                    error: function(error) {
                        $("." + v).html("");
                    }
                });
            }
        }
    },

    show_movie_add: function(view) {
        if (MOVIEWP.response.status == "Success") {}
    },
    show_tmdb_search: function(view) {
        if (MOVIEWP.response.total_results > 0) {
            $(".button-primary").hide("slow");
            $(".button-cancel").show("slow");
            $("#bulk-import").show();
            $(".total").html('<i class="fa fa-desktop" aria-hidden="true"></i> '+MOVIEWP.response.total_results+' series found.');
            $(".button-cancel").click(function() {
                $(".result_page").html("");
                $(".button-primary").show("slow");
                $(".button-cancel").hide("slow");
                $("#bulk-import").hide();
                $(".tmdb_result").html("");
                $(".total").html('');
                $("#tmdb_search #page").val(1);
                $(".tmdb_api").html("");
            });
            $(".button-reset").click(function() {
                $(".result_page").html("");
                $(".button-primary").show("slow");
                $(".button-cancel").hide("slow");
                $("#bulk-import").hide();
                $(".tmdb_result").html("");
                $(".total").html('');
                $("#with_genres").val("");
                $("#first_air_date_year").val(randomYear);
                $("#sort_by").val("popularity.desc");
                $("#tmdb_search #page").val(1);
                $(".tmdb_api").html("");
            });
            $.each(MOVIEWP.response.results, function(index, movie) {
                $("." + view).append(
                    '<div class="placeholder"><img data-tmdb_id="' +
                    movie.id +
                    '" data-toggle="tooltip" title="' +
                    movie.title +
                    " <br><b>(" +
                    movie.release_date +
                    ')</b>" data-html="true" src="' +
                    movie.poster +
                    '" alt="' +
                    movie.title +
                    '" class="img-thumbnail tmdb_result_item_' +
                    movie.id +
                    " " +
                    movie.clas +
                    '"></div>'
                );
                $('[data-toggle="tooltip"]').tooltip();
                $(".tmdb_result_item_" + movie.id).click(function() {
                    $("#tmdb_movie_id").val($(this).data("tmdb_id"));
                    $("#tmdb_language").val($("#language").val());
                    $("#tmdb_movie_add").submit();
                    $(".tmdb_result_item_" + movie.id).addClass(
                        "added animated bounceOutLeft faster"
                    );
                });
            });
            $("." + view).append('<div class="clearfix"></div>');
            if (MOVIEWP.response.page < MOVIEWP.response.total_pages) {
                $("." + view).append(
                    "<button class='btn btn-primary btn-sm float-right next'>Next</button>"
                );
                $(".next").click(function() {
                    $("#tmdb_search #page").val(MOVIEWP.response.page + 1);
                    $("#tmdb_search").submit();
                });
            }
            if (MOVIEWP.response.page > 1) {
                $("." + view).append(
                    "<button class='btn btn-primary btn-sm float-left back'>Back</button>"
                );
                $(".back").click(function() {
                    $("#tmdb_search #page").val(MOVIEWP.response.page - 1);
                    $("#tmdb_search").submit();
                });
            }
            $(".result_page").html(" Page " + MOVIEWP.response.page);
        } else {
            $("." + view).html("No results were found for your query.");
        }
    }
};

try {
    $(document).ready(function() {
        MOVIEWP.__constructor();
        $("form").on("click", 'button:not([type="submit"])', function(e) {
            e.preventDefault();
        });
        $("#bulk-import").click(function() {
           //fr0zen
            $(".img-thumbnail.none").click();
        });
        $(".button-reset").click(function() {
            $(".result_page").html("");
            $(".button-primary").show("slow");
            $(".button-cancel").hide("slow");
            $("#bulk-import").hide();
            $(".tmdb_result").html("");
            $(".total").html('');
            $("#with_genres").val("");
            $("#first_air_date_year").val(randomYear);
            $("#sort_by").val("popularity.desc");
            $("#tmdb_search #page").val(1);
            $(".tmdb_api").html("");
        });
        $("#toplevel_page_moviewp_tv, #toplevel_page_moviewp_tv > a")
            .removeClass("wp-not-current-submenu")
            .addClass("current");
    });
} catch (e) {}