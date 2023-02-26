# Symfony Docker (PHP8 / Nginx / Postgresql)

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell) or Run `docker compose up -d` to run in
   background
4. Open `http://localhost` in your favorite web browser
5. Run `docker compose down --remove-orphans` to stop the Docker containers.
6. Run `docker compose logs -f` to display current logs, `docker compose logs -f [CONTAINER_NAME]` to display specific
   container's current logs

## Configuration

1. Set your .env.local file with :
    ```env
    MAILER_DSN=mailtrap+smtp://USERNAME:PASSWORD@default
    ```
2. Run `docker compose --env-file .env.local up`

You can use sendinblue or mailtrap, mailjet is used in production

## Command examples

```shell
make composer c='install'
make composer c='require symfony/uid'
make sf c='d:m:m'
make sf c='doctrine:fixtures:load -n'
```