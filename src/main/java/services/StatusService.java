/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package services;

import java.io.IOException;
import java.net.HttpURLConnection;
import models.Employee;
import models.Status;
import org.json.simple.JSONObject;
import org.json.simple.parser.ParseException;
import helpers.Helpers;

/**
 *
 * @author migue
 */
public class StatusService {
       
    public Status getStatusById(Status status, Employee employee){
        
        try{

            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("employee/status/"+status.getId());
            conn.setRequestProperty("Authorization", employee.getToken());
            
            JSONObject json = Helpers.getResponse(conn);
            JSONObject estado = (JSONObject) json.get("status");
            
            status.setId(Integer.parseInt(estado.get("id").toString()));
            status.setStatus(estado.get("status").toString());

        }catch(IOException | ParseException ex){
            System.out.println(ex.getMessage());
        }
        
        return status;
    }
    
}
