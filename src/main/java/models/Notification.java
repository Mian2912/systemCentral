/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package models;

/**
 *
 * @author migue
 */
public class Notification {
    
    private int id;
    private int id_user;
    private String notification;
    private String destination;

    public Notification() {
    }

    public Notification(int id) {
        this.id = id;
    }

    public Notification(int id_user, String notification, String destination) {
        this.id_user = id_user;
        this.notification = notification;
        this.destination = destination;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getId_user() {
        return id_user;
    }

    public void setId_user(int id_user) {
        this.id_user = id_user;
    }

    public String getNotification() {
        return notification;
    }

    public void setNotification(String notification) {
        this.notification = notification;
    }

    public String getDestination() {
        return destination;
    }

    public void setDestination(String destination) {
        this.destination = destination;
    }

    @Override
    public String toString() {
        return "Notification{" + "id=" + id + ", id_user=" + id_user + ", notification=" + notification + ", destination=" + destination + '}';
    }
    
}
