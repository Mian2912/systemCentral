/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package controllers;

import java.util.List;
import javax.swing.table.DefaultTableModel;
import models.Employee;
import models.Notification;
import services.NotificationService;

/**
 *
 * @author migue
 */
public class NotificationController {
    
    public DefaultTableModel getNotifications(Employee employee){
        
        String[] title = {"No", "id", "Notificacion"};
        String[] rows = new String[3];
        DefaultTableModel model = new DefaultTableModel(null, title);
        
        int i = 1;
        List<Notification> notifications = new NotificationService().getNotifications(employee);
        for(Notification notification : notifications){
            rows[0] = String.valueOf(i++);
            rows[1] = String.valueOf(notification.getId());
            rows[2] = notification.getNotification();
            model.addRow(rows);
        }
        
        return model;
    }
}
