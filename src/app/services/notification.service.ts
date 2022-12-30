import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {global} from "./global";


@Injectable()
export class NotificationsService{

    private url:string;
    private identity:any;
    private token:any;
    
    constructor(private _http:HttpClient){
	this.url = global.url;
    }

    getNotifications(token:string):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.get(this.url+'user/notifications', {headers:headers});
    }

    delete(id:number, token:string):Observable<any>{
	let headers = new HttpHeaders().set('Authorization', token);
	return this._http.delete(this.url+'user/notification/'+id, {headers:headers});
    }
}
