import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-card',
  templateUrl: './card.component.html',
  imports: [CommonModule],
  styleUrls: ['./card.component.css'] // Or scss, less, etc.
})
export class CardComponent {

  @Input() podcastName: string = ''; // Default value to avoid initial undefined.
  @Input() followerCount: number = 0;
  @Input() imageUrl: string = ''; // Optional image URL

  // Optional: You might want to handle image loading errors gracefully
  imageLoadError: boolean = false;

  handleImageError() {
    this.imageLoadError = true;
  }
}
