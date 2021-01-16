# Stat for posts

Get posts from API. 
Show some statictic (information) for posts.

## Implementation

### Initial controller (file)

index.php is main file, where application starts.

### Main parts

 - App/App.php starts main functional.
 - Service/Db.php gets database instance and contains methods for database.
 - Service/Api.php contains methods for API.
 - Service/Stat.php gets and shows statistic information.
 - Model/Model.php contains common methods for models (tables in database).
 - Model/PostModel.php contains methods for posts table (extends from Model).

## Requirements

 - PHP 7
 - PDO

## Solution

1) For API requests I use file_get_contents, because it's simpliest way. But then requests are not async. Best way (in my opinion) is using async requests, for it can include additional library. I decided that for this task I don't want to use additional libraries, also number of requests in this task is not so big. Also for requests can be used CURL or fsockopen, but I decided to use simpliest way in this case.

2) I decided to use in memory database for saving posts. In future can be needed new statistic information. Also can use ususal database (mysql, for example), but for this task in memory database is, may be, better solution. Also this task can be finished without database.

3) I tried to create clean, understandable architecture for this task.