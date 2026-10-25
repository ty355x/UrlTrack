import { Component, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA } from "@angular/material/dialog";
import { finalize } from "rxjs/operators";
import { BaseEditDialog } from "src/app/core/base-edit-dialog";
import { UserService } from "../user.service";

@Component({
    templateUrl: './edit-user-dialog.component.html',
})
export class EditUserDialog extends BaseEditDialog {
    user: any = {};
    isSaving = false;
    title = 'Add User';
    passwordLabel = 'Password';
    roles = ['member', 'admin'];

    constructor(@Inject(MAT_DIALOG_DATA) private data: { user: any }, private dialogRef: MatDialogRef<EditUserDialog>,
    private userService: UserService) {
        super();
        this.init(data.user);
    }

    save() {
        this.errors = [];
        this.isSaving = true;

        this.userService
            .save(this.user)
            .pipe(finalize(() => this.isSaving = false ))
            .subscribe((res: any) => this.onSaveResponse(res));
    }

    private init(user: any) {
        if (user) {
            this.title = 'Edit User';
            Object.assign(this.user, user);
            this.passwordLabel = 'Change Password';
        } else {
            this.user.role = 'member';
        }
    }

    private onSaveResponse(res: any) {
        if (!res.success) {
            this.errors = res.errors;
            return;
        }

        this.dialogRef.close(true);
    }
}
