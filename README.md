# Eleven Soft Backend Refactoring Test

Welcome to the Eleven Soft Backend Refactoring Test repository! This Laravel-based project is designed to evaluate your backend skills, particularly in refactoring and improving the existing codebase.

## Getting Started

To begin the test, follow the steps outlined below:

### Prerequisites

Ensure that you have the following prerequisites installed on your local machine:

- PHP (>= 8.1)
- Composer
- Docker

### Installation and Setup

Ensure that docker is running on your local machine.

1. Clone this repository to your local machine:

   ```bash
   git clone https://github.com/elevensoft-dev/backend-refactoring-test.git
   ```

2. Change to the project directory:

   ```bash
   cd backend-refactoring-test
   ```

3. Install and configure the project:

   ```bash
   make install
   ```

4. Start the Docker containers:

   ```bash
   make up
   ```

The application will be accessible at `http://localhost:8000`.

### Documentation

The API is documented using Swagger. Ensure that your refactoring maintains or improves the clarity of the API documentation. The Swagger documentation can be accessed at `http://localhost:8000/api/documentation`.

### Useful Makefile Targets

- `make ps`: Show Docker container status.
- `make down`: Stop and remove Docker containers.
- `make forget`: Stop and remove Docker containers, volumes, and MySQL data.
- `make api-shell`: Access the API container shell.
- `make api-test`: Run PHPUnit tests.
- `make api-test-feature`: Run PHPUnit tests for the Feature suite.
- `make api-test-php-unit`: Run PHPUnit tests specifically.

### Submission Guidelines

1. Create a new branch for your changes:

   ```bash
   git checkout -b feature/refactoring-changes
   ```

2. Make your changes and commit them:

   ```bash
   git commit -m "refactor: change code for improved readability and maintainability"
   ```

3. Push your changes to your forked repository:

   ```bash
   git push origin feature/refactoring-changes
   ```

4. Open a pull request against the main repository.

## Questions and Support

If you have any questions or need assistance during the test, please send an email to [Eleven Soft Support](mailto:dev_tests@elevensoft.dev).

You can also reach us on WhatsApp: [Eleven Soft WhatsApp](https://wa.me/5548988168221).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

### Commit Message Format
Each commit message consists of a **header**, a **body** and a **footer**.  The header has a special
format that includes a **type** and a **subject**:

```
<type>: <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

The **header** is mandatory.

Any line of the commit message cannot be longer 100 characters! This allows the message to be easier
to read on GitHub as well as in various git tools.

The footer should contain a [closing reference to an issue](https://help.github.com/articles/closing-issues-via-commit-messages/) if any.

Samples: (even more [samples](https://github.com/angular/angular/commits/main))

```
docs(changelog): update changelog to beta.5
```
```
fix(release): need to depend on latest rxjs and zone.js

The version in our package.json gets copied to the one we publish, and users need the latest of these.
```

### Revert
If the commit reverts a previous commit, it should begin with `revert: `, followed by the header of the reverted commit. In the body it should say: `This reverts commit <hash>.`, where the hash is the SHA of the commit being reverted.

### Type
Must be one of the following:

* **build**: Changes that affect the build system or external dependencies (example scopes: gulp, broccoli, npm)
* **ci**: Changes to our CI configuration files and scripts (example scopes: Travis, Circle, BrowserStack, SauceLabs)
* **docs**: Documentation only changes
* **feat**: A new feature
* **fix**: A bug fix
* **perf**: A code change that improves performance
* **refactor**: A code change that neither fixes a bug nor adds a feature
* **style**: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
* **test**: Adding missing tests or correcting existing tests


### Subject
The subject contains a succinct description of the change:

* use the imperative, present tense: "change" not "changed" nor "changes"
* don't capitalize the first letter
* no dot (.) at the end

### Body
Just as in the **subject**, use the imperative, present tense: "change" not "changed" nor "changes".
The body should include the motivation for the change and contrast this with previous behavior.

### Footer
The footer should contain any information about **Breaking Changes** and is also the place to
reference GitHub issues that this commit **Closes**.

**Breaking Changes** should start with the word `BREAKING CHANGE:` with a space or two newlines. The rest of the commit message is then used for this.

A detailed explanation can be found in this [document][commit-message-format].

[commit-message-format]: https://docs.google.com/document/d/1QrDFcIiPjSLDn3EL15IJygNPiHORgU1_OOAqWjiDU5Y/edit#

