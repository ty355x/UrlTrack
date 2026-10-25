import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './platform/login/login.component';
import { PageNotFoundComponent } from './platform/page-not-found/page-not-found-component';

export const routes: Routes = [
    { path: 'login', component: LoginComponent },
    { path: '', redirectTo: 'app/tracked-urls', pathMatch: 'full' },
    { path: '404', component: PageNotFoundComponent, }
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule { }
