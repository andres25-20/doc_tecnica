$(document).ready(function() {
    function cargarContenido(page) {
        $.ajax({
            url: 'contenido.php', 
            type: 'GET',
            data: { page: page },
            success: function(response) {
                $('#content-container').html(response);
            },
            error: function() {
                $('#content-container').html('<p>Hubo un error al cargar el contenido.</p>');
            }
        });
    }

    $('a.nav-link').click(function(e) {
        e.preventDefault();  
        var href = $(this).attr('href');
        var page = href.split('=')[1]; 
        cargarContenido(page); 
    });


    cargarContenido('home');
});
