# Teapotio Forum

## Introduction

This is the standalone version of the forum software.

If you'd like to integrate the forum to your website, please refer [to the README file located in the teapotio-forum repository].

This platform is constantly being updated.

## Installation

1. Download composer.phar

``` $ curl -sS https://getcomposer.org/installer | php

2. Create a new project

``` $ php composer.phar create-project -s dev teapotio/teapotio-forum-standard myProjectFolder

This repository is public and its main purpose is the Forum website. If you think it will useful for you, feel free to use it.

Teapotio Base Bundles Required:
* https://github.com/Teapotio/BaseForumBundle
* https://github.com/Teapotio/BaseUserBundle

Make sure your environment fits Symfony's requirements.

[1] Add parameters_local.yml in app/config and set parameters to your local MySQL DB
[2] php app/console doctrine:database:create
[3] php app/console doctrine:schema:create
