import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { LoginService } from '../auth/login.service';
import { SessionService } from '../auth/session.service';

@Component({
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss'],
})
export class LoginComponent {
    credentials = { email: '', password: '' };
    error: string | null = null;
    isAttemptingLogin = false;

    constructor(private router: Router, private loginService: LoginService, private session: SessionService) {
    }

    login() {
        this.error = null;

        if (!this.validateCredentials()) {
            this.error = 'You must enter an email address and a password';
            return;
        }

        this.isAttemptingLogin = true;

        this.loginService
            .login(this.credentials.email, this.credentials.password)
            .subscribe(
                (res: any) => { this.onLoginResponse(res) },
                () => this.resetLoginForm()
            );
    }

    private validateCredentials(): boolean {
        return this.credentials.email !== '' && this.credentials.password !== '';
    }

    private onLoginResponse(res: any) {
        if (!res.success) {
            this.resetLoginForm();
            this.error = res.error;
            return;
        }

        this.session.setApiToken(res.token);
        this.session.sessionReadSuccess.subscribe(() => this.onSessionReadSuccess());
        this.session.sessionReadFail.subscribe(() => this.onSessionReadFail());
        this.session.loadSession();
    }

    private resetLoginForm() {
        this.isAttemptingLogin = false;
        this.credentials.email = '';
        this.credentials.password = '';
    }

    private onSessionReadSuccess() {
        this.router.navigate(['/app/tracked-urls']);
    }

    private onSessionReadFail() {
        this.resetLoginForm();
        this.error = 'There was an error communicating with server';
    }
}
