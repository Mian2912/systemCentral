/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package services;

import helpers.Helpers;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.util.ArrayList;
import java.util.List;
import models.Employee;
import models.Speciality;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.ParseException;

/**
 *
 * @author migue
 */
public class SpecialityService {
    
    public List<Speciality> getSpecilitys(){
        List<Speciality> specialitys = new ArrayList<>();
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/specialitys");

            JSONObject json = Helpers.getResponse(conn);
            ArrayList<JSONArray> especialidades = new ArrayList<>();
            especialidades.add((JSONArray) json.get("specialitys"));
            
            for (JSONArray especialidad : especialidades){
                for(int i = 0; especialidad.size() > i; i++){
                    JSONObject dataSpeciality = (JSONObject) especialidad.get(i);
                    Speciality speciality = new Speciality(Integer.parseInt(dataSpeciality.get("id").toString()), dataSpeciality.get("specialitys").toString());
                    
                    specialitys.add(speciality);
                }
            }
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return specialitys;
    }
    
    public Speciality addSpeciality(Speciality speciality, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("admin/speciality", "POST");
            conn.setRequestProperty("Authorization", admin.getToken());

            String parameters = "json="+speciality.getJson();
            JSONObject data = Helpers.getResponseWithPostAndPut(conn, parameters);
            
            speciality.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        } 
        
        return speciality;
    }
    
    public Speciality getSpecialityById(Speciality speciality){
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/speciality/"+speciality.getId());
            
            JSONObject data = Helpers.getResponse(conn);
            JSONObject specialityData = (JSONObject) data.get("speciality");
            speciality.setId(Integer.parseInt(specialityData.get("id").toString()));
            speciality.setSpecialitys(specialityData.get("specialitys").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        } 
        
        return speciality;
    }
    
    public Speciality updateSpeciality(Speciality speciality, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("admin/speciality/"+speciality.getId(), "PUT");
            conn.setRequestProperty("Authorization", admin.getToken());
            
            String parameters = "json="+speciality.getJson(); 
            JSONObject data = Helpers.getResponseWithPostAndPut(conn, parameters);
           
            speciality.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return speciality;
    }
    
    public Speciality deleteSpeciality(Speciality speciality, Employee admin){
        try{
            HttpURLConnection conn = Helpers.delete("admin/speciality/"+speciality.getId());
            conn.setRequestProperty("Authorization", admin.getToken());         
            JSONObject data = Helpers.getResponse(conn);
            speciality.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return speciality;
    }
    
    public Speciality getSpecialityByParams(Speciality speciality, Employee admin){
        
        try{
            String remplazeSpace = speciality.getSpecialitys().replace(" ", "_");
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/specialitys/"+remplazeSpace);
            conn.setRequestProperty("Authorization", admin.getToken());
            
            JSONObject data = Helpers.getResponse(conn);
            JSONObject special = (JSONObject) data.get("speciality");
            speciality.setId(Integer.parseInt(special.get("id").toString()));
             
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage()+" " + ex.getLocalizedMessage());
        }
        return speciality;
    }
}