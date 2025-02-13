import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { BlogService } from '../../services/blog.service';
import { CardComponent } from '../card/card.component';
@Component({
  selector: 'app-blogs',
  imports: [CommonModule, CardComponent],
  templateUrl: './blogs.component.html',
  styleUrl: './blogs.component.css'
})
export class BlogsComponent {
  constructor(private blogService: BlogService) { }
  ngOnInit(): void {
    this.blogService.getPosts().subscribe(
      (response) => {
        console.log('Logged in successfully', response);
        this.blogs = response.data
      },
      (error) => {
        console.error('Login failed', error);
      }
    )
  }
  blogs !: any[]
}
