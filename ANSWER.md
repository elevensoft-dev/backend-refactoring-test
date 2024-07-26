# Code Refactor

## Overview

This document details the recent enhancements and updates made to the API endpoints, including validation, error handling, HTTP status codes, and OpenAPI annotations.

## User Controller

### Index Function

#### Changes and Enhancements

- *Error Handling:* 
  - Wrapped the user fetching logic in a `try-catch` block to handle exceptions.
  - Returns an appropriate error message for better error handling.

- *HTTP Status Codes:* 
  - Used `Response::HTTP_*` constants for readability and standard compliance.

### Show Function

#### Error Handling

- *Exception Handling:* 
  - Wrapped the user fetching logic in a `try-catch` block.
  - Handles specific exceptions (e.g., `ModelNotFoundException` for non-existent users) and general exceptions.

#### HTTP Status Codes

- Used Response: Used Response::HTTP_* constants for readability and standard compliance

#### Validation

- Ensured the id parameter is validated by trying to find the user and handling the ModelNotFoundException.

## Store Function

### FormRequest Class (StoreUserRequest)

- *Validation Handling:* 
  - Utilizes `StoreUserRequest` to automatically handle validation.
  - Returns a `400 Bad Request` response with validation errors if validation fails.

- *User Creation:* 
  - Creates a user if validation passes.
  - Returns a `201 Created` response upon successful creation.

- *Error Handling:* 
  - Catches unexpected errors and returns a `500 Internal Server Error` response.

## Update Function

#### Validation Handling

- *UpdateUserRequest:* 
  - Automatically validates incoming data.
  - Updates only the provided fields. If the password is not included, it remains unchanged.

#### Error Handling

- *Exception Handling:* 
  - Catches exceptions and returns appropriate HTTP responses.

#### Swagger/OpenAPI Annotations

- *@OA\Put Annotation:* 
  - Defines the endpoint, operation, request body, and responses.

- *Response Codes:* 
  - Includes common responses: `200` for success, `400` for validation errors, `404` for not found, and `500` for internal server errors.

## Destroy Function

#### Finding the User

- *User Retrieval:*
  - Utilizes `$this->user->findOrFail($id)` to retrieve the user by ID or throw a `ModelNotFoundException`.

#### Deleting the User

- *Deletion Logic:*
  - Deletes the user if found.

#### Exception Handling

- *ModelNotFoundException:* 
  - Specifically catches cases where the user is not found.

- *General Exception:* 
  - Catches any other unexpected errors.

## User Model

### Property Changes

- *Removed Private Properties:*
  - Changed private properties to protected to leverage Eloquent’s default behavior.

### Updated Docblocks

- *Docblock Adjustments:*
  - Updated descriptions and types to align with actual usage.

### Nullability

- *Nullable Properties:* 
  - Added `?` to nullable `Carbon` properties where appropriate.