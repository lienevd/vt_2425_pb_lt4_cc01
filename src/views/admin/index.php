<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Uploader</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <form id="image-form" method="post" enctype="multipart/form-data">
        <label>Categorie:</label><br />
        <select id="category">
            <option value="">-- Kies Categorie --</option>
            <option value="dieren">Dieren</option>
            <option value="kunst">Kunst</option>
            <option valie="technologie">Technologie</option>
        </select><br />
        <label>Selecteer afbeelding om te uploaden:</label><br />
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
    <script src="/public/admin/main.js"></script>
</body>
</html>