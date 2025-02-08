import { Routes } from '@angular/router';

export const routes: Routes = [

    {
        path: 'index',
        loadChildren: () => import('./index/index.routes').then(m => m.routes)
    },
    {
        path: 'auth',
        loadChildren: () => import('./auth/auth.routes').then(m => m.routes)
    }

];
