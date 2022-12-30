import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';

import { User } from 'src/app/models/user';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {

    protected user:User;
    protected status:string;
    protected message:string;

    constructor(private _userService:UserService, private _router:Router) {
	this.status = '';
	this.message = '';
	this.user = new User(1,'','','','','','','','','');
    }

    ngOnInit(): void {	
    }

    onSubmit(form:any){
	this._userService.login(this.user).subscribe(
	    response => {
		if(response.original && response.original.status == 'error'){
		    this.status = response.original.status;
		    this.message = response.original.message;
		}else{

		    sessionStorage.setItem('token', response);		    
		    this._userService.login(this.user, true).subscribe(
			response =>{
			    sessionStorage.setItem('identity', JSON.stringify(response)); 
			    this._router.navigate(['system']);
			}	
		    );
		}
	    }   
	);
    }

}
