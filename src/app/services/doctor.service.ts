import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable, throttleTime} from "rxjs";
import {global} from "./global";

@Injectable()
export class DoctorService{

    private url:string;

    constructor(private _http:HttpClient){
	this.url = global.url;
    }


    getdoctor(idDoctor:number):Observable<any>{
	return this._http.get(this.url+'admin/doctor/'+idDoctor);
    }
}
