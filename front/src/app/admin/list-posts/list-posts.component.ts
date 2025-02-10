import { Component } from '@angular/core';
import { BlogService } from '../../services/blog.service';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-list-posts',
  imports: [CommonModule, RouterLink],
  templateUrl: './list-posts.component.html',
  styleUrl: './list-posts.component.css'
})
export class ListPostsComponent {
  constructor(private blogService: BlogService) { }
  ngOnInit(): void {
    this.getPosts()
  }
  blogs !: any[]
  getPosts() {
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
  deletePost(slug: string) {
    this.blogService.deletePost(slug).subscribe(
      (response) => {
        console.log(' successfully deleted', response);
        this.blogs = response.data
        this.getPosts()
      },
      (error) => {
        console.error('deleting post failed', error);
        this.getPosts()
      }
    )
  }
}



