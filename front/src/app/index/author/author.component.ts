import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { CardComponent } from '../card/card.component';

@Component({
  selector: 'app-author',
  imports: [CommonModule, CardComponent],
  templateUrl: './author.component.html',
  styleUrl: './author.component.css'
})
export class AuthorComponent {
  slug: string | null = '';
  user: any; // Store a single blog post
  blogs: any;

  constructor(private route: ActivatedRoute, private authService: AuthService) { }

  ngOnInit() {
    this.slug = this.route.snapshot.paramMap.get('slug');

    if (this.slug) {
      this.authService.getAuthor(this.slug).subscribe(
        (response) => {
          console.log('Fetched blog post successfully', response.posts);
          this.user = response.user;
          this.blogs = response.post
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
