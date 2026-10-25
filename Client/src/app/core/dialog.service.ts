import { Injectable } from "@angular/core";
import { MatDialog } from '@angular/material/dialog';
import { ErrorDialogComponent } from "./error-dialog/error-dialog.component";
import { PromptDialogComponent } from "./prompt-dialog/prompt-dialog.component";

@Injectable({
    providedIn: 'root'
})
export class DialogService {
    constructor(private dialog: MatDialog) {

    }

    showErrorDialog(title: string, message: string) {
        const data = { title: title, message: message };
        const dialogProperties = { data: data, width: '600px', height: '200px', disableClose: true };
        return this.dialog.open(ErrorDialogComponent, dialogProperties);
    }

    showPromptDialog(title: string, message: string) {
        return this.openPromptDialog(title, message, false);
    }

    showPromptDialogHtml(title: string, message: string) {
        return this.openPromptDialog(title, message, true);
    }

    private openPromptDialog(title: string, message: string, isMessageHtml: boolean) {
        const data = { title: title, message: message, isMessageHtml: isMessageHtml };
        const dialogProperties = { data: data, width: '600px', height: '240px', disableClose: true };
        return this.dialog.open(PromptDialogComponent, dialogProperties);
    }
}
