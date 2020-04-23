var searchService = {
    getSearchArtists : function (artistSearchPath, homepath, term) {
        var loading = $('#searchBar_modal .search_loading');
        loading.show();
        $.ajax({
            method: "POST",
            url: artistSearchPath,
            context: document.body,
            data: {term : term, maxResult : 10}
        })
        .done(function(data) {
            var artists = JSON.parse(data);
            var html = '';

            $.each(artists, function( key, artist ) {
                html = html +
                "<div class='resultLine'><a href='" + homepath + "artiste/" + artist.id + "/" + artist.slug + "'><span>" + 
                artist.libelle + "</span><button>Plus d'infos</button></a></div>";
            });

            $('#search_result .artistResult').html(html);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            $('#search_result .artistResult').html('');
        })
        .always(function (jqXHR, textStatus, errorThrow) {
            loading.hide();
        });
    },    
    getSearchVideos : function (videoSearchPath, homepath, term) {
        var loading = $('#searchBar_modal .search_loading');
        loading.show();
        $.ajax({
            method: "POST",
            url: videoSearchPath,
            context: document.body,
            data: {term : term, maxResult : 10}
        })
        .done(function(data) {
            var videos = JSON.parse(data);
            var html = '';

            $.each(videos, function( key, video ) {
                html = html +
                    "<div class='resultLine'><a href='" + homepath + "video/" + video.id + "/" + video.slug + "'><span><span class='red'>" + 
                    video.artistes[0].libelle + "</span> - " + video.titre + 
                    "</span><button>Plus d'infos</button></a></div>";
            });
            
            $('#search_result .videoResult').html(html);
        })
        .fail(function (jqXHR, textStatus, errorThrown) { 
            $('#search_result .videoResult').html('');
        })
        .always(function (jqXHR, textStatus, errorThrow) {
            loading.hide();
        });
    }
};