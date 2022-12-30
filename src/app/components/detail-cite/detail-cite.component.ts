import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {Cite} from 'src/app/models/cite';
import {CiteService} from 'src/app/services/cite.service';
import {DoctorService} from 'src/app/services/doctor.service';
import {SpecialityService} from 'src/app/services/speciality.service';
import {UserService} from 'src/app/services/user.service';
import { global } from 'src/app/services/global';

@Component({
  selector: 'app-detail-cite',
  templateUrl: './detail-cite.component.html',
  styleUrls: ['./detail-cite.component.css'],
  providers: [UserService, CiteService, SpecialityService, DoctorService]
})
export class DetailCiteComponent implements OnInit {
    
    protected cite:Cite;
    private token:any;
    protected messages:any;
    protected status:any;
    
    protected afuConfig = {
	multiple: false,
	formatsAllowed: ".pdf",
	maxSize: "20",
	uploadAPI:  {
	    url: global.url+"user/cite/upload",
	    method:"POST",
	    headers: {
		"Authorization" : this._userService.gettoken()
	    }
	},
	theme: "attachPin",
	hideProgressBar: false,
	hideResetBtn: true,
	hideSelectBtn: false,
	attachPinText:'Subir archivo solicitado'
    };


    constructor( 
	private _route: ActivatedRoute,
	private _router: Router,
	private _userService:UserService,
	private _citeService:CiteService,
	private _specialityService:SpecialityService,
	private _doctorService:DoctorService
    ) {
	this.cite = new Cite(1,'','','','','','',1,1,'','','','','',1,'','', 1);
	this.token = this._userService.gettoken();
    }

    ngOnInit(): void {	
	this.getCite();
	this.getSpeciality();	
    }

    onSubmit(form:any){
	this._citeService.updateCite(this.cite, this.token).subscribe(
	    response => {
		if(response.status == "success"){
		    this.status = response.status;
		    this.messages = response.messages;
		    this._router.navigate(['/cites']);
		}else{
		    this.status = response.status;
		    this.messages = response.messages;
		}
	    }
	);
		
    }

    getCite(){
	this._route.params.subscribe( params => {
	    
	    let citeId = params['id'];
	    this._citeService.cite(this.token, citeId).subscribe(
		response => {
		    this.cite = response.cite;
		    
		    if(this.cite.id_status != 5 && this.cite.id_status != 1){
			this.getSpeciality();
			this.getdoctor();
		    }else{
			this._router.navigate(['\cites']);
		    }
		},
		error => console.log(error)
	    ); 
	});

    }


    getSpeciality(){
	this._specialityService.speciality(this.cite.id_speciality).subscribe(
	    response => this.cite.id_speciality = response.speciality.specialitys
	);
    }

    getdoctor(){
	this._doctorService.getdoctor(this.cite.id_doctors).subscribe(
	    response => this.cite.id_doctors = response.doctor.name
	)
    }

    deleteCiteMotive(form:any){
	this._citeService.deleteCiteMotive(this.cite, this.token).subscribe(
	    response => {
		if(response.status == 'success'){
		    this._router.navigate(['/cites']);
		}else{
		    this.status = response.status;
		    this.messages = response.message;
		}
	    }, error => console.log(error)
	    
	);
    }

    authorizationFile(datos:any){
	let response = JSON.parse(datos.response);
	this.cite.authorization = response.file;
    }

    ordenFile(datos:any){
	let response = JSON.parse(datos.response);
	this.cite.orden = response.file;
    }
}
