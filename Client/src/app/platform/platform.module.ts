import { CommonModule } from "@angular/common";
import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { MatToolbarModule } from "@angular/material/toolbar";
import { RouterModule } from "@angular/router";
import { LoginComponent } from "./login/login.component";
import { PageNotFoundComponent } from "./page-not-found/page-not-found-component";
import { SiteLayoutComponent } from "./site-layout/site-layout.component";
import { MatIconModule } from '@angular/material/icon';
import { HttpClientModule } from "@angular/common/http";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        RouterModule,
        MatToolbarModule,
        MatIconModule,
    ],

    declarations: [
      LoginComponent,
      SiteLayoutComponent,
      PageNotFoundComponent,
    ],

    entryComponents: [
    ],

    exports: [
    ],
})
export class PlatformModule {
}
