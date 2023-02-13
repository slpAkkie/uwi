# Uwi Framework

Uwi - Simple and legacy backend framework without any third-party packages based on MVC pattern.

## Description

Framework based on Container with binding concrete realization to abstract interfaces.
After core Application components has been loaded one of Kernel will be started.

Kernel handle all proccess according the context. Kernel may be replaced with other one.

There is only one Kernel available now - `WebKernel`.

### Application

Application container provide some features:

- Inject dependencies into functions and methods.
- Dotenv - extract variables from .env files.

### WebKernel

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

2.3.0-beta
