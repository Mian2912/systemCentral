/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package models;

/**
 *
 * @author migue
 */
public class Employee {
    
    private int id;
    private String name;
    private String lastname;
    private String type_document;
    private String document;
    private String phone;
    private String email;
    private String password;
    private int id_rol;
    private String gettoken;
    
    private String alert;
    private String token;

    public Employee() {
    }

    public Employee(int id) {
        this.id = id;
    }

    public Employee(String email, String password) {
        this.email = email;
        this.password = password;
    }

    public Employee(int id, String name, String lastname, String type_document, String document, String phone) {
        this.id = id;
        this.name = name;
        this.lastname = lastname;
        this.type_document = type_document;
        this.document = document;
        this.phone = phone;
    }
    
    public Employee(String name, String lastname, String type_document, String document, String phone, String email, String password) {
        this.name = name;
        this.lastname = lastname;
        this.type_document = type_document;
        this.document = document;
        this.phone = phone;
        this.email = email;
        this.password = password;
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

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public int getId_rol() {
        return id_rol;
    }

    public void setId_rol(int id_rol) {
        this.id_rol = id_rol;
    }

    public String getAlert() {
        return alert;
    }

    public void setAlert(String alert) {
        this.alert = alert;
    }

    public String isGettoken() {
        return gettoken;
    }

    public void setGettoken(String gettoken) {
        this.gettoken = gettoken;
    }
    
    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }
    
    public String json(){
        return "{\"id\":\""+id+"\",\"name\":\""+name+"\",\"lastname\":\""+lastname+"\",\"type_document\":\""+type_document+"\",\"document\":\""+document+"\",\"phone\":\""+phone+"\",\"email\":\""+email+"\",\"password\":\""+password+"\"}";
    }
    
    @Override
    public String toString() {
        return "{\"id\":\""+id+"\",\"name\":\""+name+"\",\"lastname\":\""+lastname+"\",\"type_document\":\""+type_document+"\",\"document\":\""+document+"\",\"phone\":\""+phone+"\",\"email\":\""+email+"\",\"password\":\""+password+"\",\"gettoken\":\""+gettoken+"\"}";
    }

}
