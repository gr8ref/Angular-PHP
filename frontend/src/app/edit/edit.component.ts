import {Component, OnInit} from '@angular/core';
import {ResolverService} from "../resolver.service";
import {ActivatedRoute} from "@angular/router";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {switchMap, first} from 'rxjs/operators';
import { Users } from '../users';
import { UpdateUser } from '../UpdateUser';

@Component({
    selector: 'app-edit',
    templateUrl: './edit.component.html',
    styleUrls: ['./edit.component.css']
})
export class EditComponent implements OnInit {
    editForm: FormGroup;
    userId: number;
    username: string;
    firstName: string;
    lastName: string;
    address: string;
    city: string;
    zip: number;
    password: string;
    user: Users[];

    constructor(private formBuilder: FormBuilder, public resolverService: ResolverService, private route: ActivatedRoute) {
      this.resolverService.isAuthenticated();
        this.userId = this.route.params['value'].id;
    }

    ngOnInit() {
      this.route.data.subscribe(data => {
      this.user = data.user;
    });
        /*this.resolverService.getUserDataById(this.userId).subscribe(item => {
            //this.username = item["username"];
            this.firstName = item["firstName"];
            this.lastName = item["lastName"];
            this.address = item["address"];
            this.city = item["city"];
            this.zip = item["zip"];
            //this.password = item["password"];
        })
        */
       this.editForm = this.formBuilder.group({
        id: [''],
        username: [''],
        firstName: ['', Validators.required],
        lastName: ['', Validators.required],
        address: ['', Validators.required],
        zip: ['', Validators.required],
        city: ['', Validators.required]
      });
      this.resolverService.getUserDataById(this.userId).pipe(first())
      .subscribe( data => {
        this.editForm.setValue(data);
        console.log(this.editForm.value) //It works
      });
    }

    saveUser() {
        this.resolverService.updateUser(this.userId, this.editForm.value).subscribe((user: UpdateUser) =>
          console.log('Done', user));
              
    }

}