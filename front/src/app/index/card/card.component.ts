import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-card',
  templateUrl: './card.component.html',
  imports: [CommonModule],
  styleUrls: ['./card.component.css'] // Or scss, less, etc.
})
export class CardComponent {

  @Input() title: string = ''; // Default value to avoid initial undefined.
  @Input() count: number = 10;
  @Input() imageUrl: string = ''; // Optional image URL
  @Input() link: string = ''; // Default value to avoid initial undefined.
  @Input() description = 'lorem20 ksamdklawiosd sdklajd dasdklazs as l;dja sakldk;as'// Default value to avoid initial undefined.

  // Optional: You might want to handle image loading errors gracefully
  imageLoadError: boolean = false;

  handleImageError() {
    this.imageLoadError = true;
  }
}
