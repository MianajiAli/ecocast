import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AuthorService {

  private apiUrl = environment.apiUrl;
  private token = localStorage.getItem('authToken'); // Get the token from localStorage

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    return new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${this.token}`
    });
  }

  // Get all posts
  getAuthors(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/getAuthors`, { headers: this.getHeaders() }).pipe(
      map(response => response),
      catchError(error => throwError(() => new Error(error)))
    );
  }

}
