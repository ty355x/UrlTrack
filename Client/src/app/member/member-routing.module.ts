import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SiteLayoutComponent } from '../platform/site-layout/site-layout.component';
import { TrackedUrlListComponent } from './tracked-urls/tracked-url-list/tracked-url-list.component';
import { MemberGuard } from './member-guard';
import { ImageListComponent } from './tracked-urls/image-list/image-list.component';
import { TrashedTrackedUrlListComponent } from './tracked-urls/trashed-tracked-url-list/trashed-tracked-url-list.component';

const routes: Routes = [{
    path: 'app',
        component: SiteLayoutComponent,
        canActivateChild: [MemberGuard],
        children: [
            { path: '', redirectTo: 'tracked-urls', pathMatch: 'full', },
            { path: 'trashed-tracked-urls', component: TrashedTrackedUrlListComponent, data: { tabName: 'app-tracked-urls' }},
            { path: 'tracked-urls', component: TrackedUrlListComponent, data: { tabName: 'app-tracked-urls' }},
            { path: 'tracked-url-images/:id', component: ImageListComponent, data: { tabName: 'app-tracked-urls' }},
        ],
}];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule],
})
export class MemberRoutingModule { }
