var getVideos = function(path, page) {
    var page = parseInt(page, 2)+1;
    $.ajax({
        url: path+page,
        context: document.body
    })
    .done(function(html) {
        $('.column-videos #list_videos').html(html);
    })
    .fail(function() {
        console.log( "error" );
    });
};

var initPagination = function (homeAjaxPath, num_entries, page) {
    
    $("#Pagination").pagination(num_entries, {
        items_per_page:10, // Show only one item per page
        //link_to:"{{ path('spicy_site_homepage')}}__id__",
        link_to:"#",
        current_page: page,
        prev_text:"Prèc.",
        next_text:"Suiv.",
        num_edge_entries:2,
        num_display_entries:8,
        callback:function(page){
            getVideos(homeAjaxPath, page);              
        }
    });                
    
    $('#pagination-mobile').pagination(num_entries, {
        prev_text:"Prèc.",
        next_text:"Suiv.",
        num_display_entries:0,
        callback:function(page){
            getVideos(homeAjaxPath, page);              
        }
    });
};

//Tabs
if($('.tabs-container').length) {	
    $('.tabs-container').each(function() {
        var tabs=$(this);

        //show first pane
        tabs.find('.panes .pane:first-child').show();
        tabs.find('.tabs li:first-child').addClass('current');

        tabs.find('.tabs li').click(function() {
            //set active state to tab
            tabs.find('.tabs li').removeClass('current');
            $(this).addClass('current');

            //show current tab
            tabs.find('.pane').hide();
            tabs.find('.pane:eq('+$(this).index()+')').show();			
        });
    });		
    $('.tabs-container .a_tabs').click(function() {
        $(this).parent().trigger('click');
        return false;
    });
}