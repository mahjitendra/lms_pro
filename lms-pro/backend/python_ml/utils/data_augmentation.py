import numpy as np
import cv2
import random
import logging

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def augment_image(image: np.ndarray) -> np.ndarray:
    """
    Applies a series of random augmentations to a single image.

    Args:
        image: An image represented as a numpy array (in BGR format from OpenCV).

    Returns:
        The augmented image as a numpy array.
    """
    augmented_image = image.copy()
    height, width, _ = augmented_image.shape

    # 1. Random Horizontal Flip
    if random.random() > 0.5:
        augmented_image = cv2.flip(augmented_image, 1)
        logger.debug("Applied horizontal flip.")

    # 2. Random Rotation
    if random.random() > 0.5:
        angle = random.uniform(-15, 15)
        rotation_matrix = cv2.getRotationMatrix2D((width / 2, height / 2), angle, 1)
        augmented_image = cv2.warpAffine(augmented_image, rotation_matrix, (width, height))
        logger.debug(f"Applied rotation with angle {angle:.2f}.")

    # 3. Random Brightness & Contrast Adjustment
    if random.random() > 0.5:
        alpha = random.uniform(0.8, 1.2) # Contrast control (1.0-3.0)
        beta = random.uniform(-20, 20)   # Brightness control (0-100)
        augmented_image = cv2.convertScaleAbs(augmented_image, alpha=alpha, beta=beta)
        logger.debug(f"Applied brightness/contrast with alpha={alpha:.2f}, beta={beta:.2f}.")

    # 4. Random Zoom
    if random.random() > 0.5:
        zoom_factor = random.uniform(0.8, 1.1)
        # Cropping
        h_crop = int(height / zoom_factor)
        w_crop = int(width / zoom_factor)
        h_start = random.randint(0, height - h_crop)
        w_start = random.randint(0, width - w_crop)

        cropped = augmented_image[h_start:h_start+h_crop, w_start:w_start+w_crop]
        # Resizing back to original size
        augmented_image = cv2.resize(cropped, (width, height), interpolation=cv2.INTER_LINEAR)
        logger.debug(f"Applied zoom with factor {zoom_factor:.2f}.")

    return augmented_image

# Example of how to use this in a training pipeline:
#
# for image in training_images:
#     augmented_image = augment_image(image)
#     # ... then use the augmented_image for training
#
# In a PyTorch/TensorFlow dataset, this would be part of the `__getitem__` method.