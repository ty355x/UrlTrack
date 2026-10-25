import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { BaseService } from "src/app/core/base-service";

@Injectable({
    providedIn: 'root',
})
export class UserService extends BaseService {
    constructor(private httpClient: HttpClient) {
        super();
    }

    getAll() {
        return this.httpClient.get(this.apiPath + '/admin/users');
    }

    save(user: any) {
        return this.httpClient.post(this.apiPath + '/admin/users', user);
    }

    delete(userId: number) {
        return this.httpClient.delete(this.apiPath + '/admin/users/' + userId);
    }
}
