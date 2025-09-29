# LMS Pro

LMS Pro is a comprehensive, AI-powered Learning Management System designed to provide a modern, interactive, and personalized learning experience. This repository contains the full source code for the backend, web frontend, and mobile applications.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Architecture](#architecture)
- [Technology Stack](#technology-stack)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Deployment](#deployment)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)

## Project Overview

LMS Pro is a multi-platform application consisting of:
- A **Laravel Backend API** that serves as the core of the system, managing data, business logic, and interactions with AI services.
- A **Python-based AI/ML Service** that handles complex tasks like video analysis, recommendations, and natural language processing.
- A **ReactJS Web Application** for a rich, interactive user experience on desktops.
- A **React Native Mobile Application** for learning on the go.

The system is designed to be scalable, secure, and maintainable, leveraging modern technologies and best practices.

## Features

- **Course Management**: Create, manage, and enroll in courses with rich multimedia content.
- **AI-Powered Learning**:
    - **Personalized Recommendations**: Suggests courses based on user activity and interests.
    - **Video Analysis**: Automated processing and indexing of video content.
    - **Computer Vision**: Face recognition for proctoring, object detection in educational content.
    - **NLP**: Chatbots for support, question-answering systems, and text analysis.
- **Assessment & Grading**: Quizzes, assignments, and automated grading.
- **Analytics & Reporting**: Dashboards for students, instructors, and administrators.
- **Real-time Communication**: Chat, forums, and announcements.
- **Payment & Subscriptions**: Secure payment processing for course enrollments.
- **Cross-Platform**: Accessible via web and mobile (iOS & Android).

## Architecture

The application is built on a microservices-oriented architecture:
- **Backend (Laravel)**: A monolithic API that handles core LMS functionalities.
- **AI Services (Python/Flask)**: A separate service dedicated to AI and machine learning tasks, communicating with the backend via REST API.
- **Frontend (React/React Native)**: Decoupled client applications that consume the backend API.
- **Docker**: The entire environment is containerized for consistency and ease of deployment.

For a detailed overview, see the [Architecture Documentation](./docs/architecture/system-design.md).

## Technology Stack

- **Backend**: Laravel (PHP), MySQL/PostgreSQL, Redis
- **Frontend (Web)**: React.js, Redux, Axios
- **Frontend (Mobile)**: React Native, Redux
- **AI/ML**: Python, Flask, TensorFlow/PyTorch, Scikit-learn
- **Containerization**: Docker, Docker Compose
- **CI/CD**: GitHub Actions

## Getting Started

### Prerequisites

- Docker and Docker Compose
- Node.js and npm/yarn
- Composer
- Python 3.8+

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/lms-pro.git
    cd lms-pro
    ```

2.  **Set up environment variables:**
    Copy all `.env.example` files to `.env` in their respective directories (`/`, `/backend`, `/frontend-web`, `/mobile-app`).
    ```bash
    cp .env.example .env
    cp backend/.env.example backend/.env
    cp frontend-web/.env.example frontend-web/.env
    cp mobile-app/.env.example mobile-app/.env
    ```
    Update the `.env` files with your configuration (database credentials, API keys, etc.).

3.  **Build and run the application using Docker:**
    ```bash
    docker-compose up --build -d
    ```

4.  **Install backend dependencies and run migrations:**
    ```bash
    docker-compose exec backend composer install
    docker-compose exec backend php artisan key:generate
    docker-compose exec backend php artisan migrate --seed
    ```

5.  **Install frontend dependencies:**
    - **Web App:**
      ```bash
      docker-compose exec frontend npm install
      ```
    - **Mobile App:**
      Follow the React Native environment setup guide, then run:
      ```bash
      cd mobile-app
      npm install
      ```

## Usage

- **Web App**: Access at `http://localhost:3000`
- **Backend API**: Access at `http://localhost:8000`
- **Mobile App**:
    - **iOS**: `npx react-native run-ios`
    - **Android**: `npx react-native run-android`

## Testing

The project includes unit, feature, and end-to-end tests.

- **Backend (Laravel):**
  ```bash
  docker-compose exec backend ./vendor/bin/phpunit
  ```

- **Frontend (React):**
  ```bash
  docker-compose exec frontend npm test
  ```

- **E2E Tests:**
  Run the tests located in the `/tests/e2e` directory using your preferred test runner (e.g., Cypress, Playwright).

## Deployment

The application is designed for container-based deployment. See the [Deployment Documentation](./docs/deployment/docker.md) for detailed instructions on deploying with Docker Compose to a production environment.

## Documentation

Detailed documentation for the API, architecture, and user guides can be found in the `/docs` directory.
- [API Documentation](./docs/api/)
- [Architecture Documentation](./docs/architecture/)
- [Deployment Guides](./docs/deployment/)

## Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) to learn about our development process, how to propose bugfixes and improvements, and how to build and test your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.