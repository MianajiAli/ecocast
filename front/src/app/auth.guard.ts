import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AuthService } from './services/auth.service';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AdminAuthGuard implements CanActivate {
  constructor(private authService: AuthService, private router: Router) { }

  canActivate(): Observable<boolean> | boolean {
    return new Observable(observer => {
      this.authService.getRoles().subscribe(
        (roles) => {
          if (roles && roles.includes('admin') || roles.includes('manager')) {
            // User has the 'admin' role, allow access
            observer.next(true);
          } else {
            // User does not have the 'admin' role, redirect to login
            this.router.navigate(['/']);
            observer.next(false);
          }
        },
        (error) => {
          // Handle error, e.g., navigate to login if fetching roles fails
          console.error('Error checking roles', error);
          this.router.navigate(['/']);
          observer.next(false);
        }
      );
    });
  }
}
