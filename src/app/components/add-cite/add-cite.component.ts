import { Component, OnInit } from '@angular/core';
import {Cite} from 'src/app/models/cite';
import {Speciality} from 'src/app/models/speciality';
import {CiteService} from 'src/app/services/cite.service';
import {SpecialityService} from 'src/app/services/speciality.service';
import {UserService} from 'src/app/services/user.service';

@Component({
  selector: 'app-add-cite',
  templateUrl: './add-cite.component.html',
  styleUrls: ['./add-cite.component.css'],
  providers: [UserService, CiteService, SpecialityService]
})
export class AddCiteComponent implements OnInit {

    private token:string;
    protected identity:any;
    protected cite: Cite;
    protected specialitys:Speciality[];
    protected status:string;
    protected message:string;

    constructor(
	private _userServcie:UserService,
	private _citeService:CiteService,
	private _specialityService:SpecialityService
    ) {
	this.token = this._userServcie.gettoken();	
	this.identity = this._userServcie.getIdentity();
	this.cite = new Cite(
	    1, 
	    this.identity.name,
	    this.identity.lastname, 
	    this.identity.type_document,
	    this.identity.document,
	    this.identity.phone,
	    '',
	    0,
	    1,
	    '',
	    '',
	    '',
	    '',
	    '',
	    1,
	    '',
	    '',
	    this.identity.sub
	);
	this.specialitys = [];
	this.status = '';
	this.message = '';
    }

    ngOnInit(): void {
	this.cite
	this.getSpecialitys();
    }

    onSubmit(form:any){
	this._citeService.add(this.cite, this.token).subscribe(
	    response =>{
		if(response.status == 'success'){
		    this.status = response.status;
		    this.message = response.message;
		}else{
		    this.status = response.status;
		    this.message = response.message;
		}
		
	    },
	    error => console.log(error)
	);
    }

    getSpecialitys(){
	this._specialityService.specialitys().subscribe(
	    response =>{
		if(response.status == 'success'){
		    this.specialitys = response.specialitys; 
		}			
	    } 
	);
    }

}
