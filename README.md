# Library Management API

This API provides the functionality for managing users, books, borrowing, and reviews in a library system. The routes
cover user authentication, book borrowing, returning, and reviews management.

## API Endpoints

### Public Routes

- **POST `/register`**  
  Registers a new user.  
  **Request Body**:
    - `name`: User's full name
    - `email`: User's email address
    - `password`: User's password

  **Response**:
    - `status`: Success or error message
    - `data`: User information

- **POST `/login`**  
  Logs in an existing user and returns a token for authentication.  
  **Request Body**:
    - `email`: User's email address
    - `password`: User's password

  **Response**:
    - `status`: Success or error message
    - `token`: JWT token for authentication

### Authenticated Routes

These routes require an API token for authentication. Add the token in the `Authorization` header with the prefix
`Bearer`.

- **GET `/profile`**  
  Returns the authenticated user's profile information.

- **POST `/logout`**  
  Logs out the user and invalidates the token.

- **POST `/borrow`**  
  Allows a user to borrow a book.  
  **Request Body**:
    - `book_id`: ID of the book to borrow

  **Response**:
    - `status`: Success message

- **POST `/return`**  
  Allows a user to return a borrowed book.  
  **Request Body**:
    - `book_id`: ID of the book to return

  **Response**:
    - `status`: Success message

- **GET `/my-books`**  
  Lists all books borrowed by the authenticated user.

- **CRUD Routes for Reviews**
    - **GET `/reviews`**: List all reviews
    - **POST `/reviews`**: Create a new review
    - **GET `/reviews/{id}`**: Show a specific review
    - **PUT `/reviews/{id}`**: Update an existing review
    - **DELETE `/reviews/{id}`**: Delete a review

  These routes are managed by the `ReviewController`.

### Admin Routes (Role-based Access)

These routes are restricted to users with the required role, and they handle book and genre management.

- **CRUD Routes for Genres**
    - **GET `/genres`**: List all genres
    - **POST `/genres`**: Create a new genre
    - **GET `/genres/{id}`**: Show a specific genre
    - **PUT `/genres/{id}`**: Update an existing genre
    - **DELETE `/genres/{id}`**: Delete a genre

- **CRUD Routes for Books**
    - **GET `/books`**: List all books
    - **POST `/books`**: Create a new book
    - **GET `/books/{id}`**: Show a specific book
    - **PUT `/books/{id}`**: Update an existing book
    - **DELETE `/books/{id}`**: Delete a book

  The `books` routes also use the `RandomIsbnGenerator` middleware to generate a random ISBN for new books.

- **GET `/analytics`**  
  Provides analytics on the books borrowed, such as most borrowed books and overall borrow statistics.

- **GET `/admin/reviews`**  
  Returns a list of reviews for admin users.

### Middleware

- **auth:api**: Ensures that the user is authenticated and has a valid token.
- **AuthRoleBased**: Restricts access to routes based on the user's role (e.g., admin).
- **RandomIsbnGenerator**: Automatically generates a random ISBN when creating a new book.

## Usage

1. **Register a new user:**
   Send a `POST` request to `/register` with the necessary user details.

2. **Login and get token:**
   Send a `POST` request to `/login` with email and password to get a token.

3. **Borrow a book:**
   Send a `POST` request to `/borrow` with the `book_id` to borrow a book.

4. **Return a book:**
   Send a `POST` request to `/return` with the `book_id` to return a borrowed book.

5. **CRUD operations on reviews** (Authenticated users only).

6. **Admin routes** are restricted to users with an admin role and provide management for books, genres, and analytics.

## Authentication

All routes except `/register` and `/login` require a valid API token. The token should be included in the
`Authorization` header with the `Bearer` prefix.

```bash
Authorization: Bearer <your-api-token>
