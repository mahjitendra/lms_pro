import os
from pydantic import BaseSettings

class Settings(BaseSettings):
    """
    Application settings, loaded from environment variables.
    """
    # --- Server Configuration ---
    HOST: str = "0.0.0.0"
    PORT: int = 5000
    RELOAD: bool = os.getenv("PYTHON_ENV") == "development"

    # --- Security ---
    # This secret key must be shared with the Laravel backend to authenticate requests.
    SHARED_SECRET_KEY: str = os.getenv("PYTHON_ML_SERVICE_SECRET", "default_secret_key_for_dev")

    # --- Model Paths ---
    # In a real application, these would point to specific model files (e.g., .h5, .pt, .pkl)
    # stored in a dedicated directory or downloaded from cloud storage.
    MODELS_DIR: str = os.path.join(os.path.dirname(__file__), '..', 'models_data')

    # Example specific model paths
    IMAGE_CLASSIFICATION_MODEL_PATH: str = os.path.join(MODELS_DIR, "image_classification", "resnet50.pt")
    OBJECT_DETECTION_MODEL_PATH: str = os.path.join(MODELS_DIR, "object_detection", "yolov5s.pt")
    RECOMMENDATION_MODEL_PATH: str = os.path.join(MODELS_DIR, "recommendation", "collaborative_filtering.pkl")

    class Config:
        # This allows loading variables from a .env file if present
        env_file = ".env"
        env_file_encoding = "utf-8"

# Create a single settings instance to be used throughout the application
settings = Settings()

# Ensure the models directory exists
os.makedirs(settings.MODELS_DIR, exist_ok=True)