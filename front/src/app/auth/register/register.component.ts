import { Component } from '@angular/core';
import { AuthService } from '../auth.service'; // Import your AuthService
import { Router } from '@angular/router'; // Import Router for navigation

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  formData = {
    name: '',
    phone: '',
    password: '',
    confirmPassword: ''
  };
  passwordMismatch: boolean = false;

  constructor(private authService: AuthService, private router: Router) { }

  // This method handles the registration process
  onRegister() {
    if (this.formData.password !== this.formData.confirmPassword) {
      this.passwordMismatch = true;
      return;
    }
    this.passwordMismatch = false;

    // Call the register method from AuthService
    this.authService.register(this.formData.name, this.formData.phone, this.formData.password)
      .subscribe(
        response => {
          // Navigate to a different page on successful registration (e.g., dashboard)
          this.router.navigate(['/dashboard']);
        },
        error => {
          console.error('Registration error', error);
          // You can handle error responses here if necessary
        }
      );
  }
}
