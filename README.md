# Wildcard Participa
[![Build Status](https://travis-ci.org/disedit/wildcard-participa.svg?branch=master)](https://travis-ci.org/disedit/wildcard-participa)

## About
Wildcard Participa is an __online voting system__ for city councils.

- __Ease of use__: Any registered citizen can cast a ballot by entering their ID and verifying their cellphone via an SMS code.
- __Multiple questions__: People can vote on multiple questions at once and submit a unique ballot.
- __Questions__: The questions can be single or multiple choice. (_Ranked voting in the works_)
- __In-person voting__: System to set up one or more polling stations, where citizens may go and enter their ballots manually or have a staff member do it for them. This feature is especially aimed at senior citizens, who may not have an internet connection or a cellphone.
- __Anonymous voting__. Ballots are encrypted and diassociated from the voter when they are cast. (_Optional_)
- __Ballot annulment__: When anonymous voting is not enabled, administrators may annul a ballot that has been cast online to replace it with one cast in-person.
- __ID Look-up__: Administrators can search the census to troubleshoot common problems with in-person voting (_i.e. the ID was entered incorrectly into the census, or it is missing_).
- __Results page__: Display the results when the vote ends or when you specify.
- __Campaigning__: Each edition has a dedicated About page, which is displayed before the vote opens and is 100% customizable. Furthermore, every option has its dedicated description page, where voters can learn more to make an informed decision.
- __Multilingual support__ for multilingual communities.
- __Accessibility__: Site optimized for people with disabilities, such as the visually impaired.

## Requirements
Wildcard Participa is built on top of Laravel 5.6, which has the following requirements:

- PHP >= 7.0.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySQL, PostgreSQL, SQLite or SQL Server

## Installation
- Clone this repo and reset it.

    ```
    git clone https://github.com/disedit/wildcard-participa.git project_name
    cd project_name
    rm -rf .git
    git init
    git add .
    git commit -m "Initial commit"
    ```

- Duplicate the example .env file `cp .env.example .env` and add your database configuration.
- Run composer `composer install`.
- Run the database migrations and set the app keys.

    ```
    php artisan migrate
    php artisan key:generate
    php artisan jwt:generate
    ```

- Run `npm install` and `npm run dev` or `npm run production` to generate the required assets. _(Note: Wildcard Participa uses FontAwesome Pro for its icons. This is an optional dependency, but you will need to purchase and set a FontAwesome Pro NPM token if you want to use the icons.)_

- Finally, you can run `php artisan serve` to view the site.

## Create an edition
```
php artisan edition:new
```

This command will walk you through the edition creation process, prompting for start and end dates among other details, including the questions and options to go on the ballot.

If, however, you just want to test the site, you can run `php artisan db:seed` to create a fake edition with fake voters.

## Create admin users
```
php artisan admins:create [users] (--superadmin)
```

You can add as many users as you want in one go by separating them with a space. You can also make them superadministrators by adding the `--superadmin` flag. The command will return a list of the users along with their generated passwords. Write these down in a safe place and distribute them among your staff.

## Import a census
Place a file containing all the valid IDs (one per line) in `/storage/app/census` and run the following command, specifying the name of the file:

```
php artisan census:import file=census.csv
```

_Note: If the 'hashed_ids' option is set to true, this command will expect the IDs to be pre-hashed in SHA-256_

## Customization
The following files contain options to customize the site:
- `/config/participa.php` contains the Council details and app-specific settings, including vote rules.
- `/resources/assets/sass/_variables.scss` contains all the variables to customize the look and feel of the app.
- `/resources/lang/*` and `resources/assets/js/lang/*` contain all the PHP and JS language files.
- `/public/manifest.json` Web App Manifest

## Disclaimer
Online voting is a tricky subject and no system is completely secure. __This is why we do not recommend that councils use systems like this to carry out important elections.__ This system was originally conceived to enable local councils to survey the opinion of their citizens to prioritize projects when drafting a budget.

The method to verify a voter's identity used by this system is a compromise between __security__ and __ease of participation__. Ideally, online voting systems should require voters to enter their electronic ID to verify their identity. However, turnout would be very low under such a system and the result of the election would be righfully put into question.

This is why this system only requires voters to enter their ID numbers. Ballot stuffing is combated by requiring users to verify their cellphone numbers, as only one vote is allowed per cellphone number. A person could still vote more than once, provided that they have access to multiple cellphones and multiple valid IDs. However, we believe that by making the voting process easy and accessible, the increase in participation far outweighs any possible manipulation caused by any single party.
