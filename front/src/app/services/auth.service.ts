import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = environment.apiUrl;  // Backend authentication URL

  constructor(private http: HttpClient) { }

  // Login method
  login(phone: string, password: string): Observable<any> {
    const loginData = { phone, password };
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });

    return this.http.post<any>(`${this.apiUrl}/auth/login`, loginData, { headers }).pipe(
      map(response => {
        if (response && response.access_token) {
          // Store the access token and refresh token in localStorage
          localStorage.setItem('authToken', response.access_token);
          localStorage.setItem('refreshToken', response.refresh_token);
        }
        return response;
      }),
      catchError(error => {
        console.error('Login error', error);
        throw error;
      })
    );
  }

  // Register method
  register(name: string, username: string, phone: string, password: string): Observable<any> {
    const registerData = { name, username, phone, password };
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });

    return this.http.post<any>(`${this.apiUrl}/auth/register`, registerData, { headers }).pipe(
      map(response => {
        if (response && response.access_token) {
          // Store the access token and refresh token in localStorage
          localStorage.setItem('authToken', response.access_token);
          localStorage.setItem('refreshToken', response.refresh_token);
        }
        return response;
      }),
      catchError(error => {
        console.error('Registration error', error);
        throw error;
      })
    );
  }

  // Logout method
  logout(): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });

    // Send the logout request to the backend
    return this.http.post<any>(`${this.apiUrl}/auth/logout`, {}, { headers }).pipe(
      map(response => {
        // Remove tokens from localStorage
        localStorage.removeItem('authToken');
        localStorage.removeItem('refreshToken');
        return response;
      }),
      catchError(error => {
        console.error('Logout error', error);
        throw error;
      })
    );
  }

  // Refresh token method
  refreshToken(): Observable<any> {
    const refreshToken = localStorage.getItem('refreshToken');
    if (!refreshToken) {
      throw new Error('No refresh token available');
    }

    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });

    return this.http.post<any>(`${this.apiUrl}/auth/refresh`, { refresh_token: refreshToken }, { headers }).pipe(
      map(response => {
        if (response && response.access_token) {
          // Update the access token in localStorage
          localStorage.setItem('authToken', response.access_token);
        }
        return response;
      }),
      catchError(error => {
        console.error('Refresh token error', error);
        throw error;
      })
    );
  }
  getRoles(): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
    });

    return this.http.get<any>(`${this.apiUrl}/check-roles`, { headers }).pipe(
      map(response => {
        // Assuming the roles are in response.roles, adjust if necessary
        return response.roles; // Returning only the roles
      })
    );
  }
  getAuthor(username: string): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
    });

    return this.http.get<any>(`${this.apiUrl}/author/${username}`, { headers }).pipe(
      map(response => {
        // Assuming the roles are in response.roles, adjust if necessary
        return response.user; // Returning only the roles
      })
    );
  }


  // Check if the user is authenticated
  isAuthenticated(): boolean {
    return !!localStorage.getItem('authToken');
  }

  // Get the access token from localStorage
  getAuthToken(): string | null {
    return localStorage.getItem('authToken');
  }

  // Get the refresh token from localStorage
  getRefreshToken(): string | null {
    return localStorage.getItem('refreshToken');
  }
}
