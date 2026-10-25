import { Injectable } from "@angular/core";
import { MatDialog } from "@angular/material/dialog";
import { EditUserDialog } from "./edit-user-dialog/edit-user-dialog.component";

@Injectable({
    providedIn: 'root'
})
export class UserDialogService {
    constructor(private dialog: MatDialog) {
    }

    showEditUserDialog(user: any = null) {
        const data = { user: user };
        const dialogProperties = { data: data, width: '600px', height: '480px', disableClose: true };
        return this.dialog.open(EditUserDialog, dialogProperties);
    }
}
