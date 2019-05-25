$(function(){
    $('.select-movie').on('click', getID);
});

function getID(){
    var movie_id = $(this).attr("id");
    console.log(movie_id);
    onSelectEventChange(movie_id);
}

function onSelectEventChange(movie_id){
    $.get('api/movie/'+movie_id+'/datos', function(data){
        console.log(data);
        var html_descripcion = "";
        var html_photo = "";
            html_descripcion += '<div class="toast-body"><strong>'+data[1]+'</strong></div>';
            html_photo += '<div align="center" class="toast-photo"><img src="'+data[0]+'"></div>';
        $('.toast-body').html(html_descripcion);
        $('.toast-photo').html(html_photo);
    });
}
