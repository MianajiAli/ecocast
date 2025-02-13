import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-author',
  imports: [CommonModule],
  templateUrl: './author.component.html',
  styleUrl: './author.component.css'
})
export class AuthorComponent {
  slug: string | null = '';
  user: any; // Store a single blog post

  constructor(private route: ActivatedRoute, private authService: AuthService) { }

  ngOnInit() {
    this.slug = this.route.snapshot.paramMap.get('slug');

    if (this.slug) {
      this.authService.getAuthor(this.slug).subscribe(
        (response) => {
          console.log('Fetched blog post successfully', response);
          this.user = response;
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
