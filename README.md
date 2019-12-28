# sharex-uploader
A ShareX custom uploader made in PHP  
made with PDO.

# Setup
- Create the database
- Edit the config.php with your credentials and config
- Create a table in db called "sharex"
- In that table create a row called UserPassword
- Insert a value in that row, **this will be the token**.
- Go to your ShareX Custom Uploader Settings (located in Destinations)
- Create a new one
* Method: POST
* URL: http://your-web.site/upload.php (this one must be the same as the url in config.php)
* Body: Form Data (multipart/form-data)
* File form name: x
* Body name: token
* Body value: token you put in UserPassword
* Destination Type: Image Uploader
- Done, **select it as your Image Uploader in Destinations**




