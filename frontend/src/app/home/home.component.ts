import {Component, OnInit} from '@angular/core';
import {ResolverService} from '../resolver.service';
import {Users} from '../users';
import {Router} from '@angular/router';

//import {FormBuilder, FormGroup, Validators} from '@angular/forms';

@Component({
    selector: 'app-home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
    users: Users[];

    constructor(
        private router: Router,
        public resolverService: ResolverService
    ) {
        this.resolverService.isAuthenticated();
    }

    ngOnInit() {

    }
}
