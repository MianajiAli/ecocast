import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  imports: [FormsModule],
  templateUrl: './login.component.html',
})
export class LoginComponent {

  phone: string = '';
  password: string = '';

  constructor(private authService: AuthService, private router: Router) { }

  onLogin(): void {
    this.authService.login(this.phone, this.password).subscribe(
      (response) => {
        console.log('Logged in successfully', response);
        // You can navigate to a protected route here
        this.router.navigate(['/']);

      },
      (error) => {
        console.error('Login failed', error);
      }
    );
  }
}
