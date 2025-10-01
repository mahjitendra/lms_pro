import uvicorn
from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse

from . import routes
from .middleware import SecretKeyMiddleware
from ..config import settings

# Create the FastAPI app instance
app = FastAPI(
    title="LMS Pro AI/ML Service",
    description="This service handles all AI and Machine Learning tasks for LMS Pro.",
    version="1.0.0"
)

# Add middleware to the application
# This will protect our endpoints by requiring a secret key
app.add_middleware(SecretKeyMiddleware)

# Include the API routes from the routes module
app.include_router(routes.router)

@app.get("/", tags=["Health Check"])
async def read_root():
    """
    Root endpoint to check if the service is running.
    """
    return {"message": "LMS Pro AI/ML Service is running."}


# Optional: Add a custom exception handler for better error formatting
@app.exception_handler(Exception)
async def generic_exception_handler(request: Request, exc: Exception):
    return JSONResponse(
        status_code=500,
        content={
            "status": "error",
            "message": "An internal server error occurred.",
            "detail": str(exc)
        },
    )

# This allows running the app directly with `python -m api.app`
if __name__ == "__main__":
    uvicorn.run(
        "app:app",
        host=settings.HOST,
        port=settings.PORT,
        reload=settings.RELOAD
    )