import { Component, DoCheck } from '@angular/core';
import {UserService} from './services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements DoCheck {
  
    title = 'central-frontend';
    protected identity:any;

    constructor(private _userService:UserService, private _router:Router){
	this.identity = this._userService.getIdentity();
    }

    ngDoCheck(): void {
    	this.identity = this._userService.getIdentity();
    }

    logout():void{
	sessionStorage.clear();
	this.identity = null;
	this._router.navigate([''])
    }

}
