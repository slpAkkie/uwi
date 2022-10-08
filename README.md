# Uwi Framework

<img src="https://akkie.cyou/files/uwi_banner.png" alt="Uwi Banner" />

Uwi - Simple and legacy backend framework without any third-party packages based on MVC pattern.

## Description

Framework based on Container with binding concrete realization to abstract interfaces.
After core Application components has been loaded one of Kernel will be started.

Kernel handle all proccess according the context. Kernel may be replaced with other one.

There is only one Kernel available now - `HttpKernel`.

### Application

Application container provide some features:

- Inject dependencies into functions and methods.
- Dotenv - extract variables from .env files.
- Lion ORM - to work with database entities.

### HttpKernel

This Kernel responsible for http requests.

Components in this Kernel:

- Router - to specify which action use for the current request.
- Request - handle `HTTP` requests.
- Response - to send `HTTP` response.
- Sessions - to work with sessions.
- Calibri Templates - tempalte engine to construct your `html` file.

## Author

Alexandr Shamanin (@slpakkie).

## Version

2.1.0-alpha
