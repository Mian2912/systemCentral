import {HttpClient, HttpHeaders, JsonpInterceptor} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {global} from "./global";
import { Cite } from "../models/cite";

@Injectable()
export class CiteService {

    private url:string;

    constructor(private _http:HttpClient){
	this.url = global.url;
    }

    cites(token:string):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.get(this.url+'user/cites', {headers:headers});
    }

    cite(token:string, idCite:number):Observable<any>{

	let headers = new HttpHeaders().set('Authorization', token);

	return this._http.get(this.url+'user/cite/'+idCite, {headers:headers});
    }

    add(cite:any, token:string):Observable<any>{

	let json = JSON.stringify(cite);
	let params = 'json='+json;
	let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded').set('Authorization', token);
	return this._http.post(this.url+'user/cite', params, {headers:headers});
    }
    
    deleteCite(id:number, token:any):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.delete(this.url+"user/cite/"+id, {headers:headers});
    }

    searchCites(params:string, token:string):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.get(this.url+'user/searchCites/'+params, {headers:headers});
    }

    updateCite(cite:Cite, token:string):Observable<any>{
	let json = JSON.stringify(cite);
	let params = "json="+json;
	let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded').set('Authorization', token);
	return this._http.put(this.url+"user/update/"+cite.id, params, {headers:headers});
    }
    
    deleteCiteMotive(cite:Cite, token:string):Observable<any>{
	let json = JSON.stringify(cite);
	let params = "json="+json;
	let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded').set('Authorization', token);

	return this._http.put(this.url+"user/citeDelete/"+cite.id, params, {headers:headers});
    }
}
