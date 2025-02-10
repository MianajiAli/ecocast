import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { BlogService } from '../../services/blog.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-create-post',
  imports: [CommonModule, ReactiveFormsModule],
  styleUrls: ['./create-post.component.css'],
  templateUrl: './create-post.component.html'
})
export class CreatePostComponent {
  postForm: FormGroup;
  selectedImage: string | ArrayBuffer | null = null;
  imageFile: File | undefined;


  constructor(private fb: FormBuilder, private blogService: BlogService) {
    this.postForm = this.fb.group({
      title: ['', Validators.required],
      slug: ['', Validators.required],
      content: ['', Validators.required],
      meta_title: [''],
      meta_description: [''],
      status: ['draft'],
      category: [''],
      thumbnail: [''],
    });
  }


  createPost() {
    if (this.postForm.invalid) {
      return;
    }

    const postData = this.postForm.value;
    console.log(1, postData); // Debugging: log the form data to see the actual content

    // Create a plain object for the post data (no FormData needed)
    const jsonData = {
      title: postData.title,
      slug: postData.slug,
      content: postData.content,
      meta_title: postData.meta_title,
      meta_description: postData.meta_description,
      status: postData.status,
      category: postData.category,
      thumbnail: postData.thumbnail, // If you need the image data, make sure it's in the desired format
    };

    this.blogService.createPost(jsonData).subscribe({
      next: (response) => {
        this.postForm.reset();
      },
      error: (error) => {
        console.error(error);
      }
    });
  }


}
