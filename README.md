# My Learning Hub

## Table of contents
* [General info](#general-info)
* [Demonstration](#demonstration)
* [Technologies](#technologies)
* [Setup](#setup)

## General info

In this project it is possible to check user course activity - status, when the user joined the course and if finished, when. The course table can be filtered by multiple scenarious. As well it's possible to create a new course for an existing user.

Project allows to generate 50 users, 100 courses and 40 enrollments with random data for each. Also the project includes PHPUnit testing for all back-end functions.

## Demonstration

## Technologies

Project is created with:
* PHP version: 8.0
* Laravel version: 9
* MySQL version: 8.0.31-0ubuntu0.20.04.2 for Linux on x86_64 ((Ubuntu))
* Composer version: 2.4.4
* PHPUnit version: 9.5

## Setup

1. Clone this repository `git clone https://github.com/dauchinjs/MLH.git`
2. Install all dependencies `composer install` and `npm install` and `npm run dev`
3. Create a database and rename the `.env.example` file to `.env` and add your credentials
4. Because you ran `npm run dev` open a new terminal and generate an app key using `php artisan key:generate`
5. Run migrations `php artisan migrate`
6. To run tests use `php artisan test`
7. To get random data for database run seeds `php artisan db:seed`
8. Run the local server using `php artisan serve`
