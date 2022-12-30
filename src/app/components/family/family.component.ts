import { Component, OnInit } from '@angular/core';
import {FamilyService} from 'src/app/services/family.service';
import {UserService} from 'src/app/services/user.service';
import { Family } from 'src/app/models/family';
import {Cite} from 'src/app/models/cite';
import {Speciality} from 'src/app/models/speciality';
import {SpecialityService} from 'src/app/services/speciality.service';
import {CiteService} from 'src/app/services/cite.service';
@Component({
  selector: 'app-family',
  templateUrl: './family.component.html',
  styleUrls: ['./family.component.css'],
  providers:[UserService, FamilyService, SpecialityService, CiteService]
})

export class FamilyComponent implements OnInit {
    
    protected familys:Family[];
    protected specialitys:Speciality[];
    protected identity:any;
    protected token:any;
    protected status:string;
    protected statusCite:string;
    protected message:string;
    protected family:Family;
    protected familyCite:Family;
    protected cite:Cite;

    constructor(
	private _familyService:FamilyService,
	private _userService:UserService,
	private _specialityService:SpecialityService,
	private _citeService:CiteService
    ) {
	this.familys = [];
	this.specialitys = [];
	this.identity = this._userService.getIdentity();
	this.token = this._userService.gettoken();
	this.family = new Family(0,'','','','','',0,0);
	this.familyCite = new Family(0,'','','','','',0,0);
	this.cite = new Cite(0,'','','','','','',0,0,'','','','','',0,'','',0);
	this.status = '';
	this.statusCite = '';
	this.message = '';
    }

    ngOnInit(): void {
	this.getFamilys();
	this.getSpecialitys();
    }

    getFamilys(){
	this._familyService.getFamilys(this.token).subscribe(
	    response => {
		this.familys = response.family;
	    },
	    error => {
		console.log(error);
		
	    }
	);
    }

    getSpecialitys(){
	this._specialityService.specialitys().subscribe(
	    response => {
		if(response.status == "success"){
		    this.specialitys = response.specialitys;
		}
	    },
	    error => console.log(error)
	    
	    
	);
    }
    
    getFamily(id:number){
	this._familyService.getFamily(id, this.token).subscribe(
	    response => {
		if(response.status == 'success'){
		    this.familyCite = response.family;
		    this.cite.name = this.familyCite.name;
		    this.cite.lastname = this.familyCite.lastname;
		    this.cite.type_document = this.familyCite.type_document;
		    this.cite.document = this.familyCite.document;
		    this.cite.phone = this.familyCite.phone
		}
	    },error => console.log(error)
	    
	);
    }

    onSubmit(form:any){
	this._familyService.createFamily(this.family, this.token).subscribe(
	    response => {
		if(response.status == 'success'){
		    this.status = response.status;
		    this.message = response.message;
		    this.getFamilys();
		    form.reset();
		}else{
		    this.status = response.status;
		    this.message = response.message;
		}
		
	    }, error => console.log(error)
	    
	);
    }

    citeSubmit(form:any){
	this._citeService.add(this.cite, this.token).subscribe(
	    response => {
		if(response.status == "success"){
		    this.statusCite = response.status;
		    this.message = response.message;
		}else{
		    this.statusCite = response.status;
		    this.message = response.message;
		}
	    }, error => console.log(error)
	    
	);
    }

}
