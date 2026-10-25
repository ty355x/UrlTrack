import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpHandler, HttpRequest, HttpEvent, HttpResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { DialogService } from './core/dialog.service';
import { SessionService } from './platform/auth/session.service';

@Injectable()
export class AppHttpInterceptor implements HttpInterceptor {
    private isShowingError = false;

    constructor(private dialogs: DialogService, private session: SessionService) {
    }

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        const apiToken = this.session.getApiToken();

        if (!apiToken) {
            return next.handle(req);
        }

        const authReq = req.clone({
            headers: req.headers.set('Authorization', 'Bearer ' + apiToken)
        });

        return next.
            handle(authReq)
            .pipe(map(event => this.onHttpEvent(event)))
            .pipe(catchError((err: any) => this.onResponseError(err)));
    }

    private onHttpEvent(event: HttpEvent<any>) {
        if (event instanceof HttpResponse) {
            this.onResponse(event);
        }

        return event;
    }

    private onResponse(res: HttpResponse<any>) {
        const log = res?.body?._log;

        if (log) {
            console.log(log);
        }
    }

    private onResponseError(err: any) {
        if (err.status === 500) {
            this.handleApiError(err);
        } else {
            this.showErrorMessage('Error', `${err.status} - ${err.statusText}`);
        }

        /*if (err.status === 401 || err.status === 440) {
            this.handleUnauthorized(err);
        } else if (err.status === 404) {
            this.showErrorMessage(this.genericErrorMessageTitle, 'The specified resource was not found (404)');
        } else {
            this.onApiError(err);
        }*/

        return throwError(err);
    }

    private handleApiError(err: any) {
        if (err.hasOwnProperty('error') && err.error.hasOwnProperty('error_message')) {
           this.showErrorMessage('Error', err.error.error_message);
        } else {
            this.showErrorMessage('Error', 'An error occurred while processing your request');
        }

        if (err.hasOwnProperty('error') && err.error.hasOwnProperty('_error_message_debug')) {
            console.error('Error: ' + err.error._error_message_debug);
        }
    }

    private showErrorMessage(title: string, message: string) {
        if (!this.isShowingError) {
            this.isShowingError = true;
            this.dialogs.showErrorDialog(title, message).afterClosed().subscribe(() => { this.isShowingError = false });
        }
    }
}
