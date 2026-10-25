import { DatePipe } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { TrackedUrlImageService } from './../tracked-url-image.service';
import { environment } from 'src/environments/environment';
import { SessionService } from 'src/app/platform/auth/session.service';
import { DateTimePipe } from 'src/app/core/pipes';

@Component({
    templateUrl: './image-list.component.html',
    styleUrls: ['./image-list.component.scss'],
})
export class ImageListComponent implements OnInit {
    isLoading = false;
    url: string | null = null;
    images: Array<any> | null = null;
    imageGroups: Array<any> | null = null;
    isUrlTrashed = false;

    constructor(private route: ActivatedRoute, private router: Router, private session: SessionService, private imageService: TrackedUrlImageService) {
        this.route.queryParamMap.subscribe((params: ParamMap) => this.onQueryParamsChanged(params));
        this.route.paramMap.subscribe((params: ParamMap) => this.onParamsChanged(params));
    }

    ngOnInit() {
    }

    back() {
        if (!this.isUrlTrashed) {
            this.router.navigate(['/app/tracked-urls']);
        } else {
            this.router.navigate(['/app/trashed-tracked-urls']);
        }
    }

    openUrl(url: string) {
        window.open(url);
    }

    viewImage(image: any) {
        window.open(image.image_url);
    }

    private onParamsChanged(params: any) {
        const id = params?.params?.id;

        if (id) {
            this.refreshImageList(id);
        }
    }

    private onQueryParamsChanged(params: any) {
        const trashed = params?.params?.trashed;
        this.isUrlTrashed = trashed ? true : false;
    }

    private refreshImageList(id: any) {
        this.isLoading = true;

        if (!this.isUrlTrashed) {
            this.findImages(id);
        } else {
            this.findImagesForTrashedUrl(id);
        }
    }

    private findImages(id: any) {
        this.imageService
        .findForUrl(id)
        .pipe(finalize(() => this.isLoading = false))
        .subscribe((res: any) => this.onFindImagesResponse(res));
    }

    private findImagesForTrashedUrl(id: any) {
        this.imageService
        .findForTrashedUrl(id)
        .pipe(finalize(() => this.isLoading = false))
        .subscribe((res: any) => this.onFindImagesResponse(res));
    }

    private onFindImagesResponse(res: any) {
        this.url = res.url;
        this.images = res.images;
        this.makeImageGroups();
    }

    private makeImageGroups() {
        this.imageGroups = [];
        let lastDate: string | null = null;

        this.images?.forEach(image => {
            const fullDate = new DateTimePipe().transform(image.created_at);
            const date = new DatePipe('en_US').transform(fullDate.substring(0, 10), 'fullDate');
            const time = fullDate.substring(11, fullDate.length);

            if (date !== lastDate) {
                this.imageGroups?.push({ date: date, images: [] });
                lastDate = date;
            }

            if (this.imageGroups !== null) {
                const index = this.imageGroups.length - 1;
                this.imageGroups[index]?.images.push({ time: time, image: image });
            }
        });
    }
}
