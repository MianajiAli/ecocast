import { Routes } from '@angular/router';

export const routes: Routes = [
    {
        path: '',
        loadChildren: () => import('./index/index.routes').then(m => m.routes)
    }
];
