import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { BlogService } from '../../services/blog.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-blog-post',
  imports: [CommonModule, RouterLink],
  templateUrl: './blog-post.component.html',
  styleUrls: ['./blog-post.component.css']
})
export class BlogPostComponent implements OnInit {
  slug: string | null = '';
  blog: any; // Store a single blog post

  constructor(private route: ActivatedRoute, private blogService: BlogService) { }

  ngOnInit() {
    this.slug = this.route.snapshot.paramMap.get('slug');

    if (this.slug) {
      this.blogService.getPost(this.slug).subscribe(
        (response) => {
          console.log('Fetched blog post successfully', response);
          this.blog = response.data;
        },
        (error) => {
          console.error('Failed to fetch blog post', error);
        }
      );
    } else {
      console.error('Slug is null or undefined');
    }
  }
}
