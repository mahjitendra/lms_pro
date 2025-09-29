export interface Course {
  id: number;
  title: string;
  description: string;
  instructorId: number;
  modules: Module[];
}

export interface Module {
  id: number;
  title: string;
  lessons: Lesson[];
}

export interface Lesson {
  id: number;
  title: string;
  content: string; // Can be video URL, text content, etc.
}

export interface Enrollment {
  id: number;
  userId: number;
  courseId: number;
  progress: number;
}