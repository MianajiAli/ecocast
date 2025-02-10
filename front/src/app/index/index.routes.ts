import { Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { BlogsComponent } from './blogs/blogs.component';
import { BlogPostComponent } from './blog-post/blog-post.component';

export const routes: Routes = [

    {
        path: '',
        component: HomeComponent
    },
    {
        path: 'blog',
        component: BlogsComponent
    },
    {
        path: 'blog/:slug',
        component: BlogPostComponent
    },


];
