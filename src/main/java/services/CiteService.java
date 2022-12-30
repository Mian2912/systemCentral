/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package services;

import controllers.CiteController;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.util.ArrayList;
import java.util.List;
import models.Cite;
import models.Employee;
import org.json.simple.JSONObject;
import org.json.simple.parser.ParseException;
import helpers.Helpers;


/**
 *
 * @author migue
 */
public class CiteService {

    public List<Cite> getCites(Employee employee){
        
        List<Cite> cites = new ArrayList<>();
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/cites");
            conn.setRequestProperty("Authorization", employee.getToken());
            
            JSONObject json = Helpers.getResponse(conn);
            List<JSONObject> citas = (List<JSONObject>) json.get("cites");
            for (JSONObject cita :citas){
                Cite cite = new Cite();
                cite.setId(Integer.parseInt(cita.get("id").toString()));
                cite.setName(cita.get("name").toString());
                cite.setLastname(cita.get("lastname").toString());
                cite.setType_document(cita.get("type_document").toString());
                cite.setDocument(cita.get("document").toString());
                cite.setId_status(Integer.parseInt(cita.get("id_status").toString()));
                cites.add(cite);  
            }
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
                
        return cites;
    }
    
    public Cite getCiteById(Employee employee, Cite cite){
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/cite/"+cite.getId());
            conn.setRequestProperty("Authorization", employee.getToken());
           
            JSONObject data = Helpers.getResponse(conn);
            JSONObject json = (JSONObject) data.get("cite");
            
            cite.setName(json.get("name").toString());
            cite.setLastname(json.get("lastname").toString());
            cite.setType_document(json.get("type_document").toString());
            cite.setDocument(json.get("document").toString());
            cite.setPhone(json.get("phone").toString());
            cite.setEps(json.get("eps").toString());
            cite.setId_speciality(Integer.parseInt(json.get("id_speciality").toString()));
            cite.setRecommendations((String)json.get("recommendations"));
            cite.setMotiveCanceld((String) json.get("motiveCanceld"));
            cite.setOrden((String)json.get("orden"));
            cite.setAuthorization((String) json.get("authorization"));
            cite.setId_status(Integer.parseInt(json.get("id_status").toString()));
            
            cite.setId_user(Integer.parseInt(json.get("id_user").toString()));
            
            cite = new CiteController().confirmCite(cite, employee);
               
        }catch(IOException | ParseException ex){
           System.out.println(ex.getMessage());
        }
        return cite;
    }
    
    public Cite confirmCite(Cite cite, Employee employee){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("employee/cite/"+cite.getId(), "PUT");
            conn.setRequestProperty("Authorization", employee.getToken());           
            
            String parameters = "json="+cite.getJson();
            JSONObject data = Helpers.getResponseWithPostAndPut(conn, parameters);
            cite.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }

        return cite;
    }
    
    public List<Cite> getCitesByParameters(String parameters, Employee employee){
        List<Cite> cites = new ArrayList<>();
        
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/searchCites/"+parameters);
            conn.setRequestProperty("Authorization", employee.getToken());
            
            JSONObject json = Helpers.getResponse(conn);
            List<JSONObject> citas = (List<JSONObject>) json.get("cites");
            for (JSONObject cita :citas){
                Cite cite = new Cite();
                cite.setId(Integer.parseInt(cita.get("id").toString()));
                cite.setName(cita.get("name").toString());
                cite.setLastname(cita.get("lastname").toString());
                cite.setType_document(cita.get("type_document").toString());
                cite.setDocument(cita.get("document").toString());
                cite.setId_status(Integer.parseInt(cita.get("id_status").toString()));
                cites.add(cite);  
            }
            
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
                
        return cites;
    }
    
    public Cite solicitedFiles(Cite cite, Employee employee){
        
        try {
            
            HttpURLConnection conn = Helpers.updateORCreate("employee/requiredFiles/"+cite.getId(), "PUT");
            conn.setRequestProperty("Authorization", employee.getToken());
            
            String parameters = "json="+cite.getJson();
            JSONObject json = Helpers.getResponseWithPostAndPut(conn, parameters);
            
            cite.setAlert(json.get("message").toString());
        } catch (IOException | ParseException ex) {
            System.out.println(ex.getMessage());
        }
        return cite;
    }
    
    public Cite delete(Cite cite, Employee employee){
        
        try{
            HttpURLConnection conn = Helpers.delete("employee/deleteCite/"+cite.getId());
            conn.setRequestProperty("Authorization", employee.getToken());
            JSONObject json = Helpers.getResponse(conn);
            cite.setAlert((String) json.get("message"));
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return cite;
    }
    
    public String getdf(Cite cite, Employee employee, String file){
       
        String pdf = "";
        try{
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/getFile/"+file);
            conn.setRequestProperty("Authorization", employee.getToken());
            JSONObject json = Helpers.getResponse(conn);
            pdf = (String) json.get("pdf");
        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }

        return pdf;
    }
}