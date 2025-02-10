import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class BlogService {

  private apiUrl = environment.apiUrl + '/posts/';
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
  getPosts(): Observable<any> {
    return this.http.get<any>(this.apiUrl, { headers: this.getHeaders() }).pipe(
      map(response => response),
      catchError(error => throwError(() => new Error(error)))
    );
  }

  // Get a single post by slug
  getPost(slug: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}${slug}`, { headers: this.getHeaders() }).pipe(
      map(response => response),
      catchError(error => throwError(() => new Error(error)))
    );
  }

  // Create a new post (Supports Image Upload)
  createPost(jsonData: any, imageFile?: File): Observable<any> {


    const data = {
      title: jsonData.title,
      slug: jsonData.slug,
      content: jsonData.content,
      meta_title: jsonData.meta_title || '',
      meta_description: jsonData.meta_description || '',
      status: jsonData.status || 'draft',
      category: jsonData.category || '',
      tags: JSON.stringify(jsonData.tags || []),
      thumbnail: jsonData.thumbnail || '',
    };

    return this.http.post<any>(this.apiUrl, data, { headers: this.getHeaders() }).pipe(
      map(response => response),
      catchError(error => throwError(() => new Error(error)))
    );
  }

  // Update a post by slug (Supports Image Upload)
  updatePost(slug: string, jsonData: any, imageFile?: File): Observable<any> {
    const data = {
      title: jsonData.title,
      slug: jsonData.slug,
      content: jsonData.content,
      meta_title: jsonData.meta_title || '',
      meta_description: jsonData.meta_description || '',
      status: jsonData.status || 'draft',
      category: jsonData.category || '',
      tags: JSON.stringify(jsonData.tags || []),
      thumbnail: jsonData.thumbnail || '', // Use base64 image if available, otherwise fallback to existing thumbnail
    };

    return this.http.put<any>(`${this.apiUrl}${slug}`, data, { headers: this.getHeaders() }).pipe(
      map(response => response),
      catchError(error => throwError(() => new Error(error)))
    );
  }



  // Delete a post by slug
  deletePost(slug: string): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}${slug}`, { headers: this.getHeaders() }).pipe(
      map(response => response),
      catchError(error => throwError(() => new Error(error)))
    );
  }
}
