var genreService = {
    
    getModalLink : function(link, genre) {
        return link.replace('genre/0', 'genre/'+genre.id).replace('-slug-', genre.slug);
    },
    initGenreMenu : function(linkAjax, linkGenre)
    {
        $.ajax({
            url: linkAjax,
            context: document.body
        })
        .done(function(data) {
            var genres = JSON.parse(data);
            var html = '<a>Genres musicaux</a>';
            var htmlMobile = '';

            html = html + '<ul class="sub-menu">';
            $.each(genres, function(i, genre) {
                var link = genreService.getModalLink(linkGenre, genre);
                html = html + '<li>'+genreService.getABalise(link, genre)+'</li>';
                htmlMobile = htmlMobile + genreService.getABalise(link, genre);
            });

            html = html + '</ul>';
            $('#genresMenu').html(html);
            $('#sideGenreMenu').html(htmlMobile);
        });
    },
    initGenreMenuBoot : function(linkAjax, linkGenre)
    {
        $.ajax({
            url: linkAjax,
            context: document.body
        })
        .done(function(data) {
            var genres = JSON.parse(data);
            var html = '';
            var htmlMobile = '';

            $.each(genres, function( key, genre ) {
                var link = genreService.getModalLink(linkGenre, genre);
                html = html + genreService.getABaliseBoot(link, genre);
                htmlMobile = htmlMobile + genreService.getABalise(link, genre);
            });

            $('#dropdownMenu .dropdown-menu').append(html);
            $('#sideGenreMenu').html(htmlMobile);
        });
    },
    getABalise : function(link, genre) {
        return '<a title="'+genre.libelle+'" href="'+link+'">'+genre.libelle+'</a>';
    },
    getABaliseBoot : function(link, genre) {
        return '<a class="dropdown-item" title="'+genre.libelle+'" href="'+link+'">'+genre.libelle+'</a>';
    }   
};


