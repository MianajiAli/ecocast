import { Routes } from '@angular/router';
import { AdminAuthGuard } from './auth.guard';

export const routes: Routes = [

    {
        path: '',
        loadChildren: () => import('./index/index.routes').then(m => m.routes)
    },
    {
        path: 'auth',
        loadChildren: () => import('./auth/auth.routes').then(m => m.routes)
    },
    {
        path: 'panel',
        loadChildren: () => import('./admin/admin.routes').then(m => m.routes),
        canActivate: [AdminAuthGuard]
    }
];
