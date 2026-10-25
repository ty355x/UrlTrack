import { Component, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA } from "@angular/material/dialog";

@Component({
    templateUrl: './prompt-dialog.component.html'
})
export class PromptDialogComponent {
    title = '';
    message = '';
    isMessageHtml = false;

    constructor(@Inject(MAT_DIALOG_DATA) private data: { title: string, message: string, isMessageHtml: boolean },
        private dialogRef: MatDialogRef<PromptDialogComponent>) {
        this.title = data.title;
        this.message = data.message;
        this.isMessageHtml = data.isMessageHtml;
    }

    ok() {
        this.dialogRef.close(true);
    }
}
