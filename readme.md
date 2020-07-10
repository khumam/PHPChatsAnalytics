### Simple Chats Analytics
This is a simple chat analytics for whatsapp group.

### How To Use It
1. Clone this repository
2. Get the txt file from your backup group chat, save it for later.
4. Create the database, then set the configuration of the database in `Database.php` file
5. Create new file

```php
require 'phpchat/Analytics.php';

$is_initial = 0;                    // Set 0 if you want to insert data into database
$filename = 'path/name.txt';        // Include path to file
$data = new Analytics($filename, $is_initial);

```