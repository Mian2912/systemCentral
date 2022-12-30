import { Component, OnInit } from '@angular/core';
import {Notificaciones} from 'src/app/models/notifications';
import {NotificationsService} from 'src/app/services/notification.service';
import {UserService} from 'src/app/services/user.service';

@Component({
  selector: 'app-notifications',
  templateUrl: './notifications.component.html',
  styleUrls: ['./notifications.component.css'],
  providers: [UserService, NotificationsService]
})
export class NotificationsComponent implements OnInit {
    
    protected notifications:Notificaciones[];
    protected token:any;
    protected status:string;
    protected message:string;

    constructor(private _userService:UserService, private _notificationService:NotificationsService) {
	this.notifications = [];
	this.token = this._userService.gettoken();
	this.status = "";
	this.message = "";
    }

    ngOnInit(): void {
	this.getNotifications();
    }

    getNotifications(){
	this._notificationService.getNotifications(this.token).subscribe(
	    response =>{
		if(response.status != 'not found'){
		    this.notifications = response.notifications;
		}else{
		    this.status = response.status;
		    this.message = response.message;
			this.notifications = [];
		}	
	    }
	)
    }

    delete(id:number){
	this._notificationService.delete(id, this.token).subscribe(
	    response =>{
		this.getNotifications();	
	    },
	    error => console.log(error)
	    
	);	
    }

}
