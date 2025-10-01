from fastapi import APIRouter, HTTPException, Body
from pydantic import BaseModel, Field
from typing import List, Dict, Any

# Import our core logic functions (which we will create in the next step)
from ..computer_vision import image_classification, object_detection, video_analysis
from ..machine_learning import recommendation
from ..deep_learning import model_trainer

# Create an API router
router = APIRouter(
    prefix="/api/v1",
    tags=["AI Endpoints"]
)

# --- Pydantic Models for Request/Response Validation ---

class TrainRequest(BaseModel):
    model_id: int
    model_type: str
    dataset_path: str
    hyperparameters: Dict[str, Any] = Field(default_factory=dict)

class TrainResponse(BaseModel):
    status: str = "success"
    message: str
    new_version: str
    metrics: Dict[str, float]

class AnalyzeVideoRequest(BaseModel):
    video_path: str
    analysis_types: List[str] = ["transcription", "object_detection"]

class AnalyzeVideoResponse(BaseModel):
    status: str = "success"
    data: Dict[str, Any] # e.g., {"transcription": "...", "objects": [...]}

class RecommendationRequest(BaseModel):
    user_id: int
    user_data: Dict[str, Any]

class RecommendationResponse(BaseModel):
    status: str = "success"
    recommended_course_ids: List[int]

# --- API Endpoints ---

@router.post("/train", response_model=TrainResponse)
async def train_model_endpoint(request: TrainRequest):
    """
    Endpoint to trigger model training.
    """
    try:
        result = model_trainer.start_training(
            model_type=request.model_type,
            dataset_path=request.dataset_path,
            hyperparameters=request.hyperparameters
        )
        return {
            "message": f"Training started successfully for model {request.model_id}",
            "new_version": result['version'],
            "metrics": result['accuracy']
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/analyze-video", response_model=AnalyzeVideoResponse)
async def analyze_video_endpoint(request: AnalyzeVideoRequest):
    """
    Endpoint for video analysis.
    """
    try:
        analysis_results = video_analysis.analyze(
            video_path=request.video_path,
            features=request.analysis_types
        )
        return {"data": analysis_results}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/recommendations", response_model=RecommendationResponse)
async def get_recommendations_endpoint(request: RecommendationRequest):
    """
    Endpoint to get course recommendations for a user.
    """
    try:
        recommended_ids = recommendation.get_recommendations(
            user_id=request.user_id,
            user_profile=request.user_data
        )
        return {"recommended_course_ids": recommended_ids}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# Add more endpoints for image classification, object detection, etc. here...