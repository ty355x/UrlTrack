import { Injectable } from '@angular/core';
import { Router, CanActivateChild, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { SessionService } from '../platform/auth/session.service';

@Injectable({ providedIn: 'root' })
export class MemberGuard implements CanActivateChild {
    constructor(private router: Router, private session: SessionService) {
    }

    canActivateChild(childRoute: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (this.session.isLoaded) {
            return this.redirectToLoginIfNotAuthenticated(childRoute);
        } else {
            return Observable.create((subscriber: any) => {
                this.session.sessionReadSuccess.subscribe(() => {
                    subscriber.next(this.redirectToLoginIfNotAuthenticated(childRoute));
                    subscriber.complete();
                });
            });
        }
    }

    private redirectToLoginIfNotAuthenticated(childRoute: ActivatedRouteSnapshot) {
        if (this.session.isAuthenticated) {
            return true;
        } else {
            this.router.navigate(['/login']);
            return false;
        }
    }
}
