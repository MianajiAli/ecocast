import { Component } from '@angular/core';
import { AuthorService } from '../../services/author.service';
import { BlogsComponent } from '../blogs/blogs.component';
import { CardComponent } from '../card/card.component';
import { CommonModule } from '@angular/common';
import { BlogService } from '../../services/blog.service';


@Component({
  selector: 'app-home',
  imports: [BlogsComponent, CardComponent, CommonModule],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent {
  constructor(private authorService: AuthorService, private blogService: BlogService) { }
  ngOnInit(): void {
    this.getAuthors()
    this.getPosts()
  }
  podcasts!: any[]
  episodes!: any[]
  getAuthors() {
    this.authorService.getAuthors().subscribe(
      (response) => {
        this.podcasts = response.data
      },
      (error) => {
        console.error(' failed', error);
      }
    )
  }
  getPosts() {
    this.blogService.getPosts().subscribe(
      (response) => {
        console.log('Logged in successfully', response);
        this.episodes = response.data
      },
      (error) => {
        console.error('Login failed', error);
      }
    )
  }



}
