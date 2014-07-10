# Teapotio forum (standalone)

## Introduction

This is the standalone version of the forum software.

If you'd like to integrate the forum to your website, please refer [to the README file located in the teapotio-forum repository](https://github.com/teapotio/teapotio-forum/blob/master/README.md).

This platform is constantly being updated.

## Installation

1. Download composer.phar

` $ curl -sS https://getcomposer.org/installer | php `

2. Create a new project

` $ php composer.phar create-project -s dev teapotio/teapotio-forum-standard myProjectFolder `

3. Check if your environment is ready

` $ php app/check.php `

4. Run the following commands

```
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:create
$ php app/console teapotio:forum:install
$ php app/console assetic:dump
```
