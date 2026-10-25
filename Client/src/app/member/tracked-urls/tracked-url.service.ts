import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { BaseService } from "src/app/core/base-service";

@Injectable({
    providedIn: 'root'
})
export class TrackedUrlService extends BaseService {
    constructor(private httpClient: HttpClient) {
        super();
    }

    getAll() {
        return this.httpClient.get(this.apiPath + '/app/tracked-urls');
    }
    getAllTrash() {
        return this.httpClient.get(this.apiPath + '/app/trashed-tracked-urls');
    }

    save(url: any) {
        return this.httpClient.post(this.apiPath + '/app/tracked-urls', url);
    }

    delete(trackedUrlId: number) {
        return this.httpClient.delete(this.apiPath + '/app/tracked-urls/' + trackedUrlId);
    }

    restore(trackedUrlId: number) {
        return this.httpClient.post(this.apiPath + '/app/trashed-tracked-urls/restore', { id: trackedUrlId });
    }

    permDelete(trackedUrlId: number) {
        return this.httpClient.delete(this.apiPath + '/app/trashed-tracked-urls/' + trackedUrlId);
    }
}
