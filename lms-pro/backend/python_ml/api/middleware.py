from fastapi import Request, HTTPException, status
from starlette.middleware.base import BaseHTTPMiddleware, RequestResponseEndpoint
from starlette.responses import Response
import time

from ..config import settings

class SecretKeyMiddleware(BaseHTTPMiddleware):
    """
    Middleware to protect endpoints by requiring a shared secret key.
    """
    async def dispatch(
        self, request: Request, call_next: RequestResponseEndpoint
    ) -> Response:
        # The root health check endpoint and docs should be public and not require a key.
        if request.url.path in ["/", "/docs", "/openapi.json"]:
            return await call_next(request)

        # Get the secret key from the request headers
        client_secret_key = request.headers.get("X-Secret-Key")

        # Check if the key is present and matches the one in our settings
        if not client_secret_key or client_secret_key != settings.SHARED_SECRET_KEY:
            return Response(
                content="Access denied: Invalid or missing secret key.",
                status_code=status.HTTP_403_FORBIDDEN
            )

        # If the key is valid, proceed with the request
        response = await call_next(request)
        return response