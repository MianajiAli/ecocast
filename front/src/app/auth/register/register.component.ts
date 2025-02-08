import { Component } from '@angular/core';
import { AuthService } from '../auth.service'; // Import your AuthService
import { Router } from '@angular/router'; // Import Router for navigation
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-register',
  imports: [FormsModule, CommonModule],
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  formData = {
    name: '',
    phone: '',
    password: '',

  };

  constructor(private authService: AuthService, private router: Router) { }

  // This method handles the registration process
  onRegister() {
    // Call the register method from AuthService
    this.authService.register(this.formData.name, this.formData.phone, this.formData.password)
      .subscribe(
        response => {
          // Check the response
          console.log('Registration successful', response);

          // Navigate to the dashboard after a successful registration

          this.router.navigate(['/']);

        },
        error => {
          console.error('Registration error', error);
          // Handle error if necessary
        }
      );
  }

}
