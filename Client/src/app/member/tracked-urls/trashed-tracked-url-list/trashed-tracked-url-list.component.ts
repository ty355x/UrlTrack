import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { DialogService } from 'src/app/core/dialog.service';
import { TrackedUrlDialogService } from '../tracked-url-dialog.service';
import { TrackedUrlService } from '../tracked-url.service';

@Component({
    templateUrl: './trashed-tracked-url-list.component.html',
})
export class TrashedTrackedUrlListComponent implements OnInit {
    isLoading = false;
    trackedUrls: Array<any> | null = null;

    constructor(private router: Router, private dialogs: DialogService, private trackedUrlDialogService: TrackedUrlDialogService, private trackedUrlService: TrackedUrlService) {
    }

    ngOnInit() {
        this.refreshTrackedUrls();
    }

    back() {
        this.router.navigate(['/app/tracked-urls']);
    }

    visitUrl(url: any) {
        window.open(url.url);
    }

    viewImages(url: any) {
        this.router.navigate(['/app/tracked-url-images/' + url.id], { queryParams: { trashed: true }});
    }

    restoreTrackedUrl(url: any) {
        this.dialogs
            .showPromptDialogHtml('Confirm Restore', 'Are you sure you want to restore this tracked URL?')
            .afterClosed().subscribe((result: any) => this.onConfirmRestoreTrackedUrlDialogClosed(url.id, result));
    }

    permDeleteTrackedUrl(url: any) {
        this.dialogs
            .showPromptDialogHtml('Confirm Delete', 'Are you sure you want to permanently delete this tracked URL?<br>All images will be deleted<br>This action cannot be undone')
            .afterClosed().subscribe((result: any) => this.onConfirmPermDeleteTrackedUrlDialogClosed(url.id, result));
    }

    private refreshTrackedUrls() {
        this.isLoading = true;
        this.trackedUrlService
            .getAllTrash()
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

    private onConfirmRestoreTrackedUrlDialogClosed(trackedUrlId: number, result: any) {
        if (!result) {
            return;
        }

        this.isLoading = true;

        this.trackedUrlService
            .restore(trackedUrlId)
            .pipe(finalize(() => this.isLoading = false))
            .subscribe((res: any) => this.refreshTrackedUrls());
    }

    private onConfirmPermDeleteTrackedUrlDialogClosed(trackedUrlId: number, result: any) {
        if (!result) {
            return;
        }

        this.isLoading = true;

        this.trackedUrlService
            .permDelete(trackedUrlId)
            .pipe(finalize(() => this.isLoading = false))
            .subscribe((res: any) => this.refreshTrackedUrls());
    }
}
