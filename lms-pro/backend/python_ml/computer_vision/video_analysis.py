import cv2
import logging

# Import the other computer vision modules we've created
from . import object_detection
from . import face_recognition

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def analyze(video_path: str, features: list, frame_interval: int = 30) -> dict:
    """
    Analyzes a video by processing its frames for specified features.

    Args:
        video_path: The path to the video file.
        features: A list of features to analyze (e.g., ['object_detection', 'face_recognition']).
        frame_interval: The number of frames to skip between analyses (e.g., 30 means analyze ~1 frame/sec for a 30fps video).

    Returns:
        A dictionary containing the aggregated analysis results.
    """
    logger.info(f"Starting video analysis for: {video_path} with features: {features}")

    # --- Load necessary models (simulation) ---
    models = {}
    if 'object_detection' in features:
        models['object_detection'] = object_detection.load_model()
    if 'face_recognition' in features:
        models['face_recognition'] = face_recognition.load_model()

    # --- Open the video file ---
    video_capture = cv2.VideoCapture(video_path)
    if not video_capture.isOpened():
        raise IOError(f"Cannot open video file: {video_path}")

    frame_count = 0
    results = {
        "video_metadata": {
            "fps": video_capture.get(cv2.CAP_PROP_FPS),
            "total_frames": int(video_capture.get(cv2.CAP_PROP_FRAME_COUNT)),
        },
        "analysis": []
    }

    # --- Loop through video frames ---
    while video_capture.isOpened():
        ret, frame = video_capture.read()
        if not ret:
            break # End of video

        # Process frame only at the specified interval
        if frame_count % frame_interval == 0:
            timestamp = frame_count / results['video_metadata']['fps']
            logger.info(f"Analyzing frame {frame_count} at timestamp {timestamp:.2f}s")

            # For simulation, we can't pass the frame directly, so we'll just use the path.
            # In a real scenario, you'd save the frame to a temp file or pass the numpy array.

            frame_results = {"timestamp": timestamp, "frame_index": frame_count}

            # --- Perform selected analyses ---
            if 'object_detection' in features:
                detected_objects = object_detection.detect_objects(video_path, models['object_detection'])
                frame_results['objects'] = detected_objects

            if 'face_recognition' in features:
                detected_faces = face_recognition.detect_faces(video_path, models['face_recognition'])
                frame_results['faces'] = detected_faces

            results["analysis"].append(frame_results)

        frame_count += 1

    video_capture.release()
    logger.info(f"Finished video analysis. Processed {len(results['analysis'])} frames.")

    return results