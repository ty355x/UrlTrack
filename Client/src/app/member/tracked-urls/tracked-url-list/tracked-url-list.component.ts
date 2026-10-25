import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { DialogService } from 'src/app/core/dialog.service';
import { TrackedUrlDialogService } from '../tracked-url-dialog.service';
import { TrackedUrlService } from '../tracked-url.service';


@Component({
    templateUrl: './tracked-url-list.component.html',
})
export class TrackedUrlListComponent implements OnInit {
    isLoading = false;
    trackedUrls: Array<any> | null = null;

    constructor(private router: Router, private dialogs: DialogService, private trackedUrlDialogService: TrackedUrlDialogService, private trackedUrlService: TrackedUrlService) {
    }

    ngOnInit() {
        this.refreshTrackedUrls();
    }

    addTrackedUrl() {
        this.trackedUrlDialogService
            .showEditTrackedUrlDialog()
            .afterClosed().subscribe((result: any) => this.onEditTrackedUrlDialogClosed(result));
    }

    editTrackedUrl(url: any) {
        this.trackedUrlDialogService
            .showEditTrackedUrlDialog(url)
            .afterClosed().subscribe((result: any) => this.onEditTrackedUrlDialogClosed(result));
    }

    trashTrackedUrl(url: any) {
        this.dialogs
            .showPromptDialog('Confirm Trash', 'Are you sure you want to send this tracked URL to the trash?')
            .afterClosed().subscribe((result: any) => this.onConfirmDeleteTrackedUrlDialogClosed(url.id, result));
    }

    visitUrl(url: any) {
        window.open(url.url);
    }

    viewImages(url: any) {
        this.router.navigate(['/app/tracked-url-images/' + url.id]);
    }

    viewTrash() {
        this.router.navigate(['/app/trashed-tracked-urls']);
    }

    private refreshTrackedUrls() {
        this.isLoading = true;
        this.trackedUrlService
            .getAll()
            .pipe(finalize(() => this.isLoading = false))
            .subscribe((res: any) => this.onGetTrackedUrlsResponse(res));
    }

    private onGetTrackedUrlsResponse(res: any) {
        this.trackedUrls = res.tracked_urls

        if (res.tracked_urls.length > 0) {
            let alt = false;
            res.tracked_urls[0].alt = alt;

            for (let i = 1; i < res.tracked_urls.length; i++) {
                const prev = res.tracked_urls[i - 1];
                const current = res.tracked_urls[i];

                if (current.domain !== prev.domain) {
                    alt = !alt;
                }

                current.alt = alt;
            }
        }
    }

    private onEditTrackedUrlDialogClosed(result: any) {
        if (result) {
            this.refreshTrackedUrls();
        }
    }

    private onConfirmDeleteTrackedUrlDialogClosed(trackedUrlId: number, result: any) {
        if (!result) {
            return;
        }

        this.isLoading = true;

        this.trackedUrlService
            .delete(trackedUrlId)
            .pipe(finalize(() => this.isLoading = false))
            .subscribe((res: any) => this.refreshTrackedUrls());
    }
}
