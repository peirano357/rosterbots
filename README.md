# rosterbots
Roster bots application API created with SLIM framework

# installation

1 - Download or clone repository.

2 - Create a MySQL database and import the database structure and data from the folder "SQL" located in project root folder.

3 - Find the configuration file "settings.php" in project root and change the following variables, with your local or server information:

$db_host = 'localhost';                                 // your mySQL host name or IP address

$db_user = 'root';                                      // your MySQL user

$db_pwd = '';                                           // your MySQL password

$db_name = 'rosterbots';                                // the name of your MySQL database

$self_url = 'http://localhost/';                        // root url for your project

$include_url = $_SERVER['DOCUMENT_ROOT']."/";           // root physical path for your project


4 - Everithing should be working. To navigate to the API endpoints and call the web services, in your Rest Client app (like Postman), make an HTTP Request to "http://MY_HOST_NAME_OR_IP/api/ENDPOINT_NAME"

where "ENDPOINT_NAME" can be found in the API documentation on the .html file "APISpecificationDoc.html" on the root folder of this project.


 




