import { Component, OnInit } from '@angular/core';
import { environment } from 'src/environments/environment';
import { LogService } from './log.service';


@Component({
    templateUrl: './logs.component.html',
})
export class LogsComponent implements OnInit {
    showTestButton = false;

    constructor(private logService: LogService) {
        this.showTestButton = !environment.production;
    }

    ngOnInit() {
        this.logService
            .getIndex()
            .subscribe((res: any) => console.log(res));
    }

    test() {
        this.logService
            .test()
            .subscribe((res: any) => {});
    }
}
