import { Injectable, EventEmitter  } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BaseService } from '../../core/base-service';

@Injectable({
    providedIn: 'root'
})
export class SessionService extends BaseService {
    sessionReadSuccess = new EventEmitter<any>();
    sessionReadFail = new EventEmitter<any>();

    isLoaded = false;
    showUI = false;

    isAuthenticated = false;
    user = { email: null, role: null };

    constructor(private httpClient: HttpClient) {
        super();
    }

    loadSession() {
        return this.httpClient
            .get(this.apiPath + '/session')
            .subscribe(
                res => this.onLoadSessionSuccess(res),
                err => this.onLoadSessionFail(err),
            );
    }

    getApiToken(): string | null {
        return localStorage.getItem('api_token');
    }

    setApiToken(token: string): void {
        localStorage.setItem('api_token', token);
    }

    clearApiToken(): void {
        localStorage.removeItem('api_token');
    }

    private onLoadSessionSuccess(res: any): void {
        this.isAuthenticated = res.is_authenticated;
        this.user = res.user;
        this.isLoaded = true;
        this.showUI = true;
        this.sessionReadSuccess.emit();
    }

    private onLoadSessionFail(err: any): void {
        this.sessionReadFail.emit(err);
    }
}
