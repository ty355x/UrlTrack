import { Input, Component, OnInit } from '@angular/core';

@Component({
    selector: 'app-loading',
    templateUrl: './loading.component.html',
    styleUrls: ['./loading.component.scss']
})
export class LoadingComponent implements OnInit {
    @Input() show = false;
    @Input() center = false;
    @Input() overlay = true;

    loaderClass = 'loading-pane';

    constructor() {
    }

    ngOnInit() {
        if (this.center) {
            this.loaderClass = 'loading-pane loading-pane-center';
        }
    }
}
