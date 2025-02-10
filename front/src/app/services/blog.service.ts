import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class BlogService {

  private apiUrl = environment.apiUrl + '/posts/';

  constructor(private http: HttpClient) { }

  // Get all blog posts
  blogs(): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-1': 'application/json'
    });
    return this.http.get<any>(`${this.apiUrl}`, { headers }).pipe(
      map(response => response),
      catchError(error => {
        console.error('Blog error', error);
        throw error;
      })
    );
  }

  // Get a single blog post by ID
  getBlog(slug: string): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });
    return this.http.get<any>(`${this.apiUrl}${slug}`, { headers }).pipe(
      map(response => response),
      catchError(error => {
        console.error('Blog error', error);
        throw error;
      })
    );
  }

  // Create a new blog post
  createBlog(postData: any): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });
    return this.http.post<any>(`${this.apiUrl}`, postData, { headers }).pipe(
      map(response => response),
      catchError(error => {
        console.error('Create blog error', error);
        throw error;
      })
    );
  }

  // Update a blog post by ID
  updateBlog(id: number, postData: any): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });
    return this.http.put<any>(`${this.apiUrl}${id}`, postData, { headers }).pipe(
      map(response => response),
      catchError(error => {
        console.error('Update blog error', error);
        throw error;
      })
    );
  }

  // Delete a blog post by ID
  deleteBlog(id: number): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });
    return this.http.delete<any>(`${this.apiUrl}${id}`, { headers }).pipe(
      map(response => response),
      catchError(error => {
        console.error('Delete blog error', error);
        throw error;
      })
    );
  }
}
