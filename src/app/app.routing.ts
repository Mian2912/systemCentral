// importar modulos de router
import { ModuleWithProviders } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";


// importando componentes
import { IdentityGuard } from "./services/identity.guard";
import {LoginComponent} from "./components/login/login.component";
import {RegisterComponent} from "./components/register/register.component";
import {SystemComponent} from "./components/system/system.component";
import {NoIdentityGuard} from "./services/no.identity.guard";
import {ProfileComponent} from "./components/profile/profile.component";
import {NotificationsComponent} from "./components/notifications/notifications.component";
import {CitesComponent} from "./components/cites/cites.component";
import {DetailCiteComponent} from "./components/detail-cite/detail-cite.component";
import {AddCiteComponent} from "./components/add-cite/add-cite.component";
import {ChangePasswordComponent} from "./components/change-password/change-password.component";
import {FamilyComponent} from "./components/family/family.component";
import {GetFamilyComponent} from "./components/get-family/get-family.component";


// array de rutas
const appRoutes:Routes = [
    {path: '', canActivate: [NoIdentityGuard], component: RegisterComponent},
    {path:'registro', canActivate: [NoIdentityGuard], component: RegisterComponent},
    {path: 'login',  canActivate: [NoIdentityGuard], component: LoginComponent,},
    {path: 'system', canActivate: [IdentityGuard], component: SystemComponent},
    {path: 'profile', canActivate: [IdentityGuard], component: ProfileComponent},
    {path: 'notifications', canActivate: [IdentityGuard], component:NotificationsComponent},
    {path: 'cites', canActivate: [IdentityGuard], component:CitesComponent},
    {path: 'familys', canActivate: [IdentityGuard], component: FamilyComponent},
    {path: 'cite-detail/:id', canActivate:[IdentityGuard], component:DetailCiteComponent},
    {path: 'cite', canActivate: [IdentityGuard], component:AddCiteComponent},
    {path: 'change', canActivate: [IdentityGuard], component: ChangePasswordComponent},
    {path: 'family-detail/:id', canActivate: [IdentityGuard], component:GetFamilyComponent},
];

export const appRoutingProviders:any[] = [];
export const routing:ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);
