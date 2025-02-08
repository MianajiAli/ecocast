import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-blogs',
  imports: [CommonModule, RouterLink],
  templateUrl: './blogs.component.html',
  styleUrl: './blogs.component.css'
})
export class BlogsComponent {
  blogs = [
    {
      id: 1,
      title: 'Understanding Angular',
      excerpt: 'A beginner\'s guide to getting started with Angular.',
      image: 'https://via.placeholder.com/400x200',
    },
    {
      id: 2,
      title: 'Tailwind CSS in Angular',
      excerpt: 'How to integrate Tailwind CSS into your Angular project.',
      image: 'https://via.placeholder.com/400x200',
    },
    {
      id: 3,
      title: 'Advanced JavaScript Techniques',
      excerpt: 'Dive deeper into JavaScript with advanced techniques.',
      image: 'https://via.placeholder.com/400x200',
    }
  ];
}
