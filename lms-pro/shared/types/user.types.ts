export interface User {
  id: number;
  name: string;
  email: string;
  role: 'student' | 'instructor' | 'admin';
}

export interface Profile {
  userId: number;
  bio: string;
  avatarUrl: string;
}