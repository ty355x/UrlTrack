import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ErrorDialogComponent } from './error-dialog/error-dialog.component';
import { AngularMaterialModule } from '../angular-material.module';
import { LoadingComponent } from './loading/loading.component';
import { DateTimePipe } from './pipes';
import { MenuDropDownComponent } from './menu-dropdown/menu-dropdown.component';
import { PromptDialogComponent } from './prompt-dialog/prompt-dialog.component';

@NgModule({
    imports: [
        CommonModule,
        AngularMaterialModule,
    ],

    declarations: [
        DateTimePipe,
        ErrorDialogComponent,
        LoadingComponent,
        MenuDropDownComponent,
        PromptDialogComponent,
    ],

    exports: [
        DateTimePipe,
        LoadingComponent,
        MenuDropDownComponent,
    ],
})
export class CoreModule {
}
