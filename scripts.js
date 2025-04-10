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

    $('a.dropdown-item').click(function(e) {
        e.preventDefault();  
        var page = $(this).attr('href').split('=')[1]; 
        cargarContenido(page); 
    });


    cargarContenido('home');
});
