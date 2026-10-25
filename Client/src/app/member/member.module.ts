import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TrackedUrlListComponent } from './tracked-urls/tracked-url-list/tracked-url-list.component';
import { MemberRoutingModule } from './member-routing.module';
import { CoreModule } from '../core/core.module';
import { AngularMaterialModule } from '../angular-material.module';
import { EditTrackedUrlDialog } from './tracked-urls/edit-tracked-url-dialog/edit-tracked-url-dialog.component';
import { FormsModule } from '@angular/forms';
import { ImageListComponent } from './tracked-urls/image-list/image-list.component';
import { TrashedTrackedUrlListComponent } from './tracked-urls/trashed-tracked-url-list/trashed-tracked-url-list.component';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        AngularMaterialModule,
        CoreModule,
        MemberRoutingModule,
    ],

    declarations: [
        EditTrackedUrlDialog,
        ImageListComponent,
        TrackedUrlListComponent,
        TrashedTrackedUrlListComponent,
    ],
})
export class MemberModule {
}
