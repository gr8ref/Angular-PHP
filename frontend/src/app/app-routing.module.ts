import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { HomeComponent } from './home/home.component';
import { UsersComponent } from './users/users.component';
import { EditComponent } from './edit/edit.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent, data: { title: 'Login Page', showHeader: false } },
  { path: 'home', component: HomeComponent, data: { title: 'Home', showHeader: true} },
  { path: 'users', component: UsersComponent, data: { title: 'Users', showHeader: true}},
  { path: 'edit/:id', component: EditComponent, data: { title: 'Edit user', showHeader: true}},

  { path: '*', redirectTo: '/login', pathMatch: 'full'}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
