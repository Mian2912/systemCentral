import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/models/user';
import {UserService} from 'src/app/services/user.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css'],
  providers: [UserService]
})

export class ProfileComponent implements OnInit {
    
    protected user:User;
    protected token:string;
    protected identity:any;
    protected status:string;
    protected message:string;

    constructor(private _userService:UserService) {
	this.identity = this._userService.getIdentity();
	this.status = '';
	this.message = '';
	this.user = new User(
	    this.identity.sub,
	    this.identity.name, 
	    this.identity.lastname, 
	    this.identity.type_document,
	    this.identity.document,
	    this.identity.phone,
	    this.identity.email,
	    '',
	    '',
	    ''
	);
	this.token = this._userService.gettoken();
    }

    ngOnInit(): void {
	this.user;
    }
    
    onSubmit(form:any){
	this._userService.update(this.user, this.token).subscribe(
	    respose => {
		if(respose.status == 'success'){
		    this.status = respose.status;
		    this.message = respose.message;
		    this.identity = respose.change;
		    sessionStorage.setItem('identity', JSON.stringify(this.identity));
		}
		console.log('response:')
		console.log(respose);
		
	    },
	    error => {
		console.log('error: ');
		console.log(error);
		
	    }
	    
	)
    }
}
