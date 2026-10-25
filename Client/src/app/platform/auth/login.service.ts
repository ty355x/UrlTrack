import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { BaseService } from "src/app/core/base-service";

@Injectable({
    providedIn: 'root'
})
export class LoginService extends BaseService {
    constructor(private httpClient: HttpClient) {
        super();
    }

    login(email: string, password: string): Observable<any> {
        const postData = { email: email, password: password };
        return this.httpClient.post(this.apiPath + '/login', postData);
    }

    logout(): Observable<any> {
        return this.httpClient.post(this.apiPath + '/logout', {});
    }

    getSession(): Observable<any> {
        return this.httpClient.get(this.apiPath + '/session', {});
    }
}
