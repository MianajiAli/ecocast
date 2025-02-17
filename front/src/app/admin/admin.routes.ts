import { Routes } from '@angular/router';
import { BlogComponent } from './blog/blog.component';
import { CreatePostComponent } from './create-post/create-post.component';
import { AdminComponent } from './admin.component';
import { UpdatePostComponent } from './update-post/update-post.component';
import { ListPostsComponent } from './list-posts/list-posts.component';

export const routes: Routes = [
    {
        path: '',
        component: AdminComponent
    },
    {
        path: 'blog',
        children: [
            { path: '', component: ListPostsComponent },
            { path: 'add', component: CreatePostComponent },
            { path: 'update/:slug', component: UpdatePostComponent }, // Use update/:slug for updating posts

        ]
    },
];
