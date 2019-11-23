import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {ResolverService} from '../resolver.service';
import {FormBuilder} from '@angular/forms';
import {Users} from '../users';

@Component({
    selector: 'app-users',
    templateUrl: './users.component.html',
    styleUrls: ['./users.component.css']
})
export class UsersComponent implements OnInit {
    users: Users[];
    userName: string;


    constructor(
        private router: Router,
        private formBuilder: FormBuilder,
        private resolverService: ResolverService
    ) {
        this.resolverService.isAuthenticated()
    }

    ngOnInit() {
        this.listUsers();
    }

    deleteUsers(id) {
        this.resolverService.delete(id).subscribe((user: Users) => {
            //console.log("User deleted, ", user);
            this.listUsers();
        });
    }

    listUsers() {
        this.resolverService.listUsers().subscribe((user: Users[]) => {
            this.users = user;
            //console.log(this.users);
        })
    }
}
