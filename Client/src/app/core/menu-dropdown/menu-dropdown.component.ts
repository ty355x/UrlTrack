import { Component, ElementRef, HostListener, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'app-menu-dropdown',
    templateUrl: './menu-dropdown.component.html',
    styleUrls: ['./menu-dropdown.component.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class MenuDropDownComponent implements OnInit {
    showMenu = false;
    showMobile = false;

    constructor(private element: ElementRef) {
    }

    ngOnInit() {
        // this.checkShowMobile();
    }

    menuButtonClick() {
        this.showMenu = !this.showMenu;
    }

    @HostListener('document:mousedown', ['$event'])
    onGlobalClick(event: any): void {
        if (!this.element.nativeElement.contains(event.target)) {
            this.showMenu = false;
        }
    }

    @HostListener('window:resize', ['$event'])
    onResize(event: any) {
        // this.checkShowMobile();
    }

    checkShowMobile() {
        const mobileWidth = 800;
        this.showMobile = window.innerWidth <= mobileWidth;
        this.showMenu = false;
    }
}
