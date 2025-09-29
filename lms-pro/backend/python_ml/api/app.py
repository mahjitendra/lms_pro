from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/')
def home():
    return "Python ML Service is running!"

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json(force=True)
    # Add your prediction logic here
    prediction = {'result': 'This is a dummy prediction'}
    return jsonify(prediction)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)