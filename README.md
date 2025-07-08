# Backend For New Article App

The application is backend for the new article application. The project collects data from different News Article platforms using their API's Some of the platorms used for collecting data are as following.

- The Guardian
- NewYork Times
- NewsApiOrg

## Installation (Ubunut/Linux)

Below are the installation steps to run the application on your local system.

### Pre Requesistes for installation

Below are the things needed for the setup to work properly on the local machine

- Mysql
- Apache
- Php > 8.0
- composer

`NOTE FOR PEOPLE WHO HAVE DOCKER!`  Make sure that your docker is not running or conflicting with ports like `3306` and `8000` and your services like mysql and apache are enabled and running.

### Installation Steps

- Download the project from github
- Open up the project folder and open Terminal inside the root of the project folder
- Run below command
```php
composer install
```
- Once the installation is complete Open the application folder in any code editor example VS-Code
- Now create a new file on the root of the project folder by the name of `.env`
- Open up the file `.env.example` this one already exists in the project.
- Copy every thing from `.env.example` and paste it in `.env`
- Now that our `.env` is ready we have to make some modification to the values.
- But before that go ahead and create a database in the MySql `newsss_app919`
- Now we are ready to update our `.env`
- Open up `.env` and update the following 3 variables with your own values set in your system

```php
# IN THE PREVIOUS STEPS WE SET THE VALUE TO "newsss_app"
# BUT IF YOU NAMED YOUR DATABASE SOMETHINGE ELSE USE THAT NAME

DB_DATABASE=newsss_app919

# USE THE ONES SET IN YOUR SYSTEM FOR BELOW VARIABLES

DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=YOUR_MYSQL_ADMIN_USERNAME
DB_PASSWORD=YOUR_MYSQL_ADMIN_PASSWORD

# BELOW VARIABLE VALUES ARE PROVIDED IN THE EMAIL

NEWS_API_ORG_API_KEY='PROVIDED_IN_EMAIL'
THE_GUARDIAN_API_KEY='PROVIDED_IN_EMAIL'
NEWYORK_TIMES_API_KEY='PROVIDED_IN_EMAIL'

```

- After updating the `.env` save it and now we are ready to run some commands in the `Terminal`
- Open the terminal in the root folder of the project and run the following commands `one by one`

```php
# 1st COMMAND

php artisan key:generate


# 2nd COMMAND

php artisan optimize:clear


# 3rd COMMAND

php artisan migrate:fresh --seed


# 4th COMMAND

php artisan storage:link


# 5th COMMAND

php artisan serve

```

- After `5th Command` keep the terminal running `DO-NOT-CLOSE-IT` and open the browser and hit the link [http://localhost:8000](http://localhost:8000)

- The above link will take you to the homepage with details on how to pull the articles and other details about the project.

- A loom video is also created for viewing the application [Click for LOOM Video](https://www.loom.com/share/04042f60f2ef42a88542fa94ca63a304?sid=c832265c-5308-4bed-8c98-142dd5c739ee)

