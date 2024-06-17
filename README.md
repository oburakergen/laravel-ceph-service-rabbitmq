# Project Title

This project is a service-oriented architecture built with Laravel, using the repository pattern. It utilizes several technologies including PHP 8.1, Laravel Framework, RabbitMQ, Minio, MySQL, and JWT-Auth.

## Services

The application consists of the following services:

- User Service
- License Service
- File Management Service
- Notification Service
- Object Storage Service

Each service has its own Docker container and communicates with the others through a shared network.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- Docker
- Docker Compose

### Installation

1. Clone the repository
2. Navigate to the project directory
3. Run `docker-compose up -d`

## Built With

- [PHP](https://www.php.net/) - The primary programming language used.
- [Laravel](https://laravel.com/) - The web framework used.
- [RabbitMQ](https://www.rabbitmq.com/) - Open source message broker.
- [Minio](https://min.io/) - High Performance, Kubernetes Native Object Storage.
- [MySQL](https://www.mysql.com/) - The database used.
- [JWT-Auth](https://jwt-auth.readthedocs.io/) - JSON Web Token Authentication for Laravel & Lumen.

## Authors

- [Burak ERGEN](https://github.com/oburakergen)

## License

This project is licensed under the MIT License.