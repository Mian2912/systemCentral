/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package models;

/**
 *
 * @author migue
 */
public class Cite {
    
    private int id;
    private String name;
    private String lastname;
    private String type_document;
    private String document;
    private String phone;
    private String eps;
    private int id_speciality;
    private int id_doctors;
    private String date;
    private String hour;
    private String required_files;
    private String orden;
    private String authorization;
    private int id_status;
    private String recommendations;
    private String motiveCanceld;
    private int id_user;
    private int id_employee;
    private String alert;

    public Cite() {
    }

    public Cite(int id) {
        this.id = id;
    }

    public Cite(int id, String name, String lastname, String type_document, String document, String phone, String eps, int id_speciality) {
        this.id = id;
        this.name = name;
        this.lastname = lastname;
        this.type_document = type_document;
        this.document = document;
        this.phone = phone;
        this.eps = eps;
        this.id_speciality = id_speciality;
    }

    public Cite(int id, int id_doctors, String required_files, String orden, String authorization, int id_status, String recommendations, int id_employee) {
        this.id = id;
        this.id_doctors = id_doctors;
        this.required_files = required_files;
        this.orden = orden;
        this.authorization = authorization;
        this.id_status = id_status;
        this.recommendations = recommendations;
        this.id_employee = id_employee;
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

    public String getEps() {
        return eps;
    }

    public void setEps(String eps) {
        this.eps = eps;
    }

    public int getId_speciality() {
        return id_speciality;
    }

    public void setId_speciality(int id_speciality) {
        this.id_speciality = id_speciality;
    }

    public int getId_doctors() {
        return id_doctors;
    }

    public void setId_doctors(int id_doctors) {
        this.id_doctors = id_doctors;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getHour() {
        return hour;
    }

    public void setHour(String hour) {
        this.hour = hour;
    }
    
    public String getRequired_files() {
        return required_files;
    }

    public void setRequired_files(String required_files) {
        this.required_files = required_files;
    }

    public String getOrden() {
        return orden;
    }

    public void setOrden(String orden) {
        this.orden = orden;
    }

    public String getAuthorization() {
        return authorization;
    }

    public void setAuthorization(String authorization) {
        this.authorization = authorization;
    }

    public int getId_status() {
        return id_status;
    }

    public void setId_status(int id_status) {
        this.id_status = id_status;
    }

    public String getRecommendations() {
        return recommendations;
    }

    public void setRecommendations(String recommendations) {
        this.recommendations = recommendations;
    }

    public String getMotiveCanceld() {
        return motiveCanceld;
    }

    public void setMotiveCanceld(String motiveCanceld) {
        this.motiveCanceld = motiveCanceld;
    }

    public int getId_user() {
        return id_user;
    }

    public void setId_user(int id_user) {
        this.id_user = id_user;
    }

    public int getId_employee() {
        return id_employee;
    }

    public void setId_employee(int id_employee) {
        this.id_employee = id_employee;
    }

    public String getAlert() {
        return alert;
    }

    public void setAlert(String alert) {
        this.alert = alert;
    }
    
    public String getJson(){
        return "{\"name\":\""+name+"\", \"lastname\":\""+lastname+"\",\"type_document\":\""+type_document+"\",\"document\":\""+document+"\",\"phone\":\""+phone+"\",\"eps\":\""+eps+"\",\"id_speciality\":\""+id_speciality+"\",\"id_doctors\":\""+id_doctors+"\",\"date\":\""+date+"\",\"hour\":\""+hour+"\",\"required_files\":\""+required_files+"\",\"orden\":\""+orden+"\",\"authorization\":\""+authorization+"\",\"id_status\":\""+id_status+"\",\"recommendations\":\""+recommendations+"\",\"id_user\":\""+id_user+"\" }";
    }

    @Override
    public String toString() {
        return "Cite{" + "id=" + id + ", name=" + name + ", lastname=" + lastname + ", type_document=" + type_document + ", document=" + document + ", phone=" + phone + ", eps=" + eps + ", id_speciality=" + id_speciality + ", id_doctors=" + id_doctors + ", date=" + date + ", hour=" + hour + ", required_files=" + required_files + ", orden=" + orden + ", authorization=" + authorization + ", id_status=" + id_status + ", recommendations=" + recommendations + ", id_user=" + id_user + ", id_employee=" + id_employee + '}';
    }

}
