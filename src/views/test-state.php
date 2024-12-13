<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <script>
        function saveToState(name, html) {
            $.post('/save-state', {
                    name: name,
                    html: html
                })
                .done((response) => {
                    console.log('Success:', response);
                })
                .fail((jqXHR, textStatus, errorThrown) => {
                    console.error('Error:', jqXHR.responseText || errorThrown);
                });
        }
        saveToState('test', '<h1>Hello World</h1>');

        function checkForState(name) {
            return $.get(`/check-state/${name}`).then((response) => {
                const parsedResponse = JSON.parse(response);
                return parsedResponse.exists;
            });
        }

        checkForState('test').then((exists) => {
            console.log(exists);
        });

        function getFromState(name) {
            return $.get(`/get-state/${name}`)
                .done((response) => {
                    return response;
                })
                .fail(() => {
                    return false;
                });
        }

        getFromState('test')
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.error(error);
            });
    </script>
    <script src="/public/script.js"></script>
</body>

</html>
