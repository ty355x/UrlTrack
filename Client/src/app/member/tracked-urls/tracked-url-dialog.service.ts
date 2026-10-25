import { Injectable } from "@angular/core";
import { MatDialog } from "@angular/material/dialog";
import { EditTrackedUrlDialog } from "./edit-tracked-url-dialog/edit-tracked-url-dialog.component";

@Injectable({
    providedIn: 'root'
})
export class TrackedUrlDialogService {
    constructor(private dialog: MatDialog) {
    }

    showEditTrackedUrlDialog(url: any = null) {
        const data = { url: url };
        const dialogProperties = { data: data, width: '600px', height: '500px', disableClose: true };
        return this.dialog.open(EditTrackedUrlDialog, dialogProperties);
    }
}
