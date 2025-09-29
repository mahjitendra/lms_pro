# Authentication API

This document outlines the API endpoints for user authentication.

## Register

- **Endpoint:** `POST /api/v1/auth/register`
- **Description:** Registers a new user.
- **Request Body:**
  ```json
  {
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "password",
    "password_confirmation": "password"
  }
  ```
- **Response:**
  ```json
  {
    "access_token": "your-auth-token",
    "token_type": "Bearer"
  }
  ```

## Login

- **Endpoint:** `POST /api/v1/auth/login`
- **Description:** Authenticates a user and returns a token.
- **Request Body:**
  ```json
  {
    "email": "john.doe@example.com",
    "password": "password"
  }
  ```
- **Response:**
  ```json
  {
    "access_token": "your-auth-token",
    "token_type": "Bearer"
  }
  ```

## Logout

- **Endpoint:** `POST /api/v1/auth/logout`
- **Description:** Logs out the authenticated user.
- **Authentication:** Bearer Token required.
- **Response:** `204 No Content`