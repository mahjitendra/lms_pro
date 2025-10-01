import numpy as np
import logging
from sklearn.metrics import (
    accuracy_score,
    precision_recall_fscore_support,
    confusion_matrix,
    mean_squared_error,
    r2_score
)

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def get_classification_metrics(y_true: np.ndarray, y_pred: np.ndarray) -> dict:
    """
    Calculates a set of standard metrics for a classification task.

    Args:
        y_true: A numpy array of true labels.
        y_pred: A numpy array of predicted labels.

    Returns:
        A dictionary containing accuracy, precision, recall, and f1-score.
    """
    if y_true.shape != y_pred.shape:
        raise ValueError("Shape of true labels and predicted labels must be the same.")

    accuracy = accuracy_score(y_true, y_pred)

    # Calculate precision, recall, and f1-score, averaged for multi-class problems
    precision, recall, f1, _ = precision_recall_fscore_support(
        y_true, y_pred, average='weighted'
    )

    metrics = {
        "accuracy": float(accuracy),
        "precision": float(precision),
        "recall": float(recall),
        "f1_score": float(f1)
    }

    logger.info(f"Calculated classification metrics: {metrics}")
    return metrics

def get_confusion_matrix(y_true: np.ndarray, y_pred: np.ndarray) -> np.ndarray:
    """
    Computes the confusion matrix for a classification task.

    Args:
        y_true: A numpy array of true labels.
        y_pred: A numpy array of predicted labels.

    Returns:
        A numpy array representing the confusion matrix.
    """
    matrix = confusion_matrix(y_true, y_pred)
    logger.info(f"Generated confusion matrix of shape: {matrix.shape}")
    return matrix

def get_regression_metrics(y_true: np.ndarray, y_pred: np.ndarray) -> dict:
    """
    Calculates a set of standard metrics for a regression task.

    Args:
        y_true: A numpy array of true continuous values.
        y_pred: A numpy array of predicted continuous values.

    Returns:
        A dictionary containing Mean Squared Error (MSE) and R-squared score.
    """
    if y_true.shape != y_pred.shape:
        raise ValueError("Shape of true values and predicted values must be the same.")

    mse = mean_squared_error(y_true, y_pred)
    r2 = r2_score(y_true, y_pred)

    metrics = {
        "mean_squared_error": float(mse),
        "r_squared": float(r2)
    }

    logger.info(f"Calculated regression metrics: {metrics}")
    return metrics