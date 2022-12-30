import { Component, OnInit } from '@angular/core';
import {Router} from '@angular/router';
import {User} from 'src/app/models/user';
import {UserService} from 'src/app/services/user.service';

@Component({
  selector: 'app-change-password',
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.css']
})
export class ChangePasswordComponent implements OnInit {
    
    private identity:any;
    private token:any;
    protected user:User;
    protected status:any;
    protected message:any;

    constructor(
	private _userService:UserService,
	private _router:Router
    ) { 
	this.identity = this._userService.getIdentity();
	this.user = new User(1,'','','','','','','','','');
	this.token = this._userService.gettoken();
    }

    ngOnInit(): void {
	
	this.user = this.identity;

    }
    
    onSubmit(form:any){
	this._userService.changePasswor(this.user, this.token).subscribe(
	    respose => {
		if(respose.status == 'success'){
		    sessionStorage.clear()
		    this._router.navigate(['']);
		}else{
		    this.status = respose.status;
		    this.message = respose.message;
		    this.user.equalpass = '';
		    this.user.password = '';
		    this.user.newpass = '';
		}	
	    },
	    error => {
		console.log(error);
		
	    }

	);
	
    }
}
