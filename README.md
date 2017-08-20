# rosterbots
Roster bots application API
# installation

1 - Download or clone repository.

2 - Create a MySQL database and import the database structure and data from the folder "SQL" located in project root folder.

3 - Find the configuration file "settings.php" in project root and change the following variables, with your local or remote server information:

$db_host = 'localhost';                                 // your mySQL host name or IP address

$db_user = 'root';                                      // your MySQL user

$db_pwd = '';                                           // your MySQL password

$db_name = 'rosterbots';                                // the name of your MySQL database

$self_url = 'http://localhost/';                        // root url for your project

$include_url = $_SERVER['DOCUMENT_ROOT']."/";           // root physical path for your project


4 - Everything should be working now. To navigate to the API endpoints and call the web services, in your Rest Client app (like Postman), make an HTTP Request to "http://MY_HOST_NAME_OR_IP/api/ENDPOINT_NAME"

where "ENDPOINT_NAME" can be found in the API documentation on the .html file "APISpecificationDoc.html" on the root folder of this project.

# live demo
You can see a live demo, with a basic frontend implementation to see how the API works. Please note that this webpage has only been created to test the API functionality and has not been tested in mobile devices. You will have to create an account and after that, login to the webpage to be able to create or edit yor Roster.

Frontend URL: http://dev3.innvatis.com
Online API endpoint documantation: http://dev3.innvatis.com/APISpecificationDoc.html


# unit tests
You can find some Unit Tests created with PHPUnit in the folder "/api/tests". In order to excecute them you will need to use PhpUnit framework. This project has been created and tested in a PHP 5.5 server. Apache version 2.4.9.

Please not that for testing with PHPUnit, you should set up the "settings_test.php" file with the same structurethan file in point 3 above. And I suggest you to use a clean database, wich its structure and data can be found in the /SQL folder too, by the name "rosterbots_testing.sql"


# project structure
Controllers: you can find the API controllers in the folder /api/controller. This classes inherits from the SLIM framework controller and here are defined the endpoint routes and the functions associated with them.

Models: you can find the model classes in the folder /api/model. This classes inherits from two basic classes for accesing the database and manipulating objects that can be found in the folder /lib.

Views: there is only one view located in the /api/views folder and it is used to render the JSON output from the API responses.

Security: the class that manages the security is found in the /api/Middleware folder as a file called "HttpBasicAuth.php", this class extends from the SLIM Middleware class and basically denies the acces to the information to any request that is not using the authentication token in his request header.







 




