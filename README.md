# Dockerized LEMP stack manager

This project is a web-based Laravel application that is used to manage the Dockerized LEMP stack provided by [Docker LEMP Stack project](https://github.com/grey-dev-0/docker-tf-lemp/tree/compose).

## Usage and Installation

This app will be automatically installed by the mentioned stack, it enables developers to define where their projects are on the host system - under a shared root directory to be set in the config in the main stack.

The local web portal provided by this project enables you to:

- Define your development projects - currently supports php and node.
- Configure and create the database of each project you define.
- Set your custom domain to access your projects via web browsers.
- Automatically have nginx and https access to your local project configured.
- Edit the automatically generated nginx configuration of your projects when needed.
- Manage all of your projects databases via a pre-installed phpMyAdmin instance in the mentioned stack.
