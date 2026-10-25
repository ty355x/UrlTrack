import { Component, OnInit } from '@angular/core';
import { SessionService } from './platform/auth/session.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
    constructor(private session: SessionService ) {
    }

    ngOnInit() {
        this.session.loadSession();
    }
}
