import { Component } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';
import { SessionService } from '../auth/session.service';

@Component({
    templateUrl: './site-layout.component.html',
    styleUrls: ['./site-layout.component.scss']
})
export class SiteLayoutComponent {
    user: any = null;
    isAdmin = false;
    activeTab?: string = 'app-tracked-urls';

    constructor(private route: ActivatedRoute, private router: Router, private session: SessionService) {
        this.user = session.user;
        this.isAdmin = this.user?.role === 'admin';
        this.router.events.subscribe(event => this.onRouterEvent(event));
    }

    logout() {
        this.session.clearApiToken();
        this.router.navigate(['/login']);
    }

    private onRouterEvent(event: any) {
        if (event instanceof NavigationEnd) {
            this.activeTab = this.route.snapshot.firstChild?.data?.tabName;
        }
    }
}
