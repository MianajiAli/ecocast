import { Component } from '@angular/core';
import { TeamComponent } from '../team/team.component';
import { EpisodesComponent } from '../episodes/episodes.component';

@Component({
  selector: 'app-home',
  imports: [TeamComponent, EpisodesComponent],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent {

}
