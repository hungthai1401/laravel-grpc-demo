# gRPC Demo with Laravel

This project demonstrates the integration of gRPC with a Laravel application. It showcases how to define gRPC services, generate client and server code, and implement a gRPC server within a Laravel environment.

## Overview

The project includes a simple User Service implemented using gRPC. It provides basic CRUD operations for user management as an example of how gRPC can be used for efficient, high-performance communication in a microservices architecture.

## Project Structure

- **proto/**: Contains Protocol Buffer definitions for the gRPC services.
  - `user.proto`: Defines the User service with RPC methods for managing users.
- **generated/**: Auto-generated PHP code from the proto files, including service interfaces and message classes.
- **app/Services/**: Implementation of the gRPC services.
  - `UserService.php`: Implements the User service defined in `user.proto`.
- **app/Console/Commands/**:
  - `GrpcServe.php`: A Laravel command to start the gRPC server.
- **Dockerfile** and **start-container.sh**: Configuration for containerizing the application.

## Getting Started

### Prerequisites

- PHP >= 8.0
- Composer
- Docker (optional, for containerization)
- gRPC PHP extension
- Protocol Buffers compiler (protoc)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/grpc-demo.git
   cd grpc-demo
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Generate gRPC code from proto files (if not already generated):
   ```bash
   protoc --plugin=protoc-gen-php-grpc \
       --php_out=./generated \
       --php-grpc_out=./generated \
       proto/user.proto
   ```

4. Configure your environment:
   - Copy `.env.example` to `.env` and adjust settings as needed.

5. Run the application using Docker (optional):
   ```bash
   php artisan grpc:serve
   ```

## Usage

Once the gRPC server is running, you can interact with it using a gRPC client. The User Service provides methods to create, read, update, and delete users.

### Example gRPC Client

You can create a PHP client or use tools like [grpcurl](https://github.com/fullstorydev/grpcurl) to test the API.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
