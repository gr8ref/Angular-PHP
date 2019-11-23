import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {HttpHeaders} from '@angular/common/http';
import {Users} from './users';
import {Observable} from "rxjs";
import config from '../config/config';
import {appRoutes} from '../constants/constants';
import {environment} from '../environments/environment';
import {Router, ActivatedRoute} from '@angular/router';
import { first } from 'rxjs/operators';
import { UpdateUser } from './UpdateUser';

@Injectable({
    providedIn: 'root'
})
export class ResolverService {
    url: string;
    header: any;
    public userData: any = [];
    //data = false;
    users = [];


    constructor(private http: HttpClient, private router: Router, private route: ActivatedRoute) {
    }

    Login(username: string, password: string) {

        const url = environment.apiEndPoint + 'Login';
        const body = JSON.stringify({user: username, pass: password});
        const headerSettings: { [name: string]: string | string[]; } = {};
        this.header = new HttpHeaders(headerSettings);
        return this.http.post<any>(url, body, {headers: headerSettings})
    }

    isAuthenticated() {
        if (this.tokenData()) {
            this.checkAccessToken(this.tokenData().user, this.tokenData().token).subscribe((items: any) => {
                if (Object.entries(items).length === 0) {
                    this.logout();
                } else {
                    if (items === []) {
                        this.logout();
                    }
                    this.userData = [items]
                    if (window.location.pathname === '/login') {
                        this.router.navigate(['/home']);
                    }
                }
            });
        } else {
            this.logout();
        }
    }

    getRouteData() {
        return this.route.data['value'].showHeader;
    }

    getLoggedInUserData() {

        return this.checkAccessToken(this.tokenData().user, this.tokenData().token)
    }

    /*
        getUserData(): Observable<Users[]>{
           return this.checkAccessToken(this.tokenData().user, this.tokenData().token);
        }
    */
    showHeader() {
        switch (window.location.pathname) {
            case appRoutes.login:
                return false;
            case appRoutes.home:
                return true;
            case appRoutes.users:
                return true;
            case appRoutes.edit:
                return true;
            default:
                return false
        }
    }

    tokenData() {
        return JSON.parse(window.localStorage.getItem(config.TOKEN));
    }

    logout() {
        window.localStorage.removeItem(config.TOKEN);
        this.router.navigate(['/login']);
    }

    listUsers(): Observable<Users[]> {
        return this.http.get<Users[]>(environment.apiEndPoint + 'Show');
    }

    getUserDataById(id: number) {
        return this.http.get(`${environment.apiEndPoint}ShowID&id=${id}`);
    }

    checkAccessToken(user: any, accessToken: any) {
        return this.http.get(`${environment.apiEndPoint}AccessToken&user=${user}&accesstoken=${accessToken}`);
    }


    addUsers(user: Users) {
        return this.http.post(environment.apiEndPoint + 'Add', user);
    }
    
    /*updateUser(id, user: Users){
        this.http.post(environment.apiEndPoint + 'Edit?id=' + id, user);
    }*/

    updateUser(id: number, user: UpdateUser) {
        return  this.http.put(environment.apiEndPoint + 'Edit?id' + id, user);
       }
       
    /* deleteUsers(id: number){
      return this.http.delete<Users>(environment.apiEndPoint+'Delete&id=${id}');
    }
    */
    delete(id: number) {
        return this.http.delete(`${environment.apiEndPoint}Delete&id=${id}`);
    }

    openPage(page: string) {
        this.router.navigate([page]);
    }
}
