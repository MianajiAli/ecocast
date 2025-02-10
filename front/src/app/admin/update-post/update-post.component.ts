import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { BlogService } from '../../services/blog.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-update-post',
  imports: [CommonModule, ReactiveFormsModule],
  styleUrls: ['./update-post.component.css'],
  templateUrl: './update-post.component.html'
})
export class UpdatePostComponent implements OnInit {
  postForm: FormGroup;
  slug: string;

  constructor(
    private fb: FormBuilder,
    private blogService: BlogService,
    private activatedRoute: ActivatedRoute,
    private router: Router
  ) {
    this.slug = this.activatedRoute.snapshot.paramMap.get('slug')!;  // Get the slug from the route parameters
    this.postForm = this.fb.group({
      title: ['', Validators.required],
      slug: ['', Validators.required],
      content: ['', Validators.required],
      meta_title: [''],
      meta_description: [''],
      status: ['draft'],
      category: [''],
      thumbnail: [''],  // To store the image URL
    });
  }

  ngOnInit(): void {
    console.log('Slug from route:', this.slug);  // Debug log to check slug
    this.loadPostData();  // Call to load post data based on the slug
  }

  loadPostData(): void {
    console.log('Loading post data for slug:', this.slug);  // Debug log

    this.blogService.getPost(this.slug).subscribe({
      next: (response) => {
        // Access the post data from the response
        const post = response.data;

        console.log('Post data loaded:', post);  // Log the post data received from the API

        // Ensure the form is initialized and patch values after data is received
        this.postForm.patchValue({
          title: post.title,
          slug: post.slug,
          content: post.content,
          meta_title: post.meta_title,
          meta_description: post.meta_description,
          status: post.status,
          category: post.category,
          thumbnail: post.thumbnail,  // Assuming this is a URL
        });

        console.log('Form after patching:', this.postForm.value);  // Check if form values are updated
      },
      error: (error) => {
        console.error('Error loading post:', error);  // Log any errors from the API request
      },
    });
  }




  updatePost() {
    if (this.postForm.invalid) {
      return;
    }

    const postData = this.postForm.value;
    console.log('Post data to update:', postData); // Debugging: log the form data

    // Create a plain object for the post data (no FormData needed)
    const jsonData = {
      title: postData.title,
      slug: postData.slug,
      content: postData.content,
      meta_title: postData.meta_title,
      meta_description: postData.meta_description,
      status: postData.status,
      category: postData.category,
      thumbnail: postData.thumbnail,  // Keep the URL for the thumbnail
    };

    this.blogService.updatePost(this.slug, jsonData).subscribe({
      next: (response) => {
        console.log('Post updated successfully:', response);
        this.router.navigate(['/posts']);  // Redirect after successful update
      },
      error: (error) => {
        console.error('Error updating post:', error);
        // Handle the error appropriately
      },
    });
  }
}
