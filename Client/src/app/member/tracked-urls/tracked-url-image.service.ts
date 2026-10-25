import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { BaseService } from "src/app/core/base-service";

@Injectable({
    providedIn: 'root'
})
export class TrackedUrlImageService extends BaseService {
    constructor(private httpClient: HttpClient) {
        super();
    }

    findForUrl(id: any) {
        return this.httpClient.get(this.apiPath + '/app/tracked-url-images/find-for-tracked-url/' + id);
    }

    findForTrashedUrl(id: any) {
        return this.httpClient.get(this.apiPath + '/app/tracked-url-images/find-for-trashed-tracked-url/' + id);
    }

    getImage(url: string) {
        return this.httpClient.get(url);
    }
}
