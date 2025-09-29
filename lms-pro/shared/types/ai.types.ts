export interface AIModel {
  id: string;
  name: string;
  description: string;
  type: 'classification' | 'regression' | 'object-detection';
  version: string;
}

export interface Prediction {
  id: string;
  modelId: string;
  input: any;
  output: any;
  createdAt: Date;
}