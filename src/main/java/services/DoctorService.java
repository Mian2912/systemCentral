/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package services;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.util.ArrayList;
import java.util.List;
import models.Cite;
import models.Doctor;
import models.Employee;
import org.json.simple.JSONObject;
import org.json.simple.parser.ParseException;
import helpers.Helpers;

/**
 *
 * @author migue
 */
public class DoctorService {
      
    public List<Doctor> getDoctors(){
        
        List<Doctor> doctors = new ArrayList<>();
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/doctors");
            JSONObject data = Helpers.getResponse(conn);
            List<JSONObject> medicos = (List<JSONObject>) data.get("doctors");
            for(JSONObject medico : medicos){
                Doctor doctor = new Doctor();
                doctor.setId(Integer.parseInt(medico.get("id").toString()));
                doctor.setName(medico.get("name").toString());
                doctor.setLastname(medico.get("lastname").toString());
                doctor.setType_document(medico.get("type_document").toString());
                doctor.setDocument(medico.get("document").toString());
                
                doctors.add(doctor);
            }
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        return doctors;
    }
    
    public Doctor getDoctorById(Doctor doctor, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/doctor/"+doctor.getId());
            conn.setRequestProperty("Authorization", admin.getToken());
            
            JSONObject data = Helpers.getResponse(conn);
            JSONObject medico = (JSONObject) data.get("doctor");
            System.out.println(medico);
            doctor.setName(medico.get("name").toString());
            doctor.setLastname(medico.get("lastname").toString());
            doctor.setType_document(medico.get("type_document").toString());
            doctor.setDocument(medico.get("document").toString());
            doctor.setPhone(medico.get("phone").toString());
            doctor.setId_speciality(Integer.parseInt(medico.get("id_speciality").toString()));
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return doctor;
    }

    public Doctor setAddDoctor(Doctor doctor, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("admin/doctor", "POST");
            conn.setRequestProperty("Authorization", admin.getToken());
            
            String parameters = "json="+doctor.getJson();
            JSONObject json = Helpers.getResponseWithPostAndPut(conn, parameters);
            doctor.setAlert(json.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return doctor;
    }
    
    public Doctor updateDoctor(Doctor doctor, Employee admin){
        
        try{

            HttpURLConnection conn = Helpers.updateORCreate("admin/doctor/"+doctor.getId(), "PUT");
            conn.setRequestProperty("Authorization", admin.getToken());
            
            String paramaters = "json="+doctor.getJson();
            JSONObject json = Helpers.getResponseWithPostAndPut(conn, paramaters);
            
            doctor.setAlert(json.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return doctor;
    }
    
    public Doctor deleteDoctor(Doctor doctor, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.delete("admin/doctor/"+doctor.getId());
            conn.setRequestProperty("Authorization", admin.getToken());
            
            JSONObject data = Helpers.getResponse(conn);
            doctor.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return doctor;
    }
    
    public Doctor getDoctorByParams(Doctor doctor){
        try{
            String remplazeSpace = doctor.getName().replace(" ", "_");
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/doctor/"+remplazeSpace);
            JSONObject data = Helpers.getResponse(conn);
            JSONObject json = (JSONObject) data.get("doctor");
            doctor.setId(Integer.parseInt(json.get("id").toString())); 
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return doctor;
    }
    
    public List<Doctor> getDoctorsBySpeciality(Cite cite){
        
        List<Doctor> doctors = new ArrayList<>();
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/doctors/"+cite.getId_speciality());
            JSONObject data = Helpers.getResponse(conn);
            List<JSONObject> medicos = (List<JSONObject>) data.get("doctors");
           
            for(JSONObject medico :medicos){
                Doctor doctor = new Doctor();
                doctor.setId(Integer.parseInt(medico.get("id").toString()));
                doctor.setName(medico.get("name").toString());
                
                doctors.add(doctor);
            }
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
            
        return doctors;
    }
}
