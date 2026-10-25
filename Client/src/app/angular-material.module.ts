import { NgModule } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MatDialogModule } from '@angular/material/dialog';
import { MatIconModule } from '@angular/material/icon';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';

@NgModule({
    imports: [
        MatButtonModule,
        MatDialogModule,
        MatIconModule,
        NgxMaterialTimepickerModule,
    ],

    exports: [
        MatButtonModule,
        MatDialogModule,
        MatIconModule,
        NgxMaterialTimepickerModule,
    ]
})
export class AngularMaterialModule {
}
