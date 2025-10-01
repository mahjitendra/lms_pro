# Use an official Python runtime as a parent image
FROM python:3.9-slim

# Set the working directory in the container
WORKDIR /app

# Install system dependencies that might be needed by some ML libraries (e.g., OpenCV)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libgl1-mesa-glx \
    libglib2.0-0 \
    && rm -rf /var/lib/apt/lists/*

# Copy the requirements file into the container at /app
COPY ./backend/python_ml/requirements.txt .

# Install any needed packages specified in requirements.txt
# --no-cache-dir reduces image size
RUN pip install --no-cache-dir -r requirements.txt

# Copy the rest of the application's code from the host to the container at /app
COPY ./backend/python_ml/ .

# Expose the port the app runs on
EXPOSE 5000

# Define the command to run the application
# We use uvicorn to run our FastAPI app.
# The --host 0.0.0.0 flag makes the server accessible from outside the container.
CMD ["uvicorn", "api.app:app", "--host", "0.0.0.0", "--port", "5000"]