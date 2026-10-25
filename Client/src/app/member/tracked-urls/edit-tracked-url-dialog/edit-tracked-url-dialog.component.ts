import { Component, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA } from "@angular/material/dialog";
import { finalize } from "rxjs/operators";
import { BaseEditDialog } from "src/app/core/base-edit-dialog";
import { TrackedUrlService } from "../tracked-url.service";

@Component({
    templateUrl: './edit-tracked-url-dialog.component.html',
    styleUrls: ['./edit-tracked-url-dialog.component.scss'],
})
export class EditTrackedUrlDialog extends BaseEditDialog {
    url: any = {};
    isSaving = false;
    title = 'Add Tracked URL';

    updateMethods = [
        { label: 'At a specific time every day', value: 'time' },
        { label: 'Multiple times per day at a regular interval', value: 'interval' }
    ];

    constructor(@Inject(MAT_DIALOG_DATA) private data: { url: any }, private dialogRef: MatDialogRef<EditTrackedUrlDialog>, private trackedUrlService: TrackedUrlService) {
        super();
        this.init(data.url);
    }

    save() {
        this.errors = [];
        this.isSaving = true;

        this.trackedUrlService
            .save(this.url)
            .pipe(finalize(() => this.isSaving = false ))
            .subscribe((res: any) => this.onSaveResponse(res));
    }

    onSaveResponse(res: any) {
        if (!res.success) {
            this.errors = res.errors;
            return;
        }

        this.dialogRef.close(true);
    }

    private init(url: any) {
        if (url === null) {
            this.url.update_method = 'time';
            return;
        }

        this.title = 'Edit Tracked URL';
        Object.assign(this.url, url);

        if (this.url.update_method === 'time') {
            this.parseUpdateTime();
        } else if (this.url.update_method === 'interval') {
            this.parseUpdateInterval();
        }
    }

    private parseUpdateTime() {
        this.url.update_time = this.url.update_time_formatted;
    }

    private parseUpdateInterval() {
        const interval = this.url.update_interval_mins;

        this.url.update_interval_hours = '';
        this.url.update_interval_mins = '';

        if (!interval) {
            return;
        }

        const hours = Math.trunc(interval / 60);
        const mins = (interval - hours * 60) % 60;

        if (hours !== 0) {
            this.url.update_interval_hours = hours;
        }

        if (mins !== 0) {
            this.url.update_interval_mins = mins;
        }
    }
}
