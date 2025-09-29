export const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api/v1';

export const Endpoints = {
  // Auth
  LOGIN: '/auth/login',
  REGISTER: '/auth/register',
  LOGOUT: '/auth/logout',

  // Courses
  COURSES: '/courses',
  COURSE_DETAIL: (id) => `/courses/${id}`,

  // AI
  PREDICT: '/ai/predict',
};