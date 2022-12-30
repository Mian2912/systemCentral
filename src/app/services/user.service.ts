import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";

import { global } from "./global";
import { User } from "../models/user";

@Injectable()
export class UserService{
    
    private url:string;
    private identity:any;
    private token:any;

    constructor(private _htpp:HttpClient){
	this.url = global.url;
    }

    register(user:User):Observable<any>{
	let json = JSON.stringify(user);
	let params = 'json='+json;
	let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
	
	return this._htpp.post(this.url+'register', params, {headers: headers});
    }

    login(user:any, token:any=null):Observable<any>{
	if(token != null){
	    user.gettoken = 'true'
	}

	let json = JSON.stringify(user);
	let params = 'json='+json;
	let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
	return this._htpp.post(this.url+'login', params, {headers:headers})
    }

    getIdentity(){
	let identity = sessionStorage.getItem('identity');
	this.identity = (identity && identity != 'undefined') ? JSON.parse(identity) : null;
	return this.identity;
    }

    gettoken(){
	let token = sessionStorage.getItem('token');
	this.token = (token && token != 'undefined') ? token : null;
	return this.token;
    }

    update(user:User, token:any):Observable<any>{

	let json = JSON.stringify(user);
	let params = 'json='+json;
	let headers = new HttpHeaders().set('Content-type', 'application/x-www-form-urlencoded').set('Authorization', token);
	return this._htpp.put(this.url+'user/update', params, {headers:headers}); 
    }
    
    changePasswor(user:User, token:any):Observable<any>{
	let json = JSON.stringify(user);
	let params = 'json='+json;

	let headers = new HttpHeaders().set('Content-type', 'application/x-www-form-urlencoded').set('Authorization', token);
	return this._htpp.put(this.url+'user/change', params, {headers:headers});
    }
}
