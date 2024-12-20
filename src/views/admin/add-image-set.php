<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Image Set</title>
    <link rel="stylesheet" href="/public/style.css">
</head>

<body>
    <main>
        <form id="image-set-form">
            <label for="img_set">Upload Image Set:</label>
            <input type="file" name="img_set" id="img_set" accept=".zip" required>
            <br><br>
            <label for="category">Select Category:</label>
            <select name="category" id="category" required>
                <option value="">-- kies een categorie --</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit">Add</button>
        </form>
    </main>

    <script>
        document.getElementById('image-set-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('/add-img-set', {
                    method: 'POST',
                    body: formData,
                });

                const responseBody = await response.text();

                if (response.ok) {
                    alert('Success: ' + responseBody);
                } else {
                    alert('Error: ' + responseBody);
                }
            } catch (error) {
                console.error('Error submitting the form:', error);
                alert('An error occurred while submitting the form. Please try again.');
            }
        });
    </script>
</body>

</html>
