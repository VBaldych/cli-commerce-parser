# CLI Commerce Parser

This is Symfony application for parsing products from online shop.

As a shop for parsing I have chosen 'Moyo'.

As a XPath parser component I use [Symfony DOM Crawler](https://symfony.com/doc/current/components/dom_crawler.html)

For building API endpoint I use [API Platform](https://symfony.com/doc/6.4/the-fast-track/en/26-api.html)

Async data process based on queues feat. RabbitMQ.

## Installation

### Preparation

The build is based on Docker, so you should have it on your local machine - https://docs.docker.com/compose/install/

If you use Windows, it's better to [install WSL](https://documentation.ubuntu.com/wsl/en/latest/guides/install-ubuntu-wsl2/)

### Clone the repository

In the command line, run the following commands
```bash
git clone https://github.com/VBaldych/commerce-parser-cli.git
cd cli-commerce-parser
```

### Run local build

If you run the build for the first time, initialize it using command below for building containers,
install Composer dependencies and running migration
```bash
make init
```

To start a local build run
```bash
make start
```
### Make first parsing

For this action you should go into PHP container and run following commands:

1. Go into PHP container - `make php-cli`
1. Run queue worker - `php bin/console messenger:consume async`
2. Run parsing & getting data - `php bin/console app:pp`

### Check data in CSV
Go to `app/files/products.csv` and check parsed data

### Check data in API
Just go to page [http://127.0.0.1:8080/api/products](http://127.0.0.1:8080/api/products)

### Run Unit tests

Run the command:
```bash
php bin/phpunit
```

## Code analysis
You can take a look I use great tool Rector for code analysis. It helps to keep your code modern & fancy

For analyzing code with Rector, run it inside PHP container

```bash
vendor/bin/rector process --dry-run
```
## How we can improve application
- Use [Batches](https://symfony.com/doc/current/messenger.html#process-messages-by-batches) for process queues by chunks
