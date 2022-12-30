/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package models;

/**
 *
 * @author migue
 */
public class Doctor {
    
    private int id;
    private String name;
    private String lastname;
    private String type_document;
    private String document;
    private String phone;
    private int id_speciality;
    private String alert;

    public Doctor() {
    }

    public Doctor(int id) {
        this.id = id;
    }

    public Doctor(String name, String lastname, String type_document, String document, String phone, int id_speciality) {
        this.name = name;
        this.lastname = lastname;
        this.type_document = type_document;
        this.document = document;
        this.phone = phone;
        this.id_speciality = id_speciality;
    }

    public Doctor(int id, String name, String lastname, String type_document, String document, String phone, int id_speciality) {
        this.id = id;
        this.name = name;
        this.lastname = lastname;
        this.type_document = type_document;
        this.document = document;
        this.phone = phone;
        this.id_speciality = id_speciality;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getLastname() {
        return lastname;
    }

    public void setLastname(String lastname) {
        this.lastname = lastname;
    }

    public String getType_document() {
        return type_document;
    }

    public void setType_document(String type_document) {
        this.type_document = type_document;
    }

    public String getDocument() {
        return document;
    }

    public void setDocument(String document) {
        this.document = document;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public int getId_speciality() {
        return id_speciality;
    }

    public void setId_speciality(int id_speciality) {
        this.id_speciality = id_speciality;
    }

    public String getAlert() {
        return alert;
    }

    public void setAlert(String alert) {
        this.alert = alert;
    }
    
    public String getJson(){
        return "{\"id\":\""+id+"\",\"name\":\""+name+"\",\"lastname\":\""+lastname+"\",\"type_document\":\""+type_document+"\",\"document\":\""+document+"\", \"phone\":\""+phone+"\",\"id_speciality\":\""+id_speciality+"\"}";
    }
    
    @Override
    public String toString() {
        return "Doctor{" + "id=" + id + ", name=" + name + ", lastname=" + lastname + ", type_document=" + type_document + ", document=" + document + ", phone=" + phone + ", id_speciality=" + id_speciality + '}';
    }
    
}
