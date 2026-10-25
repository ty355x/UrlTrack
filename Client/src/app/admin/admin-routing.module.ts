import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SiteLayoutComponent } from '../platform/site-layout/site-layout.component';
import { AdminGuard } from './admin-guard';
import { LogsComponent } from './logs/logs.component';
import { UserListComponent } from './users/user-list/user-list.component';

const routes: Routes = [{
    path: 'app',
        component: SiteLayoutComponent,
        canActivateChild: [AdminGuard],
        children: [
            { path: 'logs', component: LogsComponent, data: { tabName: 'admin-logs' }},
            { path: 'users', component: UserListComponent, data: { tabName: 'admin-users' }},
        ],
}];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule],
})
export class AdminRoutingModule { }
