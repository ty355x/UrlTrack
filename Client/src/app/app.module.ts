import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { PlatformModule } from './platform/platform.module';
import { MemberModule } from './member/member.module';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AppHttpInterceptor } from './app-http-intercepctor';
import { CoreModule } from './core/core.module';
import { AngularMaterialModule } from './angular-material.module';
import { AdminModule } from './admin/admin.module';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    BrowserAnimationsModule,
    AngularMaterialModule,
    AppRoutingModule,
    AdminModule,
    CoreModule,
    PlatformModule,
    MemberModule,
  ],
  providers: [
    {
        provide: HTTP_INTERCEPTORS,
        useClass: AppHttpInterceptor,
        multi: true
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
