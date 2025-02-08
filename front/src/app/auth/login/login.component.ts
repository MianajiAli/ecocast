import { Component } from '@angular/core';
import { AuthService } from '../auth.service';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-login',
  imports: [FormsModule],
  templateUrl: './login.component.html',
})
export class LoginComponent {

  phone: string = '';
  password: string = '';

  constructor(private authService: AuthService) { }

  onLogin(): void {
    this.authService.login(this.phone, this.password).subscribe(
      (response) => {
        console.log('Logged in successfully', response);
        // You can navigate to a protected route here
      },
      (error) => {
        console.error('Login failed', error);
      }
    );
  }
}
