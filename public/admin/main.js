$(document).ready(function() {
    
    $('#image-form').on('submit', function(e) {
        e.preventDefault();
        
        let image = $('#fileToUpload')[0].files[0];
        let category = $('#category').val();

        var formData = new FormData(this);
        formData.append('image', image);
        formData.append('category', category);

        $.ajax({
            url: '/admin',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
            },
        })

    });

});