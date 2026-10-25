import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CoreModule } from '../core/core.module';
import { AngularMaterialModule } from '../angular-material.module';
import { FormsModule } from '@angular/forms';
import { AdminRoutingModule } from './admin-routing.module';
import { LogsComponent } from './logs/logs.component';
import { UserListComponent } from './users/user-list/user-list.component';
import { EditUserDialog } from './users/edit-user-dialog/edit-user-dialog.component';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        AngularMaterialModule,
        CoreModule,
        AdminRoutingModule,
    ],

    declarations: [
        EditUserDialog,
        LogsComponent,
        UserListComponent,
    ],
})
export class AdminModule {
}
