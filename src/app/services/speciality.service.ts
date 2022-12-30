import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {global} from "./global";

@Injectable()
export class SpecialityService{

    private url:string;

    constructor(private _http:HttpClient){
	this.url = global.url;
    }

    specialitys():Observable<any>{
	return this._http.get(this.url+'admin/specialitys')
    }

    speciality(idSpeciality:any):Observable<any>{
	return this._http.get(this.url+'admin/speciality/'+idSpeciality);
    }
}
