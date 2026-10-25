import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { BaseService } from "src/app/core/base-service";

@Injectable({
    providedIn: 'root',
})
export class LogService extends BaseService {
    constructor(private httpClient: HttpClient) {
        super();
    }

    getIndex() {
        return this.httpClient.get(this.apiPath + '/admin/log');
    }

    test() {
        return this.httpClient.post(this.apiPath + '/admin/test', {});
    }
}
