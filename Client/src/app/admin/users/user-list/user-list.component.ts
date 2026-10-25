import { Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { DialogService } from 'src/app/core/dialog.service';
import { UserDialogService } from '../user-dialog.service';
import { UserService } from '../user.service';

@Component({
    templateUrl: './user-list.component.html',
})
export class UserListComponent implements OnInit {
    isLoading = false;
    users: any = null;

    constructor(private dialogs: DialogService, private userDialogService: UserDialogService, private userService: UserService) {
    }

    ngOnInit() {
        this.isLoading = true;
        this.refreshUsers();
    }

    addUser() {
        this.userDialogService
            .showEditUserDialog()
            .afterClosed().subscribe((result: any) => this.onEditUserDialogClosed(result));
    }

    editUser(user: any) {
        this.userDialogService
            .showEditUserDialog(user)
            .afterClosed().subscribe((result: any) => this.onEditUserDialogClosed(result));
    }

    deleteUser(user: any) {
        this.dialogs
            .showPromptDialog('Confirm Delete', 'Are you sure you want to delete this user?')
            .afterClosed().subscribe((result: any) => this.onConfirmDeleteUserDialogClosed(user.id, result));
    }

    private refreshUsers() {
        this.userService
            .getAll()
            .pipe(finalize(() => this.isLoading = false))
            .subscribe((res: any) => this.users = res.users);
    }

    private onEditUserDialogClosed(result: any) {
        if (result) {
            this.refreshUsers();
        }
    }

    private onConfirmDeleteUserDialogClosed(trackedUrlId: number, result: any) {
        if (!result) {
            return;
        }

        this.userService
            .delete(trackedUrlId)
            .pipe(finalize(() => this.isLoading = false))
            .subscribe((res: any) => this.refreshUsers());
    }
}
