import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { BlogService } from '../../services/blog.service';
@Component({
  selector: 'app-blogs',
  imports: [CommonModule, RouterLink],
  templateUrl: './blogs.component.html',
  styleUrl: './blogs.component.css'
})
export class BlogsComponent {
  constructor(private blogService: BlogService) { }
  ngOnInit(): void {
    this.blogService.blogs().subscribe(
      (response) => {
        console.log('Logged in successfully', response);
        this.blogs = response
      },
      (error) => {
        console.error('Login failed', error);
      }
    )
  }
  blogs !: any[]
}
