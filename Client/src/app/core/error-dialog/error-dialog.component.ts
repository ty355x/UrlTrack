import { Component, Inject } from "@angular/core";
import { MAT_DIALOG_DATA } from "@angular/material/dialog";

@Component({
    templateUrl: './error-dialog.component.html'
})
export class ErrorDialogComponent {
    title = '';
    message = '';

    constructor(@Inject(MAT_DIALOG_DATA) private data: { title: string, message: string }) {
        this.title = data.title;
        this.message = data.message;
    }
}
