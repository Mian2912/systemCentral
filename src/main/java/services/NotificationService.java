/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package services;


import java.io.IOException;
import java.net.HttpURLConnection;
import java.util.ArrayList;
import java.util.List;
import models.Employee;
import models.Notification;
import org.json.simple.JSONObject;
import org.json.simple.parser.ParseException;
import helpers.Helpers;

/**
 *
 * @author migue
 */
public class NotificationService {
       
    public List<Notification> getNotifications(Employee employee){
        List<Notification> notifications = new ArrayList<>();
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/notifications");
            conn.setRequestProperty("Authorization", employee.getToken());
            
            JSONObject data = Helpers.getResponse(conn);
            List<JSONObject> notificaciones = (List<JSONObject>) data.get("notifications");
            if(notificaciones == null){
                return notifications;
            }
            for(JSONObject notificacion : notificaciones){
                Notification notification = new Notification();
                notification.setId(Integer.parseInt(notificacion.get("id").toString()));
                notification.setNotification(notificacion.get("notification").toString());
                notifications.add(notification);
            }
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return notifications;
    }
    
    public Notification deleteNotification(Notification notification, Employee employee){
        
        try{
            HttpURLConnection conn = Helpers.delete("employee/notification/"+notification.getId());
            conn.setRequestProperty("Authorization", employee.getToken());
              
        }catch(IOException ex){
            System.out.println(ex.getMessage());
        }
        return notification;
    }
    
}
