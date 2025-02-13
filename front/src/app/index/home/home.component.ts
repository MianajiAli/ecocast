import { Component } from '@angular/core';
import { AuthorService } from '../../services/author.service';
import { BlogsComponent } from '../blogs/blogs.component';
import { CardComponent } from '../card/card.component';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-home',
  imports: [BlogsComponent, CardComponent, CommonModule],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent {
  constructor(private authorService: AuthorService) { }
  ngOnInit(): void {
    this.authorService.getAuthors().subscribe(
      (response) => {
        this.podcasts = response.data
      },
      (error) => {
        console.error(' failed', error);
      }
    )
  }
  podcasts !: any[]



  episodes = [
    { name: 'اکوکست', followers: 1250, imageUrl: 'url_to_your_image1.jpg' },
    { name: 'اسم پادکست', followers: 900, imageUrl: 'url_to_your_image2.jpg' },
    { name: 'پادکست جدید', followers: 500, imageUrl: 'url_to_your_image3.jpg' },
    // Add more podcast data as needed
  ];
}
