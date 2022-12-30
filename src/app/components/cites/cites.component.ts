import { Component, OnInit } from '@angular/core';
import {Cite} from 'src/app/models/cite';
import {CiteService} from 'src/app/services/cite.service';
import {UserService} from 'src/app/services/user.service';

@Component({
  selector: 'app-cites',
  templateUrl: './cites.component.html',
  styleUrls: ['./cites.component.css'],
  providers: [UserService, CiteService]
})
export class CitesComponent implements OnInit {
    
    private token:string;
    protected cites:Cite[];
    protected status:string;
    protected message:string;

    constructor(
	private _userService:UserService,
	private _citeService:CiteService,
    ) {
	this.token = this._userService.gettoken();
	this.cites = [];
	this.status = '';
	this.message = '';
    }

    ngOnInit(): void {
	this.getCites();
    }
    
    
    onSubmit(form:any){
	let params = form.value.search;
	this._citeService.searchCites(params ,this.token).subscribe(
	    response => {
		if(response.status == 'success'){
		    this.status = response.status;
		    this.cites = response.cites;
		    console.log(this.cites)
		}else{
		    this.cites = [];
		    this.status = response.status;
		    this.message = response.message;
		}
	    }, error => console.log(error)
	    
	);
    }


    getCites(){
	this._citeService.cites(this.token).subscribe(
	    response => {
		if(response.status == 'success'){
		    this.cites = Object.values(response.cites);
		    
		}else{
		    this.status = response.status;
		    this.cites = [];
		    this.message = response.message;
		}
	    } 
	);
    }
    
    deleteCite(id:number){
	this._citeService.deleteCite(id, this.token).subscribe(
	    response => this.getCites(),
	    error => console.log(error)
	    
	)
	
    }
}
