import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";

import { global } from "./global";
import { UserService } from "./user.service";
import {Family} from "../models/family";

@Injectable()
export class FamilyService{

    private url:string;

    constructor(private _http:HttpClient){
	this.url = global.url;
    }

    getFamilys(token:any):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.get(this.url+'user/familys', {headers:headers});
    }

    createFamily(family:Family, token:any):Observable<any>{
	let json = JSON.stringify(family);
	let params = "json="+json;
	let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded').set('Authorization', token);
	return this._http.post(this.url+"user/family", params, {headers:headers});
    }

    getFamily(id:number, token:any):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.get(this.url+"user/family/"+id, {headers:headers});
    }

    update(family:Family, token:any):Observable<any>{
	let json = JSON.stringify(family);
	let params = "json="+json;
	let headers = new HttpHeaders().set('Content-Type', "application/x-www-form-urlencoded").set("Authorization", token);

	return this._http.put(this.url+"user/family/"+family.id, params, {headers:headers});
    }
}
