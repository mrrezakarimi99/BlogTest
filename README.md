
# Store Web Api

## Overview

1. [Install prerequisites](#install-prerequisites)

   Before installing project make sure the following prerequisites have been met.

2. [Clone the project](#clone-the-project)

   We’ll download the code from its repository on GitHub.

5. [Run the application](#run-the-application)

   By this point we’ll have all the project pieces in place.
___

## Install prerequisites

For now, this project has been mainly created for Unix `(Linux/MacOS)`. Perhaps it could work on Windows.

All requisites should be available for your distribution. The most important are :

* [Git](https://www.digitalocean.com/community/tutorials/how-to-install-git-on-ubuntu-20-04)

## Clone the project

To install [Git](http://git-scm.com/book/en/v2/Getting-Started-Installing-Git), download it and install following the instructions :

```sh
git clone https://github.com/mrrezakarimi99/BlogTest
```

### Project tree

```sh
.
├── web
│   └── app
│       ├── Http
│       ├── Models
│   └── config
│       ├── filesystems
│   └── database
│       └── factories
│       └── migration
│   └── public
│       └── index.php
│   └── test
│       └── feature
│            ├── AuthTest
│            ├── PostTest
├── README.md
        
```

## Run the application

1. Copying the composer configuration file :

    ```sh
    cp .env.example .env
    ```

2. Start the application :

    ```sh
    php artisan serve
    ```
3. run migration with fake data

    ```sh
    php artisan migrate --seed
    ```

## Help us

Any thought, feedback or (hopefully not!)
