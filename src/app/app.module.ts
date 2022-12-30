import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';


import { routing, appRoutingProviders } from './app.routing';
import { AppComponent } from './app.component';
import { UserService } from './services/user.service';
import { IdentityGuard } from './services/identity.guard';
import { RegisterComponent } from './components/register/register.component';
import { LoginComponent } from './components/login/login.component';
import { SystemComponent } from './components/system/system.component';
import {NoIdentityGuard} from './services/no.identity.guard';
import { ProfileComponent } from './components/profile/profile.component';
import { NotificationsComponent } from './components/notifications/notifications.component';
import { CitesComponent } from './components/cites/cites.component';
import { DetailCiteComponent } from './components/detail-cite/detail-cite.component';
import { AddCiteComponent } from './components/add-cite/add-cite.component';
import { ChangePasswordComponent } from './components/change-password/change-password.component';
import { FamilyComponent } from './components/family/family.component';
import { GetFamilyComponent } from './components/get-family/get-family.component';
import { AngularFileUploaderModule } from 'angular-file-uploader';

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent,
    LoginComponent,
    SystemComponent,
    ProfileComponent,
    NotificationsComponent,
    CitesComponent,
    DetailCiteComponent,
    AddCiteComponent,
    ChangePasswordComponent,
    FamilyComponent,
    GetFamilyComponent,
  ],
  imports: [
    BrowserModule,
    routing,
    HttpClientModule,
    FormsModule,
    AngularFileUploaderModule
  ],
  providers: [
      appRoutingProviders,
      UserService,
      IdentityGuard,
      NoIdentityGuard
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
