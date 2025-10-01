import logging
import random
from datetime import datetime

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# A simple knowledge base for our rule-based chatbot simulation
KNOWLEDGE_BASE = {
    "greeting": ["Hello! How can I help you today?", "Hi there! What can I do for you?", "Greetings! Ask me anything about our courses."],
    "courses": ["We have a wide range of courses in programming, data science, and AI. Is there a specific topic you're interested in?", "You can browse our full course catalog on the 'Courses' page."],
    "enrollment": ["To enroll in a course, simply navigate to the course page and click the 'Enroll Now' button."],
    "pricing": ["Our courses are available through a monthly subscription. You can find more details on the 'Pricing' page."],
    "support": ["If you need help, you can contact our support team through the 'Support' page. What is the issue?"],
    "default": ["I'm not sure how to answer that. Could you try rephrasing your question?", "That's a good question. Let me find someone who can help with that.", "I'm still learning! You can find more information on our help page."]
}

def load_model():
    """
    Placeholder for loading a conversational AI model.
    In a real application, this could be a large language model (LLM) like GPT
    or a retrieval-based model fine-tuned on your specific data.
    """
    logger.info("Loading conversational AI model (simulation)...")
    # from transformers import pipeline
    # chatbot = pipeline("conversational", model="microsoft/DialoGPT-medium")
    return "dummy_chatbot_model"

def generate_response(user_message: str, conversation_history: list = [], model: any = None) -> dict:
    """
    Generates a chatbot response to a user's message.

    Args:
        user_message: The latest message from the user.
        conversation_history: A list of previous turns in the conversation.
        model: The loaded chatbot model.

    Returns:
        A dictionary containing the chatbot's response text and updated history.
    """
    if model is None:
        model = load_model()

    logger.info(f"Generating chatbot response for message: '{user_message}' (simulation)")

    # --- Real LLM Inference Would Happen Here ---
    # 1. Format the conversation history into the format expected by the model.
    # 2. Pass the history and new message to the model pipeline.
    #    new_user_input = {"role": "user", "content": user_message}
    #    response = chatbot(conversation_history + [new_user_input])
    #    bot_response_text = response[0]['generated_text']

    # --- Placeholder Logic ---
    # We will use a simple keyword-based system to select a response from our knowledge base.
    user_message_lower = user_message.lower()
    response_category = "default"

    if any(word in user_message_lower for word in ["hello", "hi", "hey"]):
        response_category = "greeting"
    elif any(word in user_message_lower for word in ["course", "learn", "subject"]):
        response_category = "courses"
    elif any(word in user_message_lower for word in ["enroll", "join", "sign up"]):
        response_category = "enrollment"
    elif any(word in user_message_lower for word in ["price", "cost", "how much"]):
        response_category = "pricing"
    elif any(word in user_message_lower for word in ["help", "support", "issue"]):
        response_category = "support"

    # Select a random response from the chosen category
    bot_response_text = random.choice(KNOWLEDGE_BASE[response_category])

    # Update the conversation history
    new_history = conversation_history + [
        {"sender": "user", "message": user_message, "timestamp": datetime.utcnow().isoformat()},
        {"sender": "bot", "message": bot_response_text, "timestamp": datetime.utcnow().isoformat()},
    ]

    logger.info(f"Generated response: '{bot_response_text}'")

    return {
        "response_text": bot_response_text,
        "conversation_history": new_history
    }