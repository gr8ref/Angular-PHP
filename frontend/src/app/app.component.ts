import {Component} from '@angular/core';
import {ResolverService} from '../app/resolver.service';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})

export class AppComponent {
    title = 'firstApp';

    constructor(
        private router: Router,
        private resolverService: ResolverService,
        public route: ActivatedRoute
    ) {
        this.resolverService.isAuthenticated();
    }


}
