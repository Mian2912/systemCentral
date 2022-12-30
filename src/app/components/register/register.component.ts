import { Component, OnInit } from '@angular/core';

import { User } from 'src/app/models/user';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [UserService]
})

export class RegisterComponent implements OnInit {
    
    protected user:User;
    protected status:string;
    protected message:string;

    constructor(
	private _userService:UserService
    ) { 
		
	this.user = new User(1, '', '', '', '', '', '', '','','');
	this.status = '';
	this.message = '';
    }

    ngOnInit(): void {
    }

    onSubmit(form:any){
	
	this._userService.register(this.user).subscribe(
	    response => {

		if(response.status == 'success'){
		    this.status = response.status;
		    this.message = response.message;
		    form.reset();
		}else{
		    this.status = response.status;
		    this.message = response.message;
		}
	    },
	    error => console.log(error)
	    
	);
    }
}
