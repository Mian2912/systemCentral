/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package models;

/**
 *
 * @author migue
 */
public class Speciality {
    
    private int id;
    private String Specialitys;
    private String alert;

    public Speciality() {
    }

    public Speciality(int id) {
        this.id = id;
    }

    public Speciality(String Specialitys) {
        this.Specialitys = Specialitys;
    }

    public Speciality(int id, String Specialitys) {
        this.id = id;
        this.Specialitys = Specialitys;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getSpecialitys() {
        return Specialitys;
    }

    public void setSpecialitys(String Specialitys) {
        this.Specialitys = Specialitys;
    }

    public String getAlert() {
        return alert;
    }

    public void setAlert(String alert) {
        this.alert = alert;
    }
    
    public String getJson(){
        return "{\"id\":\""+id+"\",\"specialitys\":\""+Specialitys+"\"}";
    }
    
    @Override
    public String toString() {
        return "Speciality{" + "id=" + id + ", Specialitys=" + Specialitys + '}';
    }
    
}
