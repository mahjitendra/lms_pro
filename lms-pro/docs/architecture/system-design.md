# System Design

This document provides a high-level overview of the LMS Pro system architecture.

## Core Components

The system is composed of four main components:

1.  **Backend API (Laravel):** The central nervous system of the application. It handles all business logic, data persistence, and communication with other services. It exposes a RESTful API for the client applications.

2.  **Frontend Web App (React):** A single-page application (SPA) that provides a rich user interface for browsers. It communicates with the backend API to fetch and display data.

3.  **Mobile App (React Native):** A cross-platform mobile application for iOS and Android, offering a native experience for users on the go.

4.  **Python ML Service (Flask):** A dedicated microservice for handling all AI and machine learning tasks. This keeps the resource-intensive AI operations separate from the main backend, improving scalability and maintainability.

## Communication Flow

- The **Frontend** and **Mobile** apps communicate with the **Backend API** via RESTful HTTP requests.
- The **Backend API** communicates with the **Python ML Service** via internal API calls to perform tasks like video analysis, recommendations, etc.
- A **Redis** instance is used for caching and queue management to handle asynchronous jobs (e.g., sending emails, processing videos).
- All data is stored in a **MySQL** database.

## Containerization

The entire application stack is containerized using **Docker** and orchestrated with **Docker Compose**. This ensures a consistent development, testing, and production environment. An **Nginx** container acts as a reverse proxy, directing traffic to the appropriate service (frontend, backend, or ML).