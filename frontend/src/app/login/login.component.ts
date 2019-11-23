import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';

//import { HttpErrorResponse } from '@angular/common/http';  

import {ResolverService} from '../resolver.service'
import {first} from 'rxjs/operators';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

    loginForm: FormGroup;
    loading = false;
    submitted = false;
    errorMessage: string;

    constructor(private formBuilder: FormBuilder, private router: Router, private resolverService: ResolverService) {
        this.resolverService.isAuthenticated()
    }

    ngOnInit() {
        this.loginForm = this.formBuilder.group({
            username: ['', Validators.required],
            password: ['', Validators.required]
        });

    }

    get f() {
        return this.loginForm.controls;
    }

    onSubmit() {
        this.submitted = true;

        if (this.loginForm.invalid) {
            return;
        }

        this.loading = true;
        this.resolverService.Login(this.f.username.value, this.f.password.value)
            .pipe(first())
            .subscribe(
                data => {
                    //console.log(data);
                    if (data.message == "Success") {

                        const token = data
                        window.localStorage.setItem("token", JSON.stringify(token));

                        this.router.navigate(['/home']);
                        //debugger;
                        //console.log(data);
                    } else {
                        this.errorMessage = data.message;

                        //console.log(data);
                        this.router.navigate(['/login']);
                    }
                },
                error => {
                    this.errorMessage = error.message;
                    this.router.navigate(['/login']);

                });

    }
}
