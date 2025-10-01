import re
import numpy as np
import cv2
from sklearn.preprocessing import StandardScaler
import logging

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# --- Text Preprocessing ---

def clean_text(text: str) -> str:
    """
    Cleans a text string by lowercasing, removing HTML tags, and non-alphanumeric characters.

    Args:
        text: The raw input string.

    Returns:
        The cleaned text string.
    """
    # Remove HTML tags
    text = re.sub(r'<.*?>', '', text)
    # Remove non-alphanumeric characters (except spaces)
    text = re.sub(r'[^a-zA-Z0-9\s]', '', text)
    # Convert to lowercase
    text = text.lower()
    # Remove extra whitespace
    text = " ".join(text.split())

    return text

# --- Image Preprocessing ---

def preprocess_image_for_model(image_path: str, target_size: tuple = (224, 224)) -> np.ndarray:
    """
    Loads, resizes, and normalizes an image for a typical deep learning model.

    Args:
        image_path: Path to the image file.
        target_size: A tuple (width, height) for the output image size.

    Returns:
        A preprocessed numpy array representing the image.
    """
    try:
        image = cv2.imread(image_path)
        if image is None:
            raise ValueError(f"Could not read image at {image_path}")

        # Resize the image
        image = cv2.resize(image, target_size, interpolation=cv2.INTER_AREA)

        # Convert from BGR (OpenCV default) to RGB
        image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

        # Normalize pixel values from [0, 255] to [0, 1]
        image = image.astype(np.float32) / 255.0

        # In a real PyTorch/TensorFlow pipeline, you might also subtract the mean
        # and divide by the standard deviation.
        # e.g., normalize = transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225])

        return image
    except Exception as e:
        logger.error(f"Failed to preprocess image {image_path}: {e}")
        raise

# --- Numerical Data Preprocessing ---

def scale_numerical_features(data: np.ndarray) -> tuple:
    """
    Scales numerical features using StandardScaler (zero mean, unit variance).

    Args:
        data: A 2D numpy array where rows are samples and columns are features.

    Returns:
        A tuple containing the scaled data and the fitted scaler object
        (which is needed to inverse the transform or scale new data).
    """
    if not isinstance(data, np.ndarray) or data.ndim != 2:
        raise ValueError("Input data must be a 2D numpy array.")

    scaler = StandardScaler()
    scaled_data = scaler.fit_transform(data)

    logger.info(f"Scaled {data.shape[0]} samples and {data.shape[1]} features.")

    return scaled_data, scaler